<?php

use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;

$args = get_query_var('args', []);
$source_page_id = (int) ($args['source_page_id'] ?? get_the_ID());
$phone_number = get_option('phone_number');
$input_class = 'w-full rounded-xl border border-cynBorder dark:border-white/40 bg-cynWhite/8 backdrop-blur-md px-4 ps-12 py-4 text-sm md:text-base font-normal text-cynTextPrimary placeholder:text-cynTextPrimary/50 dark:placeholder:text-cynWhite/80 focus:outline-none focus:border-cynBorderHover transition-all duration-300';
$icon_wrap_class = 'size-7 shrink-0 absolute start-3 top-1/2 -translate-y-1/2 pointer-events-none text-cynBorderHover flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-current [&_svg]:stroke-[1.5]';
$channel_label_class = 'group flex items-center gap-2 rounded-xl border border-cynBorder dark:border-white/30 bg-white/8 backdrop-blur-md px-3 py-4 cursor-pointer transition-all duration-300 has-[:checked]:border-cynBorderHover';
$channels = [
	'whatsapp' => __('درخواست مشاوره در واتساپ', 'novavilla'),
	'telegram' => __('درخواست مشاوره در تلگرام', 'novavilla'),
	'bale' => __('درخواست مشاوره در بله', 'novavilla'),
];
?>

<form
	id="product_consult_form"
	action="<?php echo esc_url(rest_url('cyn/v1/session_request')); ?>"
	method="post"
	hx-post="<?php echo esc_url(rest_url('cyn/v1/session_request')); ?>"
	hx-headers='{"X-WP-Nonce":"<?php echo esc_attr(wp_create_nonce('wp_rest')); ?>"}'
	hx-target="#product-consult-success"
	hx-swap="innerHTML"
	hx-disabled-elt="find button[type=submit]"
	hx-on::before-request="const btn = event.target.querySelector('button[type=submit]'); if (btn) btn.classList.add('is-loading'); const err = document.querySelector('#product-consult-error'); if (err) { err.hidden = true; err.textContent = ''; }"
	hx-on::after-request="const btn = event.target.querySelector('button[type=submit]'); if (btn) btn.classList.remove('is-loading'); if (event.detail.successful) { event.target.reset(); const modal = event.target.closest('[modal]'); if (modal) modal.scrollTop = 0; }"
	hx-on::before-swap="if (!event.detail.successful) { event.detail.shouldSwap = false; const err = document.querySelector('#product-consult-error'); let msg = ''; try { const data = JSON.parse(event.detail.xhr.responseText); msg = data.error || data.message || ''; } catch (e) { msg = '<?php echo esc_js(__('خطا در ارسال فرم.', 'novavilla')); ?>'; } if (err) { err.textContent = msg; err.hidden = false; } }"
	class="flex flex-col gap-5 md:gap-6">

	<input type="hidden" name="html_success" value="1" />
	<input type="hidden" name="request_type" value="consultation" />
	<input type="hidden" name="source_page_id" value="<?php echo esc_attr((string) $source_page_id); ?>" />

	<div class="flex flex-col gap-3">
		<span class="text-sm md:text-base font-semibold text-cynTextPrimary leading-6">
			<?php esc_html_e('اطلاعات خود را وارد کنید', 'novavilla'); ?>
		</span>
		<div class="flex flex-col gap-2 md:gap-3">
			<label class="relative block">
				<i class="<?php echo esc_attr($icon_wrap_class); ?>">
					<?php Icon::print('User,-Profile-7'); ?>
				</i>
				<input type="text" name="name" required autocomplete="name" placeholder="<?php esc_attr_e('نام شما', 'novavilla'); ?>" class="<?php echo esc_attr($input_class); ?>" />
			</label>
			<label class="relative block">
				<i class="<?php echo esc_attr($icon_wrap_class); ?>">
					<?php Icon::print('Phone,-Call-11'); ?>
				</i>
				<input type="tel" name="phone" required autocomplete="tel" inputmode="numeric" maxlength="11" pattern="[0-9]{11}" dir="rtl" placeholder="<?php esc_attr_e('شماره تماس', 'novavilla'); ?>" class="<?php echo esc_attr($input_class); ?>" />
			</label>
		</div>
	</div>

	<div class="flex flex-col gap-3">
		<span class="text-base font-semibold text-cynTextPrimary leading-6">
			<?php esc_html_e('راه ارتباطی موردنظرتان را انتخاب کنید', 'novavilla'); ?>
		</span>
		<div class="flex flex-col gap-2 md:gap-3">
			<?php foreach ($channels as $value => $label) : ?>
				<label class="<?php echo esc_attr($channel_label_class); ?>">
					<span class="size-6 shrink-0 rounded-md border border-cynBorder dark:border-white flex items-center justify-center transition-all duration-300 group-has-[:checked]:border-cynBorderHover group-has-[:checked]:bg-cynBorderHover">
						<i class="size-3.5 opacity-0 text-cynBlack flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-current group-has-[:checked]:opacity-100">
							<?php Icon::print('check'); ?>
						</i>
					</span>
					<input type="radio" name="channel" value="<?php echo esc_attr($value); ?>" class="sr-only" <?php checked($value, 'whatsapp'); ?> />
					<span class="text-sm md:text-base font-normal text-cynTextPrimary leading-5 group-has-[:checked]:text-cynBorderHover transition-all duration-300">
						<?php echo esc_html($label); ?>
					</span>

				</label>
			<?php endforeach; ?>
		</div>
	</div>

	<p id="product-consult-error" class="text-sm font-medium text-cynRed" role="alert" hidden></p>

	<div class="flex items-stretch gap-2 justify-end">
		<?php if ($phone_number) : ?>
			<a href="<?php echo esc_url('tel:' . $phone_number); ?>" class="inline-flex items-center self-stretch rounded-xl border border-cynBorderHover/40 bg-cynWhite/8 px-5 py-3 text-xs md:text-sm font-semibold leading-none text-cynTextPrimary transition-all duration-300 hover:border-cynBorderHover">
				<span class="whitespace-nowrap">
					<?php esc_html_e('تماس با مشاورین ما', 'novavilla'); ?>
				</span>
			</a>
		<?php endif; ?>
		<button type="submit" class="submit-contact-btn primary-button btn-have-icon !py-2 w-fit">
			<span class="submit-contact-btn__idle flex items-center gap-1">
				<span class="text-xs md:text-sm font-semibold whitespace-nowrap">
					<?php esc_html_e('ارسال درخواست', 'novavilla'); ?>
				</span>
				<i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
					<?php Icon::print('Arrow-27'); ?>
				</i>
			</span>
			<span class="submit-contact-btn__loading items-center gap-1">
				<span class="size-5 stroke-[1.5] flex items-center justify-center animate-spin">
					<?php Icon::print('Rotate,-Refresh,-Loading'); ?>
				</span>
				<span class="text-xs md:text-sm font-semibold whitespace-nowrap">
					<?php esc_html_e('در حال ارسال...', 'novavilla'); ?>
				</span>
			</span>
		</button>
	</div>
</form>