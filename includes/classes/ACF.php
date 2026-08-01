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
		self::forActivity();
		self::forCertificate();

		//Taxonomies

		//Page Templates
		self::forContactUs();
		self::forAboutUs();
		self::forBlogs();

		//Menu Items

	}

	private static function forActivity()
	{
		$acfGroup = new AcfGroup();

		$acfGroup->basicFields->addTextarea('activity_description', 'توضیحات', ['rows' => 3]);
		$acfGroup->relationshipFields->addLink('activity_btn', 'دکمه مشاهده محصولات');

		$acfGroup->setLocation('post_type', '==', 'activity');

		$acfGroup->register('Activity');
	}

	private static function forCertificate()
	{
		$acfGroup = new AcfGroup();

		$acfGroup->basicFields->addText('certificate_issuer', 'مرجع صادرکننده', ['width' => '50']);
		$acfGroup->basicFields->addText('certificate_number', 'شماره گواهینامه', ['width' => '50']);
		$acfGroup->basicFields->addText('certificate_issue_date', 'تاریخ صدور', ['width' => '50']);
		$acfGroup->basicFields->addText('certificate_expiry_date', 'تاریخ اعتبار', ['width' => '50']);
		$acfGroup->contentFields->addFile('certificate_file', 'فایل دانلود', ['return_format' => 'url', 'width' => '50']);
		$acfGroup->basicFields->addUrl('certificate_view_url', 'لینک مشاهده', ['width' => '50']);

		$acfGroup->setLocation('post_type', '==', 'certificate');

		$acfGroup->register('Certificate');
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

		$acfGroup->layoutFields->addTab('about_intro_tab', '(هیرو) معرفی');
		$acfGroup->basicFields->addText('about_title', 'عنوان', ['default_value' => __('درباره‌ی ما', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addText('about_subtitle', 'زیرعنوان', ['default_value' => __('کیفیت، تعهد و تخصص در ساخت سازه های پیش ساخته شده', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addTextarea('about_description', 'توضیحات', ['default_value' => __('ما مجموعه‌ای تخصصی در زمینه طراحی، تولید و اجرای انواع ویلای پیش‌ساخته، کانکس، کانتینرهای تجهیزشده و کلبه‌های چوبی هستیم. هدف ما ساخت سازه‌هایی است که علاوه بر سرعت اجرا، از نظر کیفیت، ایمنی، زیبایی و کارایی نیز پاسخ‌گوی نیاز واقعی مشتریان باشند.', 'novavilla'), 'rows' => 4]);
		$acfGroup->relationshipFields->addLink('products_btn', 'دکمه محصولات ما', ['width' => '50']);
		$acfGroup->relationshipFields->addLink('certificates_btn', 'دکمه گواهینامه ها', ['width' => '50']);

		$acfGroup->layoutFields->addTab('about_story_tab', 'متن و ویدیو');
		$acfGroup->basicFields->addText('about_story_title', 'عنوان', ['default_value' => __('رسالت ما', 'novavilla')]);
		$acfGroup->basicFields->addTextarea('about_story_description', 'توضیحات', ['rows' => 5, 'default_value' => __('ما مجموعه‌ای تخصصی در زمینه طراحی، تولید و اجرای انواع ویلای پیش‌ساخته، کانکس، کانتینرهای تجهیزشده و کلبه‌های چوبی هستیم. هدف ما ساخت سازه‌هایی است که علاوه بر سرعت اجرا، از نظر کیفیت، ایمنی، زیبایی و کارایی نیز پاسخ‌گوی نیاز واقعی مشتریان باشند.', 'novavilla')]);
		$acfGroup->contentFields->addFile('about_video_file', 'فایل ویدیو', ['return_format' => 'url', 'width' => '50']);
		$acfGroup->contentFields->addImage('about_video_cover', 'تصویر پوشش ویدیو', ['return_format' => 'url', 'width' => '50']);

		$acfGroup->layoutFields->addTab('about_activities_tab', 'حوزه‌های فعالیت');
		$acfGroup->basicFields->addText('activities_title', 'عنوان بخش', ['default_value' => __('حوزه‌های فعالیت ما', 'novavilla')]);

		$acfGroup->layoutFields->addTab('about_certificates_tab', 'گواهینامه‌ها');
		$acfGroup->basicFields->addText('about_certificates_title', 'عنوان', ['default_value' => __('گواهینامه‌ها و استانداردهای ما', 'novavilla')]);
		$acfGroup->basicFields->addTextarea('about_certificates_desc', 'توضیحات', ['rows' => 3, 'default_value' => __('کیفیت، ایمنی و دوام محصولات ما بر پایه استانداردهای فنی و فرآیندهای کنترل‌شده است. در این بخش می‌توانید گواهینامه‌ها، مجوزها و تأییدیه‌های مجموعه را مشاهده کنید.', 'novavilla')]);
		$acfGroup->contentFields->addImage('about_certificates_image', 'تصویر بخش', ['return_format' => 'url']);
		$acfGroup->basicFields->addText('about_cert_stat_value_1', 'مقدار آمار ۱', ['default_value' => '+ 12', 'width' => '50']);
		$acfGroup->basicFields->addText('about_cert_stat_label_1', 'برچسب آمار ۱', ['default_value' => __('گواهینامه معتبر', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addText('about_cert_stat_value_2', 'مقدار آمار ۲', ['default_value' => '+ 10', 'width' => '50']);
		$acfGroup->basicFields->addText('about_cert_stat_label_2', 'برچسب آمار ۲', ['default_value' => __('سال تجربه اجرایی', 'novavilla'), 'width' => '50']);

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
			$acfGroup->contentFields->addImage("why_icon_{$i}", "آیکون {$i}", ['return_format' => 'array', 'width' => '20']);
			$acfGroup->basicFields->addText("why_item_title_{$i}", "عنوان {$i}", ['default_value' => $item['title'], 'width' => '40']);
			$acfGroup->basicFields->addTextarea("why_item_desc_{$i}", "توضیح {$i}", ['default_value' => $item['desc'], 'rows' => 3, 'width' => '40']);
		}

		$acfGroup->setLocation('page_template', '==', 'templates/about-us.php');

		$acfGroup->register('About-Us');
	}

	private static function forBlogs()
	{
		$acfGroup = new AcfGroup();

		$acfGroup->basicFields->addText('blogs_title', 'عنوان بخش', ['default_value' => __('جدیدترین مقالات', 'novavilla')]);

		$acfGroup->setLocation('page_template', '==', 'templates/blogs.php');

		$acfGroup->register('Blogs');
	}
}
