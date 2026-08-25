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
		self::forProduct();
		self::forProject();

		//Taxonomies

		//Page Templates
		self::forContactUs();
		self::forAboutUs();
		self::forBlogs();
		self::forHome();
		self::for3dStructure();
		self::forLanding();

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

	private static function forProduct()
	{
		$acfGroup = new AcfGroup();

		$acfGroup->layoutFields->addTab('product_info_tab', 'اطلاعات محصول');
		$acfGroup->basicFields->addNumber('product_price', 'قیمت (تومان)', ['placeholder' => '450000000', 'width' => '33']);
		$acfGroup->basicFields->addNumber('product_area', 'متراژ (متر مربع)', ['placeholder' => '60', 'width' => '33']);
		$acfGroup->basicFields->addText('product_dimensions', 'ابعاد', ['placeholder' => '۶ × ۱۰ متر', 'width' => '33']);
		$acfGroup->basicFields->addNumber('product_rooms', 'تعداد اتاق', ['placeholder' => '2', 'min' => 0, 'width' => '33']);
		$acfGroup->basicFields->addText('product_structure_type', 'نوع سازه', ['placeholder' => 'سازه فلزی سبک', 'width' => '33']);
		$acfGroup->basicFields->addText('product_build_time', 'زمان ساخت', ['placeholder' => '۳۰ تا ۴۵ روز کاری', 'width' => '33']);
		$acfGroup->basicFields->addText('product_status', 'وضعیت محصول', ['placeholder' => 'اماده تحویل', 'width' => '33']);
		$acfGroup->contentFields->addTextEditor('product_description', 'توضیحات', ['rows' => 12]);

		$acfGroup->layoutFields->addTab('product_gallery_tab', 'گالری');
		$acfGroup->contentFields->addGallery('product_gallery', 'تصویر', ['return_format' => 'url', 'width' => '25'], 20);
		$acfGroup->choiceFields->addBoolean('product_videos_first', 'نمایش ویدیوها در ابتدای گالری', ['message' => 'در صورت غیرفعال بودن، ویدیوها آخرین اسلایدها خواهند بود', 'default_value' => 0]);
		for ($i = 1; $i <= 4; $i++) {
			$source_key = 'acf_select_product_video_' . $i . '_source_';
			$acfGroup->choiceFields->addSelect("product_video_{$i}_source", "منبع ویدیو {$i}", ['choices' => ['aparat' => 'آپارات', 'wordpress' => 'رسانه وردپرس'], 'default_value' => 'aparat', 'width' => '25']);
			$acfGroup->contentFields->addTextEditor("product_video_{$i}_aparat", "کد embed آپارات {$i}", ['width' => '75', 'media_upload' => 0, 'conditional_logic' => [[['field' => $source_key, 'operator' => '==', 'value' => 'aparat']]]]);
			$acfGroup->contentFields->addFile("product_video_{$i}_file", "فایل ویدیو {$i}", ['return_format' => 'array', 'width' => '50', 'conditional_logic' => [[['field' => $source_key, 'operator' => '==', 'value' => 'wordpress']]]]);
			$acfGroup->contentFields->addImage("product_video_{$i}_cover", "کاور ویدیو {$i}", ['return_format' => 'url', 'width' => '50']);
		}

		$acfGroup->layoutFields->addTab('product_features_tab', 'امکانات قابل انتخاب');
		$acfGroup->basicFields->addTextarea('product_features_desc', 'توضیحات', ['rows' => 2, 'placeholder' => 'این مدل بر اساس نیاز شما قابل شخصی سازی است. امکانات موردنظر را انتخاب کنید', 'default_value' => 'این مدل بر اساس نیاز شما قابل شخصی سازی است. امکانات موردنظر را انتخاب کنید']);
		$feature_placeholders = [
			1 => ['title' => 'پارکینگ', 'desc' => 'پارکینگ سرپوشیده یا روباز', 'price' => '10000000'],
			2 => ['title' => 'پنجره پانوراما', 'desc' => 'پنجره های قدی با دید گسترده', 'price' => '10000000'],
			3 => ['title' => 'تراس', 'desc' => 'اضافه شدن تراس چوبی یا کامپوزیت', 'price' => '10000000'],
			4 => ['title' => 'گرمایش از کف', 'desc' => 'سیستم گرمایش از کف برقی', 'price' => '10000000'],
			5 => ['title' => 'نمای ترموود', 'desc' => 'استفاده از ترموود در نمای بیرونی', 'price' => '10000000'],
			6 => ['title' => 'سیستم هوشمندسازی', 'desc' => 'کنترل هوشمند لوازم روشنایی و تجهیزات', 'price' => '10000000'],
			7 => ['title' => 'انباری', 'desc' => 'افزودن فضای انباری مجزا', 'price' => '10000000'],
			8 => ['title' => 'کابینت MDF ارتقایی', 'desc' => 'کابینت اشپزخانه MDF طرح سفارشی', 'price' => '10000000'],
			9 => ['title' => 'دوربین مداربسته', 'desc' => 'نصب سیستم امنیتی و DVR', 'price' => '10000000'],
		];
		foreach ($feature_placeholders as $i => $item) {
			$acfGroup->basicFields->addText("product_feature_title_{$i}", "عنوان امکان {$i}", ['default_value' => $item['title'], 'placeholder' => $item['title'], 'width' => '33']);
			$acfGroup->basicFields->addTextarea("product_feature_desc_{$i}", "توضیح امکان {$i}", ['default_value' => $item['desc'], 'placeholder' => $item['desc'], 'rows' => 2, 'width' => '33']);
			$acfGroup->basicFields->addNumber("product_feature_price_{$i}", "هزینه امکان {$i}", ['default_value' => $item['price'], 'placeholder' => $item['price'], 'width' => '33', 'append' => 'تومان']);
		}

		$acfGroup->layoutFields->addTab('product_installment_tab', 'اقساط');
		$acfGroup->basicFields->addText('product_installment_title', 'عنوان بخش', ['default_value' => 'شرایط پیش پرداخت و اقساط', 'width' => '50']);
		$acfGroup->basicFields->addText('product_installment_subtitle', 'زیرعنوان', ['default_value' => 'یکی از دو حالت زیر را انتخاب کنید', 'width' => '50']);
		$acfGroup->contentFields->addImage('product_installment_image', 'عکس بخش اقساط', ['return_format' => 'url']);

		$acfGroup->layoutFields->addTab('product_related_tab', 'محصولات مرتبط');
		$acfGroup->relationshipFields->addPostObject('product_similar', 'انتخاب محصولات مرتبط (حداکثر 4 — در صورت کمتر، بقیه خودکار پر می‌شود)', ['post_type' => 'product', 'multiple' => 1, 'return_format' => 'id', 'width' => '100%']);
		$acfGroup->relationshipFields->addPostObject('product_suggested', 'شاید بپسندید (حداکثر 4 — در صورت کمتر، بقیه خودکار پر می‌شود)', ['post_type' => 'product', 'multiple' => 1, 'return_format' => 'id', 'width' => '100%']);

		$acfGroup->setLocation('post_type', '==', 'product');
		$acfGroup->register('Product');
	}

	private static function forProject()
	{
		$acfGroup = new AcfGroup();

		$acfGroup->layoutFields->addTab('project_info_tab', 'اطلاعات پروژه');
		$acfGroup->contentFields->addTextEditor('project_desc', 'توضیحات)', ['rows' => 3, 'placeholder' => 'طراحی و اجرای یک ویلای مدرن با متریال ترکیبی چوب و شیشه در دل طبیعت؛ با نورگیری عالی، مصرف انرژی بهینه و دوام بالا', 'width' => '50']);
		$acfGroup->basicFields->addText('project_type', 'نوع پروژه', ['placeholder' => 'ویلای پیش‌ساخته دوبلکس', 'width' => '50']);
		$acfGroup->basicFields->addText('project_location', 'محل اجرا', ['placeholder' => 'مازندران', 'width' => '50']);
		$acfGroup->basicFields->addText('project_area', 'متراژ', ['placeholder' => '۱۲۰ مترمربع', 'width' => '50']);
		$acfGroup->basicFields->addText('project_floors', 'تعداد طبقات', ['placeholder' => '۲ طبقه', 'width' => '33']);
		$acfGroup->basicFields->addText('project_duration', 'مدت زمان اجرا', ['placeholder' => '۴۵ روز کاری', 'width' => '33']);
		$acfGroup->basicFields->addText('project_year', 'سال اجرا', ['placeholder' => '۱۴۰۵', 'width' => '33']);

		$acfGroup->layoutFields->addTab('project_gallery_tab', 'گالری');
		$acfGroup->contentFields->addGallery('project_gallery', 'تصویر', ['return_format' => 'url', 'width' => '25'], 20);
		$acfGroup->choiceFields->addBoolean('project_videos_first', 'نمایش ویدیوها در ابتدای گالری', ['message' => 'در صورت غیرفعال بودن، ویدیوها آخرین اسلایدها خواهند بود', 'default_value' => 0]);
		for ($i = 1; $i <= 4; $i++) {
			$source_key = 'acf_select_project_video_' . $i . '_source_';
			$acfGroup->choiceFields->addSelect("project_video_{$i}_source", "منبع ویدیو {$i}", ['choices' => ['aparat' => 'آپارات', 'wordpress' => 'رسانه وردپرس'], 'default_value' => 'aparat', 'width' => '25']);
			$acfGroup->contentFields->addTextEditor("project_video_{$i}_aparat", "کد embed آپارات {$i}", ['width' => '75', 'media_upload' => 0, 'conditional_logic' => [[['field' => $source_key, 'operator' => '==', 'value' => 'aparat']]]]);
			$acfGroup->contentFields->addFile("project_video_{$i}_file", "فایل ویدیو {$i}", ['return_format' => 'array', 'width' => '50', 'conditional_logic' => [[['field' => $source_key, 'operator' => '==', 'value' => 'wordpress']]]]);
			$acfGroup->contentFields->addImage("project_video_{$i}_cover", "کاور ویدیو {$i}", ['return_format' => 'url', 'width' => '50']);
		}

		$acfGroup->layoutFields->addTab('project_content_tab', 'توضیحات');
		$acfGroup->contentFields->addTextEditor('project_description', 'توضیحات پروژه', ['rows' => 8]);
		$acfGroup->contentFields->addTextEditor('project_design_solution', 'راهکار طراحی', ['rows' => 8, 'placeholder' => 'هر مورد در یک خط', 'width' => '50']);
		$acfGroup->contentFields->addTextEditor('project_client_need', 'نیاز کارفرما', ['rows' => 8, 'placeholder' => 'هر مورد در یک خط', 'width' => '50']);

		$acfGroup->layoutFields->addTab('project_compare_tab', 'قبل و بعد');
		$acfGroup->contentFields->addImage('project_image_before', 'تصویر قبل', ['return_format' => 'url', 'width' => '50']);
		$acfGroup->contentFields->addImage('project_image_after', 'تصویر بعد', ['return_format' => 'url', 'width' => '50']);

		$acfGroup->layoutFields->addTab('project_review_tab', 'نظر کارفرما');
		$acfGroup->contentFields->addTextEditor('project_review_text', 'متن نظر', ['rows' => 5]);
		$acfGroup->basicFields->addNumber('project_review_rating', 'امتیاز', ['placeholder' => '5', 'min' => 0, 'max' => 5, 'step' => 0.1, 'width' => '25']);
		$acfGroup->basicFields->addText('project_review_name', 'نام', ['placeholder' => 'آقای میرزایی', 'width' => '25']);
		$acfGroup->basicFields->addText('project_review_role', 'سمت', ['default_value' => 'کارفرما', 'placeholder' => 'کارفرما', 'width' => '25']);
		$acfGroup->contentFields->addImage('project_review_image', 'تصویر', ['return_format' => 'url', 'width' => '25']);

		$acfGroup->layoutFields->addTab('project_related_tab', 'پروژه‌های مرتبط');
		$acfGroup->relationshipFields->addPostObject('project_similar', 'انتخاب پروژه‌های مشابه (حداکثر 4 — در صورت کمتر، بقیه خودکار پر می‌شود)', ['post_type' => 'project', 'multiple' => 1, 'return_format' => 'id', 'width' => '100%']);

		$acfGroup->setLocation('post_type', '==', 'project');
		$acfGroup->register('Project');
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
		$acfGroup->basicFields->addText('about_certificates_title', 'عنوان', ['default_value' => __('گواهینامه‌ها و', 'novavilla')]);
		$acfGroup->basicFields->addText('about_certificates_title_accent', 'ادامه عنوان رنگی', ['default_value' => __('استانداردهای', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addText('about_certificates_title_normal', 'ادامه عنوان عادی', ['default_value' => __(' ما', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addTextarea('about_certificates_desc', 'توضیحات', ['rows' => 3, 'default_value' => __('کیفیت، ایمنی و دوام محصولات ما بر پایه استانداردهای فنی و فرآیندهای کنترل‌شده است. در این بخش می‌توانید گواهینامه‌ها، مجوزها و تأییدیه‌های مجموعه را مشاهده کنید.', 'novavilla')]);
		$acfGroup->contentFields->addImage('about_certificates_image', 'تصویر بخش', ['return_format' => 'url']);
		$acfGroup->basicFields->addNumber('about_cert_stat_value_1', 'عدد آمار ۱', ['default_value' => 12, 'min' => 0, 'step' => 1, 'width' => '50']);
		$acfGroup->basicFields->addText('about_cert_stat_label_1', 'برچسب آمار ۱', ['default_value' => __('گواهینامه معتبر', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addNumber('about_cert_stat_value_2', 'عدد آمار ۲', ['default_value' => 10, 'min' => 0, 'step' => 1, 'width' => '50']);
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

	private static function forHome()
	{
		$acfGroup = new AcfGroup();

		$acfGroup->layoutFields->addTab('home_top_hero_tab', 'بنر بالای هیرو');
		$acfGroup->choiceFields->addBoolean('home_top_hero_default', 'فعال سازی نمایش پیش فرض', ['message' => 'در صورت فعال بودن، طرح پیش‌فرض نمایش داده می‌شود', 'default_value' => 1, 'width' => '50']);
		$acfGroup->contentFields->addImage('home_top_hero_image', 'تصویر و یا گیف بنر بالای هیرو', ['return_format' => 'url', 'width' => '50', 'conditional_logic' => [[['field' => 'acf_true_false_home_top_hero_default_', 'operator' => '!=', 'value' => '1']]]]);

		$acfGroup->layoutFields->addTab('home_hero_tab', 'هیرو و دسته‌بندی‌ها');
		$home_cat_defaults = [
			1 => ['title' => 'ویلای پیش ساخته', 'subtitle' => 'رویایی ترین ویلاها رو به سرعت بسازید'],
			2 => ['title' => 'کلبه چوبی', 'subtitle' => 'رویایی ترین ویلاها رو به سرعت بسازید'],
			3 => ['title' => 'کانتینر', 'subtitle' => 'کاربردی , نصب سریع , قیمت مناسب'],
			4 => ['title' => 'کانکس', 'subtitle' => 'کاربردی , نصب سریع , قیمت مناسب'],
		];
		foreach ($home_cat_defaults as $i => $item) {
			$acfGroup->contentFields->addImage("home_cat_image_{$i}", "تصویر {$i}", ['return_format' => 'url', 'width' => '25', 'required' => 1]);
			$acfGroup->basicFields->addText("home_cat_title_{$i}", "عنوان {$i}", ['default_value' => $item['title'], 'width' => '25']);
			$acfGroup->basicFields->addText("home_cat_subtitle_{$i}", "زیرعنوان {$i}", ['default_value' => $item['subtitle'], 'width' => '25']);
			$acfGroup->relationshipFields->addLink("home_cat_btn_{$i}", "دکمه {$i}", ['width' => '25', 'required' => 1]);
		}

		$acfGroup->layoutFields->addTab('home_cta_tab', 'بنر CTA');
		$acfGroup->basicFields->addText('home_cta_title', 'عنوان', ['default_value' => __('اقســــــــاط 12 ماهه', 'novavilla'), 'required' => 1, 'width' => '50']);
		$acfGroup->basicFields->addText('home_cta_subtitle', 'زیرعنوان', ['default_value' => __('فروش رویایی ترین ویلاهای پیش ساخته', 'novavilla'), 'width' => '50']);
		$acfGroup->relationshipFields->addLink('home_cta_button', 'دکمه', ['width' => '50']);
		$acfGroup->contentFields->addImage('home_cta_image', 'عکس', ['return_format' => 'url', 'required' => 1, 'width' => '50']);


		$acfGroup->layoutFields->addTab('home_3d_tab', 'سازه سه‌بعدی');
		$acfGroup->contentFields->addImage('home_3d_image', 'عکس (دارک)', ['return_format' => 'url', 'required' => 1, 'width' => '50']);
		$acfGroup->contentFields->addImage('home_3d_image_light', 'عکس (لایت)', ['return_format' => 'url', 'width' => '50']);
		$acfGroup->basicFields->addText('home_3d_title', 'عنوان (سفید)', ['default_value' => __('قبل از ساخت،', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addText('home_3d_title_colored', 'عنوان رنگی', ['default_value' => __('سازه‌تان را ببینید', 'novavilla'), 'width' => '50']);
		$acfGroup->contentFields->addTextEditor('home_3d_description', 'توضیحات', ['rows' => 5, 'default_value' => __('یکی از خدمات ویژه مجموعه، طراحی سه‌بعدی سازه هم‌زمان با جلسه حضوری فروش است. در این جلسه، کارشناسان ما نیازها، سلیقه، متراژ و نوع کاربری پروژه شما را بررسی کرده و طرح اولیه سازه را در همان لحظه آماده می‌کنند. مدل سه‌بعدی از طریق ویدئوپروژکتور روی پرده نمایش داده می‌شود تا بتوانید تغییرات طرح را واضح‌تر مشاهده کنید، درباره جزئیات نظر بدهید و نتیجه هر تصمیم را پیش از شروع تولید ببینید.', 'novavilla')]);
		$acfGroup->relationshipFields->addLink('home_3d_button', 'دکمه', ['width' => '50']);

		$acfGroup->layoutFields->addTab('home_about_tab', 'درباره ما');
		$acfGroup->basicFields->addText('home_about_title', 'عنوان', ['default_value' => __('چرا نواویلا', 'novavilla'), 'width' => '50', 'required' => 1]);
		$acfGroup->basicFields->addTextarea('home_about_description', 'توضیحات', ['rows' => 6, 'default_value' => __('سال‌ها تجربه در طراحی و اجرای ویلاهای پیش‌ساخته، امروز ما رو به عنوان مجموعه‌ای قابل اعتماد در این حوزه معرفی کرده. ما همواره تلاش کرده‌ایم با ارائه قیمت‌های منصفانه و رقابتی، بهترین ارزش را در برابر هزینه پرداختی برای مشتریان خود فراهم کنیم. در نواویلا، هر پروژه با دقت و توجه کامل به نیازها، سلیقه و شرایط مشتری ارزیابی می‌شود و سپس به‌صورت کاملاً دقیق و اصولی اجرا می‌گردد. باور ما این است که یک پروژه موفق، نتیجه درک درست از خواسته‌های مشتری و اجرای بی‌نقص آن است؛ به همین دلیل از مرحله مشاوره تا تحویل نهایی در کنار شما هستیم تا نتیجه‌ای مطابق انتظار و حتی فراتر از آن ارائه دهیم.', 'novavilla'), 'required' => 1]);
		$acfGroup->relationshipFields->addLink('home_about_button', 'دکمه', ['width' => '50']);
		$acfGroup->contentFields->addImage('home_about_image', 'عکس', ['return_format' => 'url', 'width' => '50', 'required' => 1]);

		$acfGroup->layoutFields->addTab('home_achievements_tab', 'دستاورد های ما');
		$home_achievement_defaults = [
			1 => __('استحکام بنا', 'novavilla'),
			2 => __('فروش اقساطی', 'novavilla'),
			3 => __('خوش نقشه', 'novavilla'),
			4 => __('نصب سریع', 'novavilla'),
			5 => __('مهندسین حرفه ای', 'novavilla'),
		];
		foreach ($home_achievement_defaults as $i => $title) {
			$acfGroup->basicFields->addText("home_achievement_title_{$i}", "عنوان {$i}", ['default_value' => $title]);
		}

		$acfGroup->layoutFields->addTab('product_archive_hero_tab', 'آرشیو محصولات');
		$acfGroup->contentFields->addImage('product_archive_hero_image_before', 'تصویر هیرو قبل از تغییر', ['return_format' => 'url', 'width' => '50']);
		$acfGroup->contentFields->addImage('product_archive_hero_image_after', 'تصویر هیرو بعد از تغییر', ['return_format' => 'url', 'width' => '50']);
		$acfGroup->basicFields->addText('product_archive_hero_title_one', 'عنوان خط اول', ['default_value' => __('ویلایــــــــــــــی مـــــــدرن', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addText('product_archive_hero_title_two', 'عنوان خط دوم', ['default_value' => __('سریع‌تر از آنچه تصور می‌کنید', 'novavilla'), 'width' => '50']);
		$acfGroup->contentFields->addTextEditor('product_archive_hero_description', 'توضیحات', ['rows' => 5, 'default_value' => __('در مجموعه نوا ویلا، ویلاهای پیش‌ساخته با طراحی مدرن و کیفیت بالا تولید می‌شوند. با استفاده از تکنولوژی‌های نوین ساخت، زمان تحویل پروژه به‌طور چشمگیری کاهش می‌یابد و شما می‌توانید ویلای مدرن خود را سریع‌تر از آنچه تصور می‌کنید داشته باشید.', 'novavilla')]);
		$acfGroup->relationshipFields->addLink('product_archive_hero_button', 'دکمه', ['width' => '50']);

		$acfGroup->layoutFields->addTab('product_installment_settings_tab', 'اقساط محصولات');
		$acfGroup->contentFields->addImage('product_installment_default_image', 'عکس پیش‌فرض بخش اقساط', ['return_format' => 'url']);
		$acfGroup->basicFields->addText('product_prepay_section_title', 'عنوان بخش پیش پرداخت', ['default_value' => 'پیش پرداخت']);
		$home_prepay = [
			1 => ['title' => 'پیش پرداخت 50 %', 'percent' => 50, 'desc' => 'مناسب پرداخت اولیه بیشتر و اقساط سبک‌تر'],
			2 => ['title' => 'پیش پرداخت 30%', 'percent' => 30, 'desc' => 'مناسب شروع آسان‌تر با پیش پرداخت کمتر'],
		];
		foreach ($home_prepay as $i => $item) {
			$acfGroup->basicFields->addText("product_prepay_title_{$i}", "عنوان پیش‌پرداخت {$i}", ['default_value' => $item['title'], 'width' => '40']);
			$acfGroup->basicFields->addNumber("product_prepay_percent_{$i}", "درصد {$i}", ['default_value' => $item['percent'], 'min' => 1, 'max' => 99, 'width' => '20']);
			$acfGroup->basicFields->addTextarea("product_prepay_desc_{$i}", "توضیح {$i}", ['default_value' => $item['desc'], 'rows' => 2, 'width' => '40']);
		}
		$acfGroup->basicFields->addText('product_period_section_title', 'عنوان بخش مدت بازپرداخت', ['default_value' => 'مدت بازپرداخت (تعداد اقساط)']);
		$home_periods = [1 => ['label' => '3 ماه', 'months' => 3], 2 => ['label' => '6 ماه', 'months' => 6], 3 => ['label' => '12 ماه', 'months' => 12]];
		foreach ($home_periods as $i => $item) {
			$acfGroup->basicFields->addText("product_period_label_{$i}", "برچسب مدت {$i}", ['default_value' => $item['label'], 'width' => '50']);
			$acfGroup->basicFields->addNumber("product_period_months_{$i}", "تعداد ماه {$i}", ['default_value' => $item['months'], 'min' => 1, 'width' => '50']);
		}
		$acfGroup->basicFields->addNumber('product_interest_rate', 'سود ماهانه (%)', ['default_value' => 3, 'min' => 0, 'step' => 0.1, 'width' => '50']);
		$acfGroup->basicFields->addText('product_calc_section_title', 'عنوان بخش محاسبه', ['default_value' => 'محاسبه اقساط', 'width' => '50']);
		$acfGroup->basicFields->addTextarea('product_installment_note', 'توضیح پایین محاسبه', ['rows' => 2, 'default_value' => 'مبلغ نهایی با توجه به مبلغ سفارش و تعداد اقساط محاسبه می‌شود.']);

		$acfGroup->layoutFields->addTab('project_archive_hero_tab', 'آرشیو پروژه‌ها');
		$acfGroup->basicFields->addText('project_archive_hero_title', 'عنوان', ['default_value' => __('پروژه‌هایی که', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addText('project_archive_hero_title_colored', 'عنوان رنگی', ['default_value' => __('از ایده به واقعیت رسیدند', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addTextarea('project_archive_hero_description', 'توضیحات', ['rows' => 4, 'default_value' => __('مجموعه‌ای از پروژه‌های طراحی، تولید و اجرای ویلای پیش‌ساخته، کانکس، کانتینر تجهیزشده و کلبه چوبی را مشاهده کنید.', 'novavilla')]);
		$acfGroup->contentFields->addImage('project_archive_hero_image', 'عکس', ['return_format' => 'url']);
		$project_archive_stat_defaults = [
			1 => ['title' => __('سال تجربه اجرایی', 'novavilla'), 'number' => 10, 'icon' => 'shield-chekmark'],
			2 => ['title' => __('گواهینامه معتبر', 'novavilla'), 'number' => 12, 'icon' => 'Certificate'],
			3 => ['title' => __('سال تجربه اجرایی', 'novavilla'), 'number' => 10, 'icon' => 'shield-chekmark'],
			4 => ['title' => __('گواهینامه معتبر', 'novavilla'), 'number' => 12, 'icon' => 'Certificate'],
		];
		foreach ($project_archive_stat_defaults as $i => $item) {
			$acfGroup->basicFields->addText("project_archive_stat_title_{$i}", "عنوان آمار {$i}", ['default_value' => $item['title'], 'width' => '40']);
			$acfGroup->basicFields->addNumber("project_archive_stat_number_{$i}", "عدد آمار {$i}", ['default_value' => $item['number'], 'min' => 0, 'step' => 1, 'width' => '30']);
			$acfGroup->basicFields->addText("project_archive_stat_icon_{$i}", "نام آیکون آمار {$i}", ['default_value' => $item['icon'], 'width' => '30']);
		}

		$acfGroup->setLocation('page_template', '==', 'templates/home.php');

		$acfGroup->register('Home');
	}

	private static function for3dStructure()
	{
		$acfGroup = new AcfGroup();

		$acfGroup->layoutFields->addTab('structure_hero_tab', 'هیرو');
		$acfGroup->basicFields->addText('structure_hero_title', 'عنوان', ['default_value' => __('قبل از ساخت،', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addText('structure_hero_subtitle', 'زیرعنوان (رنگی)', ['default_value' => __('سازه‌تان را ببینید', 'novavilla'), 'width' => '50']);
		$acfGroup->contentFields->addTextEditor('structure_hero_description', 'توضیحات', ['rows' => 5, 'default_value' => __('یکی از خدمات ویژه مجموعه، طراحی سه‌بعدی سازه هم‌زمان با جلسه حضوری فروش است. در این جلسه، کارشناسان ما نیازها، سلیقه، متراژ و نوع کاربری پروژه شما را بررسی کرده و طرح اولیه سازه را در همان لحظه آماده می‌کنند. مدل سه‌بعدی از طریق ویدئوپروژکتور روی پرده نمایش داده می‌شود تا بتوانید تغییرات طرح را واضح‌تر مشاهده کنید، درباره جزئیات نظر بدهید و نتیجه هر تصمیم را پیش از شروع تولید ببینید.', 'novavilla')]);
		$acfGroup->contentFields->addImage('structure_hero_image', 'تصویر هیرو (میتوانید عکس را اینجا و یا در تصویر شاخص انتخاب کنید)', ['return_format' => 'url', 'width' => '50']);
		$acfGroup->relationshipFields->addLink('structure_booking_btn', 'دکمه رزرو جلسه', ['width' => '50']);
		$acfGroup->relationshipFields->addLink('structure_consultation_btn', 'دکمه مشاوره', ['width' => '50']);

		$acfGroup->layoutFields->addTab('structure_session_tab', 'مراحل جلسه');
		$acfGroup->basicFields->addText('structure_session_title', 'عنوان بخش', ['default_value' => __('این جلسه چگونه برگزار می‌شود؟', 'novavilla')]);

		$session_defaults = [
			1 => ['number' => '۲', 'title' => __('بررسی نیازهای پروژه', 'novavilla'), 'desc' => __('در ابتدای جلسه درباره نوع سازه، کاربری، متراژ زمین، تعداد فضاها، بودجه و سبک طراحی موردنظر شما گفت‌وگو می‌کنیم.', 'novavilla')],
			2 => ['number' => '۳', 'title' => __('شکل‌گیری طرح اولیه', 'novavilla'), 'desc' => __('بر اساس اطلاعات اولیه، فرم کلی سازه، جانمایی فضاها و ابعاد تقریبی پروژه در نرم‌افزار طراحی ایجاد می‌شود.', 'novavilla')],
			3 => ['number' => '۴', 'title' => __('نمایش سه‌بعدی روی پرده', 'novavilla'), 'desc' => __('طرح سه‌بعدی روی پرده نمایش داده می‌شود تا بتوانید نمای بیرونی، فرم سازه و ارتباط فضاها را بررسی کنید.', 'novavilla')],
			4 => ['number' => '۵', 'title' => __('اعمال تغییرات در لحظه', 'novavilla'), 'desc' => __('تغییراتی مثل جابه‌جایی فضاها، نوع نما، فرم پنجره‌ها، رنگ‌ها و برخی امکانات، بر اساس نظر شما در همان جلسه روی طرح اعمال می‌شوند.', 'novavilla')],
			5 => ['number' => '۶', 'title' => __('جمع‌بندی و ادامه طراحی', 'novavilla'), 'desc' => __('پس از تأیید مسیر کلی پروژه، اطلاعات طرح برای بررسی فنی، تکمیل جزئیات و آماده‌سازی پیش‌فاکتور در اختیار تیم طراحی و تولید قرار می‌گیرد.', 'novavilla')],
		];

		foreach ($session_defaults as $i => $item) {
			$acfGroup->contentFields->addImage("structure_step_image_{$i}", "تصویر مرحله {$i}", ['return_format' => 'url', 'width' => '25']);
			$acfGroup->basicFields->addText("structure_step_number_{$i}", "شماره {$i}", ['default_value' => $item['number'], 'width' => '15']);
			$acfGroup->basicFields->addText("structure_step_title_{$i}", "عنوان {$i}", ['default_value' => $item['title'], 'width' => '30']);
			$acfGroup->basicFields->addTextarea("structure_step_desc_{$i}", "توضیح {$i}", ['default_value' => $item['desc'], 'rows' => 3, 'width' => '30']);
		}

		$acfGroup->layoutFields->addTab('structure_review_tab', 'موارد قابل بررسی');
		$acfGroup->basicFields->addText('structure_review_title', 'عنوان بخش', ['default_value' => __('چه مواردی در جلسه قابل بررسی هست؟', 'novavilla')]);

		$review_defaults = [
			1 => ['title' => __('فرم و نمای کلی سازه', 'novavilla'), 'desc' => __('بررسی سبک مدرن، کلاسیک، مینیمال یا چوبی و انتخاب فرم کلی سازه.', 'novavilla')],
			2 => ['title' => __('جانمایی فضاها', 'novavilla'), 'desc' => __('بررسی محل اتاق‌ها، آشپزخانه، نشیمن، سرویس‌ها، تراس و مسیرهای رفت‌وآمد.', 'novavilla')],
			3 => ['title' => __('در و پنجره‌ها', 'novavilla'), 'desc' => __('انتخاب موقعیت، ابعاد و فرم پنجره‌ها برای نورگیری و دید بهتر.', 'novavilla')],
			4 => ['title' => __('نمای خارجی', 'novavilla'), 'desc' => __('بررسی ترکیب متریال‌هایی مانند ترموود، فایبرسمنت، شیشه و پوشش‌های فلزی.', 'novavilla')],
			5 => ['title' => __('رنگ و متریال', 'novavilla'), 'desc' => __('مشاهده ترکیب رنگ‌ها و متریال‌های مختلف روی مدل سه‌بعدی سازه.', 'novavilla')],
			6 => ['title' => __('امکانات سفارشی', 'novavilla'), 'desc' => __('بررسی امکان اضافه‌کردن تراس، پارکینگ، انباری، پنجره پانوراما یا فضای اختصاصی.', 'novavilla')],
		];

		foreach ($review_defaults as $i => $item) {
			$acfGroup->contentFields->addImage("structure_review_image_{$i}", "تصویر {$i}", ['return_format' => 'url', 'width' => '25']);
			$acfGroup->basicFields->addText("structure_review_title_{$i}", "عنوان {$i}", ['default_value' => $item['title'], 'width' => '35']);
			$acfGroup->basicFields->addTextarea("structure_review_desc_{$i}", "توضیح {$i}", ['default_value' => $item['desc'], 'rows' => 2, 'width' => '40']);
		}

		$acfGroup->layoutFields->addTab('structure_cta_tab', 'بنر پایانی');
		$acfGroup->basicFields->addText('structure_cta_title', 'عنوان', ['default_value' => __('سازه‌ای که در ذهن دارید با هم به تصویر بکشیم', 'novavilla')]);
		$acfGroup->basicFields->addTextarea('structure_cta_description', 'توضیحات', ['rows' => 4, 'default_value' => __('جلسات طراحی و مشاوره در فضای اختصاصی مجموعه برگزار می‌شوند. نمایش طرح روی پرده ویدئوپروژکتور این امکان را فراهم می‌کند که تمام افراد حاضر در جلسه، جزئیات پروژه را هم‌زمان مشاهده کرده و درباره تغییرات تصمیم‌گیری کنند.', 'novavilla')]);
		$acfGroup->contentFields->addImage('structure_cta_image', 'تصویر بنر (دارک)', ['return_format' => 'url', 'width' => '50']);
		$acfGroup->contentFields->addImage('structure_cta_image_light', 'تصویر بنر (لایت)', ['return_format' => 'url', 'width' => '50']);
		$acfGroup->basicFields->addText('structure_cta_button_text', 'متن دکمه ارسال', ['default_value' => __('درخواست جلسه حضوری', 'novavilla'), 'width' => '50']);
		$acfGroup->basicFields->addText('structure_cta_input_placeholder', 'متن placeholder ورودی', ['default_value' => __('ایمیل یا شماره همراه خود را وارد کنید', 'novavilla'), 'width' => '50']);

		$acfGroup->setLocation('page_template', '==', 'templates/3d-structure.php');

		$acfGroup->register('3D-Structure');
	}

	private static function forLanding()
	{
		$acfGroup = new AcfGroup();

		$acfGroup->layoutFields->addTab('landing_content_tab', 'بخش‌های محتوا');
		$landing_defaults = [
			1 => [
				'title' => __('انواع کانکس‌های سفارشی برای انواع کسب و کارها', 'novavilla'),
				'desc' => __('از فضای فروش و کافه تا دفتر کار، اقامتگاه و کاربری‌های تخصصی؛ کانکس‌های ما متناسب با نوع استفاده، محیط پروژه و نیاز شما طراحی و اجرا می‌شوند.', 'novavilla'),
			],
			2 => [
				'title' => __('انواع کانکس‌های سفارشی برای انواع کسب و کارها', 'novavilla'),
				'desc' => __('از فضای فروش و کافه تا دفتر کار، اقامتگاه و کاربری‌های تخصصی؛ کانکس‌های ما متناسب با نوع استفاده، محیط پروژه و نیاز شما طراحی و اجرا می‌شوند.', 'novavilla'),
			],
			3 => [
				'title' => __('انواع کانکس‌های سفارشی برای انواع کسب و کارها', 'novavilla'),
				'desc' => __('از فضای فروش و کافه تا دفتر کار، اقامتگاه و کاربری‌های تخصصی؛ کانکس‌های ما متناسب با نوع استفاده، محیط پروژه و نیاز شما طراحی و اجرا می‌شوند.', 'novavilla'),
			],
		];
		foreach ($landing_defaults as $i => $item) {
			$acfGroup->contentFields->addImage("landing_block_image_{$i}", "تصویر {$i}", ['return_format' => 'url', 'width' => '25']);
			$acfGroup->basicFields->addText("landing_block_title_{$i}", "عنوان {$i}", ['default_value' => $item['title'], 'width' => '35']);
			$acfGroup->contentFields->addTextEditor("landing_block_desc_{$i}", "توضیح {$i}", ['default_value' => $item['desc'], 'rows' => 4, 'width' => '40']);
		}
		for ($i = 4; $i <= 6; $i++) {
			$acfGroup->contentFields->addImage("landing_block_image_{$i}", "تصویر {$i}", ['return_format' => 'url', 'width' => '25']);
			$acfGroup->basicFields->addText("landing_block_title_{$i}", "عنوان {$i}", ['width' => '35']);
			$acfGroup->contentFields->addTextEditor("landing_block_desc_{$i}", "توضیح {$i}", ['rows' => 4, 'width' => '40']);
		}

		$acfGroup->layoutFields->addTab('landing_projects_tab', 'پروژه‌های اجرا شده');
		$acfGroup->choiceFields->addBoolean('landing_projects_enabled', 'نمایش بخش پروژه‌های اجرا شده', ['message' => 'در صورت فعال بودن، بخش پروژه‌ها در صفحه نمایش داده می‌شود', 'default_value' => 0]);
		$acfGroup->basicFields->addText('landing_projects_title', 'عنوان بخش', ['default_value' => __('پروژه های اجرا شده', 'novavilla')]);
		$acfGroup->relationshipFields->addPostObject('landing_projects', 'انتخاب پروژه‌ها (حداکثر ۴ — در صورت خالی، آخرین پروژه‌ها)', ['post_type' => 'project', 'multiple' => 1, 'return_format' => 'id']);

		$acfGroup->layoutFields->addTab('landing_products_tab', 'محصولات');
		$acfGroup->choiceFields->addBoolean('landing_products_enabled', 'نمایش بخش محصولات', ['message' => 'در صورت فعال بودن، بخش محصولات در صفحه نمایش داده می‌شود', 'default_value' => 0]);
		$acfGroup->basicFields->addText('landing_products_title', 'عنوان بخش', ['default_value' => __('محصولات', 'novavilla')]);
		$acfGroup->relationshipFields->addPostObject('landing_products', 'انتخاب محصولات (حداکثر ۴ — در صورت خالی، آخرین محصولات)', ['post_type' => 'product', 'multiple' => 1, 'return_format' => 'id']);

		$acfGroup->layoutFields->addTab('landing_faq_tab', 'سوالات متداول');
		$acfGroup->relationshipFields->addTaxonomy('landing_faq_place', 'مکان نمایش سوالات متداول', ['taxonomy' => 'faq_place', 'field_type' => 'select', 'allow_null' => 1, 'return_format' => 'object', 'create_terms' => 1]);

		$acfGroup->setLocation('page_template', '==', 'templates/landing.php');
		$acfGroup->register('Landing');
	}
}
