<?php /* Template Name: About Us */ ?>
<?php

use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

$about_title = get_field('about_title');
$about_subtitle = get_field('about_subtitle');
$about_description = get_field('about_description');
$certificates_btn = get_field('certificates_btn');
$products_btn = get_field('products_btn');
$about_story_title = get_field('about_story_title');
$about_story_description = get_field('about_story_description');
$about_video_file = get_field('about_video_file');
$about_video_file_url = is_array($about_video_file) ? ($about_video_file['url'] ?? '') : $about_video_file;
$about_video_mime = is_array($about_video_file) ? ($about_video_file['mime_type'] ?? '') : '';
$about_video_cover = get_field('about_video_cover');
$about_video_cover_url = is_array($about_video_cover) ? ($about_video_cover['url'] ?? '') : $about_video_cover;
if (empty($about_video_cover_url) && has_post_thumbnail()) {
    $about_video_cover_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
}

$activities_title = get_field('activities_title');
$activities = get_posts([
    'post_type' => 'activity',
    'posts_per_page' => 4,
    'orderby' => 'menu_order date',
    'order' => 'ASC',
    'fields' => 'ids',
]);

$about_certificates_title = get_field('about_certificates_title');
$about_certificates_title_accent = get_field('about_certificates_title_accent');
$about_certificates_title_normal = get_field('about_certificates_title_normal');
$about_certificates_desc = get_field('about_certificates_desc');
$about_certificates_image = get_field('about_certificates_image');
$about_certificates_image_url = is_array($about_certificates_image) ? ($about_certificates_image['url'] ?? '') : $about_certificates_image;
$about_cert_stat_label_1 = get_field('about_cert_stat_label_1');
$about_cert_stat_label_2 = get_field('about_cert_stat_label_2');
$about_cert_stats = [];
foreach ([
	['number' => get_field('about_cert_stat_value_1'), 'title' => $about_cert_stat_label_1, 'icon' => 'Certificate'],
	['number' => get_field('about_cert_stat_value_2'), 'title' => $about_cert_stat_label_2, 'icon' => 'Shield,-Protected,-Checkmark'],
] as $about_cert_stat_item) {
	$about_cert_stat_raw = $about_cert_stat_item['number'];
	$about_cert_stat_number = is_numeric($about_cert_stat_raw) ? (int) $about_cert_stat_raw : (preg_match('/\d+/', (string) $about_cert_stat_raw, $about_cert_stat_match) ? (int) $about_cert_stat_match[0] : null);
	if ($about_cert_stat_number === null && empty($about_cert_stat_item['title'])) continue;
	$about_cert_stats[] = ['number' => $about_cert_stat_number, 'title' => $about_cert_stat_item['title'], 'icon' => $about_cert_stat_item['icon']];
}
$about_certificates = get_posts([
    'post_type' => 'certificate',
    'posts_per_page' => -1,
    'orderby' => 'menu_order date',
    'order' => 'ASC',
]);

$personnels = get_posts([
    'post_type' => 'personnels',
    'posts_per_page' => 4,
    'orderby' => 'menu_order date',
    'order' => 'ASC',
    'fields' => 'ids',
]);

$why_title = get_field('why_title');

$why_items = [];
for ($i = 1; $i <= 6; $i++) {
    $title = get_field("why_item_title_{$i}");
    $desc = get_field("why_item_desc_{$i}");

    if (empty($title) && empty($desc)) {
        continue;
    }

    $why_items[] = [
        'icon' => get_field("why_icon_{$i}"),
        'title' => $title,
        'desc' => $desc,
    ];
}

get_header();
?>

<?php Templates::getPart('breadcrumb'); ?>

<main class="about-us-page container space-y-11 md:space-y-16">

    <section class="flex flex-col lg:flex-row lg:items-center gap-3">

        <div class="w-full lg:w-1/2">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('full', ['class' => 'w-full h-auto object-cover rounded-3xl', 'alt' => esc_attr(get_the_title())]); ?>
            <?php else : ?>
                <div class="w-full h-[285px] md:h-[432px] bg-cynBgItem/40 rounded-3xl"></div>
            <?php endif; ?>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center gap-3">
            <div class="flex flex-col gap-2">
                <h1 class="text-2xl md:text-4xl font-bold text-cynTextPrimary">
                    <?php echo $about_title ? esc_html($about_title) : __('درباره‌ی ما', 'novavilla'); ?>
                </h1>

                <p class="text-xs font-medium md:text-base md:font-normal text-cynBorderHover mt-1">
                    <?php echo $about_subtitle ? esc_html($about_subtitle) : __('کیفیت، تعهد و تخصص در ساخت سازه های پیش ساخته شده', 'novavilla'); ?>
                </p>

                <p class="text-xs md:text-base font-light text-cynTextPrimary leading-5 md:leading-7">
                    <?php echo $about_description ? esc_html($about_description) : __('ما مجموعه‌ای تخصصی در زمینه طراحی، تولید و اجرای انواع ویلای پیش‌ساخته، کانکس، کانتینرهای تجهیزشده و کلبه‌های چوبی هستیم. هدف ما ساخت سازه‌هایی است که علاوه بر سرعت اجرا، از نظر کیفیت، ایمنی، زیبایی و کارایی نیز پاسخ‌گوی نیاز واقعی مشتریان باشند.', 'novavilla'); ?>
                </p>
            </div>

            <div class="flex flex-wrap items-stretch gap-2">

                <?php if (!empty($products_btn['url'])) : ?>
                    <a href="<?php echo esc_url($products_btn['url']); ?>" <?php echo !empty($products_btn['target']) ? 'target="' . esc_attr($products_btn['target']) . '"' : ''; ?> class="primary-button">
                        <span class="whitespace-nowrap">
                            <?php echo esc_html($products_btn['title'] ?: __('محصولات ما', 'novavilla')); ?>
                        </span>
                    </a>
                <?php endif; ?>

                <?php if (!empty($certificates_btn['url'])) : ?>
                    <a href="<?php echo esc_url($certificates_btn['url']); ?>" <?php echo !empty($certificates_btn['target']) ? 'target="' . esc_attr($certificates_btn['target']) . '"' : ''; ?> class="primary-button btn-have-icon">
                        <span class="flex items-center gap-1 whitespace-nowrap text-xs md:text-sm font-semibold">
                            <?php echo esc_html($certificates_btn['title'] ?: __('گواهینامه ها', 'novavilla')); ?>
                            <i class="size-5 flex items-center justify-center [&_svg]:stroke-[2]">
                                <?php Icon::print('Arrow-27'); ?>
                            </i>
                        </span>
                    </a>
                <?php endif; ?>

            </div>
        </div>

    </section>

    <section class="flex flex-col gap-3 md:gap-6">
        <?php if ($about_story_title && $about_story_description) : ?>
            <div class="flex flex-col gap-2">
                <?php if ($about_story_title) : ?>
                    <h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary">
                        <?php echo esc_html($about_story_title); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($about_story_description) : ?>
                    <p class="text-xs md:text-base font-light text-cynTextPrimary leading-5 md:leading-7">
                        <?php echo esc_html($about_story_description); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($about_video_file_url) && !empty($about_video_cover_url)) : ?>
            <div class="video-player relative w-full h-[280px] md:h-[576px] rounded-3xl overflow-hidden bg-cynBgItem">
                <video class="video video-plyr h-full w-full object-cover" playsinline preload="metadata" data-poster="<?php echo esc_url($about_video_cover_url); ?>">
                    <source src="<?php echo esc_url($about_video_file_url); ?>" <?php echo $about_video_mime ? 'type="' . esc_attr($about_video_mime) . '"' : ''; ?>>
                </video>
                <div class="video-cover absolute inset-0 z-10 cursor-pointer opacity-100 pointer-events-auto transition-opacity duration-300 bg-cover bg-center bg-no-repeat" style="background-image: url(<?php echo esc_url($about_video_cover_url); ?>);">
                    <div class="flex items-center justify-center w-full h-full bg-black/35">
                        <i class="size-11 md:size-16 flex items-center justify-center rounded-full overflow-hidden text-cynWhite backdrop-blur-xl [&_svg]:size-full"><?php echo file_get_contents(THEME_DIR . '/assets/icon/play.svg'); ?></i>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </section>

    <?php if (!empty($activities)) : ?>
        <section class="flex flex-col gap-3">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12">
                    <?php echo $activities_title ? esc_html($activities_title) : __('حوزه‌های فعالیت ما', 'novavilla'); ?>
                </h2>

                <div class="flex items-center gap-2 lg:hidden">
                    <button type="button" id="activitiesPrev" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('قبلی', 'novavilla'); ?>">
                        <i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
                            <?php Icon::print('Arrow-19'); ?>
                        </i>
                    </button>
                    <button type="button" id="activitiesNext" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('بعدی', 'novavilla'); ?>">
                        <i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
                            <?php Icon::print('Arrow-27'); ?>
                        </i>
                    </button>
                </div>
            </div>

            <div class="hidden lg:grid lg:grid-cols-4 gap-3">
                <?php foreach ($activities as $activity_id) : ?>
                    <?php Templates::getCard('activity', ['post-id' => $activity_id]); ?>
                <?php endforeach; ?>
            </div>

            <div class="lg:hidden">
                <swiper-container class="w-full" slides-per-view="auto" space-between="12" loop="true" pagination="false" navigation="true" navigation-next-el="#activitiesNext" navigation-prev-el="#activitiesPrev" breakpoints='{"768": {"slidesPerView": 2}}'>
                    <?php foreach ($activities as $activity_id) : ?>
                        <swiper-slide>
                            <?php Templates::getCard('activity', ['post-id' => $activity_id]); ?>
                        </swiper-slide>
                    <?php endforeach; ?>
                </swiper-container>
            </div>
        </section>
    <?php endif; ?>

    <section class="flex flex-col gap-3">
        <h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12">
            <?php echo $why_title ? esc_html($why_title) : __('چرا مشتریان ما را انتخاب می‌کنند؟', 'novavilla'); ?>
        </h2>

        <div class="grid grid-cols-2 lg:grid-cols-6 gap-3">
            <?php foreach ($why_items as $item) : ?>
                <?php Templates::getCard('feature', $item); ?>
            <?php endforeach; ?>
        </div>
    </section>

    <?php if (!empty($about_certificates_title) || !empty($about_certificates_title_accent) || !empty($about_certificates_title_normal) || !empty($about_certificates_image_url) || !empty($about_certificates)) : ?>
        <section class="flex flex-col gap-3 lg:gap-3">
            <div class="flex flex-col lg:flex-row lg:items-center gap-3 lg:gap-3">
                <?php if (!empty($about_certificates_image_url)) : ?>
                    <div class="w-full lg:w-[55%] shrink-0">
                        <img src="<?php echo esc_url($about_certificates_image_url); ?>" alt="<?php echo esc_attr($about_certificates_title ?: __('گواهینامه‌ها', 'novavilla')); ?>" class="relative z-10 w-full object-cover" loading="lazy" decoding="async" />
                    </div>
                <?php endif; ?>

                <div class="flex flex-col gap-3 w-full lg:w-[45%]">
                    <?php if (!empty($about_certificates_title) || !empty($about_certificates_title_accent) || !empty($about_certificates_title_normal) || !empty($about_certificates_desc)) : ?>
                        <div class="flex flex-col gap-2 lg:gap-3">
                            <?php if (!empty($about_certificates_title) || !empty($about_certificates_title_accent) || !empty($about_certificates_title_normal)) : ?>
                                <h2 class="text-xl font-medium lg:text-4xl lg:font-bold leading-8 lg:leading-16">
                                    <?php if (!empty($about_certificates_title)) : ?>
                                        <span class="block text-cynTextPrimary">
                                            <?php echo esc_html($about_certificates_title); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($about_certificates_title_accent) || !empty($about_certificates_title_normal)) : ?>
                                        <span class="block">
                                            <?php if (!empty($about_certificates_title_accent)) : ?>
                                                <span class="text-cynBorderHover">
                                                    <?php echo esc_html($about_certificates_title_accent); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($about_certificates_title_normal)) : ?>
                                                <span class="text-cynTextPrimary">
                                                    <?php echo esc_html($about_certificates_title_normal); ?>
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                    <?php endif; ?>
                                </h2>
                            <?php endif; ?>

                            <?php if (!empty($about_certificates_desc)) : ?>
                                <p class="text-xs lg:text-base font-light text-cynTextPrimary leading-4 lg:leading-7">
                                    <?php echo esc_html($about_certificates_desc); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($about_cert_stats)) : ?>
                        <div class="grid grid-cols-2 gap-2 lg:gap-3">
                            <?php foreach ($about_cert_stats as $about_cert_stat) : ?>
                                <div class="flex items-center justify-center gap-3 lg:gap-5 rounded-3xl border border-cynBorderHover/40 hover:border-cynBorderHover transition-all duration-300 bg-cynWhite/10 backdrop-blur-xl sm:px-8 sm:py-6 px-4 py-4">
                                    <div class="flex flex-col gap-1">
                                        <?php if ($about_cert_stat['number'] !== null) : ?>
                                            <span class="text-lg md:text-4xl font-bold text-cynBorderHover leading-5 md:leading-10 text-end" dir="ltr" data-stat-count="<?php echo esc_attr($about_cert_stat['number']); ?>">+0</span>
                                        <?php endif; ?>
                                        <?php if (!empty($about_cert_stat['title'])) : ?>
                                            <span class="text-xs md:text-xl font-normal text-cynTextPrimary leading-4 md:leading-6">
                                                <?php echo esc_html($about_cert_stat['title']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($about_cert_stat['icon'])) : ?>
                                        <i class="size-8 sm:size-12 shrink-0 flex items-center justify-center text-cynBorderHover [&_svg]:size-full [&_svg]:stroke-[1.5]">
                                            <?php Icon::print($about_cert_stat['icon']); ?>
                                        </i>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($about_certificates)) : ?>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                    <?php foreach ($about_certificates as $certificate_id) : ?>
                        <?php Templates::getCard('certificate', ['post-id' => $certificate_id]); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php if (!empty($personnels)) : ?>
        <section class="flex flex-col gap-3">
            <h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12"><?php esc_html_e('تیم ما', 'novavilla'); ?></h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <?php foreach ($personnels as $personnel_id) : ?>
                    <?php Templates::getCard('personnel', ['post-id' => $personnel_id]); ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php Templates::getPart('faq', ['faq_place' => 'about-us']); ?>

</main>

<?php get_footer(); ?>