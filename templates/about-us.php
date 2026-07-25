<?php /* Template Name: About Us */ ?>
<?php

use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

$about_title = get_field('about_title');
$about_subtitle = get_field('about_subtitle');
$about_description = get_field('about_description');
$certificates_btn = get_field('certificates_btn');
$products_btn = get_field('products_btn');

$why_title = get_field('why_title');
$why_defaults = [
    [
        'title' => __('طراحی قابل سفارشی سازی', 'novavilla'),
        'desc' => __('ابعاد، پلان، نما، متریال و امکانات داخلی هر سازه بر اساس نیاز و بودجه مشتری قابل تغییر است.', 'novavilla'),
    ],
    [
        'title' => __('تولید با کنترل کیفیت', 'novavilla'),
        'desc' => __('متریال، اتصالات، تأسیسات و جزئیات اجرایی در مراحل مختلف تولید بررسی و کنترل می‌شوند.', 'novavilla'),
    ],
    [
        'title' => __('تحویل شفاف و برنامه‌ریزی‌شده', 'novavilla'),
        'desc' => __('زمان تولید، هزینه‌ها، امکانات و شرایط حمل پیش از شروع پروژه به‌صورت شفاف مشخص می‌شوند.', 'novavilla'),
    ],
    [
        'title' => __('پشتیبانی از طراحی تا نصب', 'novavilla'),
        'desc' => __('تیم مجموعه از انتخاب محصول و طراحی اولیه تا حمل، نصب و تحویل نهایی در کنار مشتری خواهد بود.', 'novavilla'),
    ],
    [
        'title' => __('مناسب برای اقلیم‌های مختلف', 'novavilla'),
        'desc' => __('نوع سازه و عایق‌بندی بر اساس شرایط آب‌وهوایی محل اجرای پروژه انتخاب می‌شود.', 'novavilla'),
    ],
    [
        'title' => __('امکان حمل و نقل و جا به جایی', 'novavilla'),
        'desc' => __('بخش زیادی از محصولات با توجه به طراحی سازه، قابلیت حمل، نصب مجدد یا توسعه در آینده را دارند.', 'novavilla'),
    ],
];

$why_items = [];
foreach ($why_defaults as $index => $default) {
    $i = $index + 1;
    $icon = get_field("why_icon_{$i}");
    $title = get_field("why_item_title_{$i}");
    $desc = get_field("why_item_desc_{$i}");

    $why_items[] = [
        'icon' => $icon,
        'title' => $title ?: $default['title'],
        'desc' => $desc ?: $default['desc'],
    ];
}

get_header();
?>

<?php Templates::getPart('breadcrumb'); ?>

<main class="container pb-10 md:pb-14 flex flex-col gap-8 lg:gap-12">

    <section class="flex flex-col lg:flex-row lg:items-center gap-3">

        <div class="w-full lg:w-1/2">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('full', [
                    'class' => 'w-full h-auto object-cover rounded-[20px]',
                    'alt' => esc_attr(get_the_title()),
                ]); ?>
            <?php endif; ?>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col gap-3 justify-center">

            <div class="flex flex-col gap-2 lg:gap-3">
                <h1 class="text-2xl lg:text-5xl font-bold text-cynTextPrimary">
                    <?php echo $about_title ? esc_html($about_title) : __('درباره‌ی ما', 'novavilla'); ?>
                </h1>

                <div class="flex flex-col gap-1 lg:gap-2">
                    <p class="text-xs lg:text-xl font-semibold text-cynBorderHover">
                        <?php echo $about_subtitle
                            ? esc_html($about_subtitle)
                            : __('کیفیت، تعهد و تخصص در ساخت سازه های پیش ساخته شده', 'novavilla'); ?>
                    </p>

                    <p class="text-xs lg:text-base font-medium text-cynTextPrimary leading-5 lg:leading-7">
                        <?php echo $about_description
                            ? esc_html($about_description)
                            : __('ما مجموعه‌ای تخصصی در زمینه طراحی، تولید و اجرای انواع ویلای پیش‌ساخته، کانکس، کانتینرهای تجهیزشده و کلبه‌های چوبی هستیم. هدف ما ساخت سازه‌هایی است که علاوه بر سرعت اجرا، از نظر کیفیت، ایمنی، زیبایی و کارایی نیز پاسخ‌گوی نیاز واقعی مشتریان باشند.', 'novavilla'); ?>
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <?php if (!empty($certificates_btn['url'])) : ?>
                    <a href="<?php echo esc_url($certificates_btn['url']); ?>"
                        <?php echo !empty($certificates_btn['target']) ? 'target="' . esc_attr($certificates_btn['target']) . '"' : ''; ?>
                        class="primary-button !py-2.5 !ps-2.5 !pe-3.5 group">
                        <span class="flex items-center gap-1 whitespace-nowrap">
                            <i class="size-5 flex items-center justify-center [&_svg]:stroke-[1.5]">
                                <?php Icon::print('Certificate'); ?>
                            </i>
                            <?php echo esc_html($certificates_btn['title'] ?: __('گواهینامه ها', 'novavilla')); ?>
                        </span>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="primary-button !py-2.5 !ps-2.5 !pe-3.5 group">
                        <span class="flex items-center gap-1 whitespace-nowrap">
                            <i class="size-5 flex items-center justify-center [&_svg]:stroke-[1.5]">
                                <?php Icon::print('Certificate'); ?>
                            </i>
                            <?php _e('گواهینامه ها', 'novavilla'); ?>
                        </span>
                    </a>
                <?php endif; ?>

                <?php if (!empty($products_btn['url'])) : ?>
                    <a href="<?php echo esc_url($products_btn['url']); ?>"
                        <?php echo !empty($products_btn['target']) ? 'target="' . esc_attr($products_btn['target']) . '"' : ''; ?>
                        class="primary-button !py-2.5 !px-5">
                        <span class="whitespace-nowrap">
                            <?php echo esc_html($products_btn['title'] ?: __('محصولات ما', 'novavilla')); ?>
                        </span>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="primary-button !py-2.5 !px-5">
                        <span class="whitespace-nowrap">
                            <?php _e('محصولات ما', 'novavilla'); ?>
                        </span>
                    </a>
                <?php endif; ?>
            </div>

        </div>

    </section>

    <section class="flex flex-col gap-3">
        <h2 class="text-xl lg:text-5xl font-bold text-cynTextPrimary">
            <?php echo $why_title
                ? esc_html($why_title)
                : __('چرا مشتریان ما را انتخاب می‌کنند؟', 'novavilla'); ?>
        </h2>

        <div class="grid grid-cols-2 lg:grid-cols-6 gap-3">
            <?php foreach ($why_items as $item) : ?>
                <article class="flex flex-col rounded-[20px] border border-cynBorder bg-cynBgItem backdrop-blur-xl overflow-hidden min-h-[218px]">
                    <div class="relative flex items-center justify-center h-[110px] mt-4 mx-[13px]">
                        <span class="pointer-events-none absolute size-[78px] rounded-full bg-[#fbb963] blur-[50px] opacity-80" aria-hidden="true"></span>
                        <?php if (!empty($item['icon']['url'])) : ?>
                            <img
                                src="<?php echo esc_url($item['icon']['url']); ?>"
                                alt="<?php echo esc_attr($item['icon']['alt'] ?: $item['title']); ?>"
                                class="relative z-10 w-[100px] h-auto object-contain drop-shadow-[0_6px_12px_rgba(0,0,0,0.35)]"
                                loading="lazy"
                                decoding="async" />
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-col gap-1 px-4 pb-4 pt-0">
                        <h3 class="text-base font-semibold text-cynTextPrimary text-center leading-6">
                            <?php echo esc_html($item['title']); ?>
                        </h3>
                        <p class="text-xs font-normal text-cynTextPrimary/90 text-center leading-4">
                            <?php echo esc_html($item['desc']); ?>
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>