<?php

/**
 * Rest API
 * this class is used to register rest routes and handle requests
 * @package Cyan\Theme\Classes
 */

namespace Cyan\Theme\Classes;

use WP_REST_Request;
use WP_REST_Response;

class Rest
{

	protected static $namespace = 'cyn/v1';

	public static function init()
	{
		add_action('rest_api_init', [__CLASS__, 'registerRoutes']);
	}

	public static function registerRoutes()
	{
		self::makeRoute('/contact_form', 'POST', [__CLASS__, 'createForm']);
		self::makeRoute('/session_request', 'POST', [__CLASS__, 'createSessionRequest']);
	}

	public static function createForm(WP_REST_Request $request)
	{
		$ip = self::getClientIp();

		// Minimum interval between two submissions (seconds)
		$min_interval = 120; // 2 minutes
		$rate_key = 'cyn_contact_last_' . md5($ip);
		$last_time = get_transient($rate_key);
		if ($last_time !== false && (time() - $last_time) < $min_interval) {
			$wait = $min_interval - (time() - $last_time);
			return new WP_REST_Response([
				'error' => sprintf(__('لطفاً %d ثانیه صبر کنید و دوباره تلاش کنید.', 'novavilla'), $wait)
			], 429);
		}

		// Maximum submissions per hour per IP
		$max_per_hour = 2;
		$count_key = 'cyn_contact_count_' . md5($ip);
		$count_data = get_transient($count_key);
		if ($count_data === false) {
			$count_data = ['count' => 0, 'start' => time()];
		}
		if ($count_data['count'] >= $max_per_hour) {
			return new WP_REST_Response([
				'error' => __('تعداد ارسال‌های شما در این ساعت به حد مجاز رسیده. لطفاً بعداً تلاش کنید.', 'novavilla')
			], 429);
		}

		$body = $request->get_body_params();

		$name = isset($body['name']) ? sanitize_text_field($body['name']) : '';
		$email_raw = isset($body['email']) ? trim($body['email']) : '';
		$email = $email_raw !== '' ? sanitize_email($email_raw) : '';
		$phone = isset($body['phone']) ? sanitize_text_field($body['phone']) : '';
		$message = isset($body['message']) ? sanitize_textarea_field($body['message']) : '';

		if (empty($phone) || empty($name) || empty($message)) {
			return new WP_REST_Response([
				'error' => __('نام، شماره تماس و پیام الزامی هستند.', 'novavilla'),
			], 400);
		}

		if ($email_raw !== '' && !is_email($email)) {
			return new WP_REST_Response([
				'error' => __('ایمیل وارد شده معتبر نیست.', 'novavilla'),
			], 400);
		}

		if (!preg_match('/^[0-9]{11}$/', $phone)) {
			return new WP_REST_Response([
				'error' => __('شماره تلفن معتبر نیست.', 'novavilla'),
			], 400);
		}

		$new_post = wp_insert_post([
			'post_type' => 'contact_form',
			'post_title' => $name,
			'post_status' => 'private',
			'meta_input' => [
				'_name' => $name,
				'_phone' => $phone,
				'_email' => $email,
				'_message' => $message,
				'_read' => '0',
			],
		], true);

		if (is_wp_error($new_post)) {
			return new WP_REST_Response([
				'error' => __('خطا در ثبت فرم، لطفاً دوباره تلاش کنید.', 'novavilla'),
			], 500);
		}

		set_transient($rate_key, time(), $min_interval);
		$count_data['count']++;
		set_transient($count_key, $count_data, HOUR_IN_SECONDS);

		return new WP_REST_Response([
			'message' => __('فرم با موفقیت ارسال شد.', 'novavilla'),
		], 200);
	}

	public static function createSessionRequest(WP_REST_Request $request)
	{
		$ip = self::getClientIp();
		$min_interval = 120;
		$rate_key = 'cyn_session_last_' . md5($ip);
		$last_time = get_transient($rate_key);
		if ($last_time !== false && (time() - $last_time) < $min_interval) {
			$wait = $min_interval - (time() - $last_time);
			return new WP_REST_Response([
				'error' => sprintf(__('لطفاً %d ثانیه صبر کنید و دوباره تلاش کنید.', 'novavilla'), $wait),
			], 429);
		}

		$max_per_hour = 2;
		$count_key = 'cyn_session_count_' . md5($ip);
		$count_data = get_transient($count_key);
		if ($count_data === false) {
			$count_data = ['count' => 0, 'start' => time()];
		}
		if ($count_data['count'] >= $max_per_hour) {
			return new WP_REST_Response([
				'error' => __('تعداد ارسال‌های شما در این ساعت به حد مجاز رسیده. لطفاً بعداً تلاش کنید.', 'novavilla'),
			], 429);
		}

		$body = $request->get_body_params();
		$html_success = !empty($body['html_success']);
		$is_product_consult = array_key_exists('name', $body) || array_key_exists('phone', $body);

		$name = '';
		$phone = '';
		$channel = '';
		$contact = '';
		$contact_type = '';

		if ($is_product_consult) {
			$name = isset($body['name']) ? sanitize_text_field($body['name']) : '';
			$phone = isset($body['phone']) ? sanitize_text_field($body['phone']) : '';
			$channel = isset($body['channel']) ? sanitize_key($body['channel']) : '';

			if ($name === '' || $phone === '') {
				return new WP_REST_Response(['error' => __('نام و شماره موبایل الزامی هستند.', 'novavilla')], 400);
			}
			if (!preg_match('/^[0-9]{11}$/', $phone)) {
				return new WP_REST_Response(['error' => __('شماره تلفن معتبر نیست.', 'novavilla')], 400);
			}
			if (!in_array($channel, ['whatsapp', 'telegram', 'bale'], true)) {
				return new WP_REST_Response(['error' => __('راه ارتباطی معتبر نیست.', 'novavilla')], 400);
			}

			$contact = $phone;
			$contact_type = 'phone';
			$request_type = 'consultation';
		} else {
			$contact_raw = isset($body['contact']) ? trim($body['contact']) : '';
			$contact = sanitize_text_field($contact_raw);

			if ($contact === '') {
				return new WP_REST_Response(['error' => __('ایمیل یا شماره همراه الزامی است.', 'novavilla')], 400);
			}
			if (is_email($contact)) {
				$contact_type = 'email';
			} elseif (preg_match('/^[0-9]{11}$/', $contact)) {
				$contact_type = 'phone';
			} else {
				return new WP_REST_Response(['error' => __('ایمیل یا شماره همراه معتبر نیست.', 'novavilla')], 400);
			}

			$request_type = isset($body['request_type']) ? sanitize_key($body['request_type']) : '';
			if (!in_array($request_type, ['session', 'consultation'], true)) {
				return new WP_REST_Response(['error' => __('نوع درخواست معتبر نیست.', 'novavilla')], 400);
			}
		}

		$source_page_id = isset($body['source_page_id']) ? absint($body['source_page_id']) : 0;
		$source_page_title = '';
		$source_url = '';
		if ($source_page_id && get_post($source_page_id)) {
			$source_page_title = get_the_title($source_page_id);
			$source_url = get_permalink($source_page_id);
		}

		$meta_input = [
			'_contact' => $contact,
			'_contact_type' => $contact_type,
			'_request_type' => $request_type,
			'_source_page_id' => $source_page_id,
			'_source_page_title' => $source_page_title,
			'_source_url' => $source_url,
			'_read' => '0',
		];
		if ($is_product_consult) {
			$meta_input['_name'] = $name;
			$meta_input['_phone'] = $phone;
			$meta_input['_channel'] = $channel;
		}

		$new_post = wp_insert_post([
			'post_type' => 'session_request',
			'post_title' => $is_product_consult ? $name : $contact,
			'post_status' => 'private',
			'meta_input' => $meta_input,
		], true);

		if (is_wp_error($new_post)) {
			return new WP_REST_Response(['error' => __('خطا در ثبت فرم، لطفاً دوباره تلاش کنید.', 'novavilla')], 500);
		}

		set_transient($rate_key, time(), $min_interval);
		$count_data['count']++;
		set_transient($count_key, $count_data, HOUR_IN_SECONDS);

		$message = __('درخواست شما با موفقیت ثبت شد.', 'novavilla');
		if ($html_success) {
			ob_start();
			include get_template_directory() . '/partials/parts/product/product-consult-success.php';
			$html = ob_get_clean();
			status_header(200);
			nocache_headers();
			header('Content-Type: text/html; charset=' . get_option('blog_charset'));
			echo $html;
			exit;
		}

		return new WP_REST_Response(['message' => $message], 200);
	}

	/**
	 * Get client IP address
	 * @return string
	 */
	private static function getClientIp()
	{
		$ip_keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];

		foreach ($ip_keys as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					$ip = trim($ip);
					if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
						return $ip;
					}
				}
			}
		}

		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
	}

	/**
	 * make route
	 * @param string $route route path
	 * @param string $methods GET, POST, PUT, DELETE, etc.
	 * @param callable $callback callback function
	 * @param callable $permission_callback permission callback function
	 * @return void
	 */
	private static function makeRoute($route, $methods, $callback, $permission_callback = '__return_true')
	{
		register_rest_route(self::$namespace, $route, [
			'methods' => $methods,
			'callback' => $callback,
			'permission_callback' => $permission_callback
		]);
	}
}
