<?php

/**
 * ACF Class
 * @package Cyan\Theme\Classes
 */

namespace Cyan\Theme\Classes;

use Cyan\Theme\Helpers\Validators;
use Cyan\Theme\Helpers\ACF\AcfGroup;


class ACF
{

	public static function init()
	{
		$isDev = ENVIRONMENT === 'development';
		$isDev ? null : add_filter('acf/settings/show_admin', '__return_false', 100);

		if (! function_exists('acf_add_local_field_group')) {
			return;
		}


		add_action('acf/include_fields', [__CLASS__, 'registerAllACF']);
	}

	/**
	 * Register all ACF fields for the individual post types, taxonomies, page templates, and menu items
	 * @return void
	 */
	public static function registerAllACF()
	{
		//PostTypes

		//Taxonomies

		//Page Templates
		self::forContactUs();
		self::forAboutUs();

		//Menu Items

	}

	private static function forContactUs()
	{
		//define helper
		$acfGroup = new AcfGroup();

		//add fields
		$acfGroup->basicFields->addText('contact_title', 'عنوان', ['default_value' => __('ارتباط با نووا ویلا', 'novavilla')]);
		$acfGroup->basicFields->addText('under_title', 'متن زیر عنوان', ['default_value' => __('برای شروع پروژه، با ما در ارتباط باشید', 'novavilla')]);

		$acfGroup->basicFields->addText('contact_box_title', 'عنوان باکس تماس با ما', ['default_value' => __('مراجعه حضوری', 'novavilla')]);
		$acfGroup->basicFields->addTextarea('contact_box_description', 'توضیحات باکس تماس با ما', ['default_value' => __('برای بازدید از نمونه محصولات، بررسی متریال‌ها و گفت‌وگوی حضوری با کارشناسان، می‌توانید با هماهنگی قبلی به دفتر یا مجموعه تولیدی ما مراجعه کنید.', 'novavilla')]);

		$acfGroup->basicFields->addText('visit_title', 'عنوان آدرس حضوری', ['default_value' => __('آدرس', 'novavilla')]);
		$acfGroup->basicFields->addText('opening_hours_title', 'عنوان ساعت کاری', ['default_value' => __('ساعات فعال', 'novavilla')]);
		$acfGroup->basicFields->addText('map_address_title', 'عنوان آدرس روی نرم افزار ها', ['default_value' => __('آدرس روی نقشه', 'novavilla')]);
		$acfGroup->basicFields->addText('contact_info_title', 'عنوان اطلاعات تماس', ['default_value' => __('شماره تماس و شبکه های اجتماعی', 'novavilla')]);

		//location
		$acfGroup->setLocation('page_template', '==', 'templates/contact-us.php');

		//register group
		$acfGroup->register('Contact Us');
	}

	private static function forAboutUs()
	{
		$acfGroup = new AcfGroup();

		$acfGroup->layoutFields->addTab('about_intro_tab', 'معرفی');
		$acfGroup->basicFields->addText('about_title', 'عنوان', ['default_value' => __('درباره‌ی ما', 'novavilla')]);
		$acfGroup->basicFields->addText('about_subtitle', 'زیرعنوان', ['default_value' => __('کیفیت، تعهد و تخصص در ساخت سازه های پیش ساخته شده', 'novavilla')]);
		$acfGroup->basicFields->addTextarea('about_description', 'توضیحات', ['default_value' => __('ما مجموعه‌ای تخصصی در زمینه طراحی، تولید و اجرای انواع ویلای پیش‌ساخته، کانکس، کانتینرهای تجهیزشده و کلبه‌های چوبی هستیم. هدف ما ساخت سازه‌هایی است که علاوه بر سرعت اجرا، از نظر کیفیت، ایمنی، زیبایی و کارایی نیز پاسخ‌گوی نیاز واقعی مشتریان باشند.', 'novavilla'), 'rows' => 4]);
		$acfGroup->relationshipFields->addLink('certificates_btn', 'دکمه گواهینامه ها');
		$acfGroup->relationshipFields->addLink('products_btn', 'دکمه محصولات ما');

		$acfGroup->layoutFields->addTab('about_why_tab', 'چرا مشتریان ما');
		$acfGroup->basicFields->addText('why_title', 'عنوان بخش', ['default_value' => __('چرا مشتریان ما را انتخاب می‌کنند؟', 'novavilla')]);

		$why_defaults = [
			1 => [
				'title' => __('طراحی قابل سفارشی سازی', 'novavilla'),
				'desc' => __('ابعاد، پلان، نما، متریال و امکانات داخلی هر سازه بر اساس نیاز و بودجه مشتری قابل تغییر است.', 'novavilla'),
			],
			2 => [
				'title' => __('تولید با کنترل کیفیت', 'novavilla'),
				'desc' => __('متریال، اتصالات، تأسیسات و جزئیات اجرایی در مراحل مختلف تولید بررسی و کنترل می‌شوند.', 'novavilla'),
			],
			3 => [
				'title' => __('تحویل شفاف و برنامه‌ریزی‌شده', 'novavilla'),
				'desc' => __('زمان تولید، هزینه‌ها، امکانات و شرایط حمل پیش از شروع پروژه به‌صورت شفاف مشخص می‌شوند.', 'novavilla'),
			],
			4 => [
				'title' => __('پشتیبانی از طراحی تا نصب', 'novavilla'),
				'desc' => __('تیم مجموعه از انتخاب محصول و طراحی اولیه تا حمل، نصب و تحویل نهایی در کنار مشتری خواهد بود.', 'novavilla'),
			],
			5 => [
				'title' => __('مناسب برای اقلیم‌های مختلف', 'novavilla'),
				'desc' => __('نوع سازه و عایق‌بندی بر اساس شرایط آب‌وهوایی محل اجرای پروژه انتخاب می‌شود.', 'novavilla'),
			],
			6 => [
				'title' => __('امکان حمل و نقل و جا به جایی', 'novavilla'),
				'desc' => __('بخش زیادی از محصولات با توجه به طراحی سازه، قابلیت حمل، نصب مجدد یا توسعه در آینده را دارند.', 'novavilla'),
			],
		];

		foreach ($why_defaults as $i => $item) {
			$acfGroup->contentFields->addImage("why_icon_{$i}", "آیکون {$i}", [
				'return_format' => 'array',
				'width' => '20',
			]);
			$acfGroup->basicFields->addText("why_item_title_{$i}", "عنوان {$i}", [
				'default_value' => $item['title'],
				'width' => '40',
			]);
			$acfGroup->basicFields->addTextarea("why_item_desc_{$i}", "توضیح {$i}", [
				'default_value' => $item['desc'],
				'rows' => 3,
				'width' => '40',
			]);
		}

		$acfGroup->setLocation('page_template', '==', 'templates/about-us.php');

		$acfGroup->register('About Us');
	}
}
