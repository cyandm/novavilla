<?php

use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;

$home_id = get_the_ID();
$home_cta_title = get_field('home_cta_title', $home_id);
$home_cta_subtitle = get_field('home_cta_subtitle', $home_id);
$home_cta_image = get_field('home_cta_image', $home_id);
$home_cta_button = get_field('home_cta_button', $home_id);
$phone = (string) get_option('phone_number');
$home_cta_button_url = is_array($home_cta_button) ? ($home_cta_button['url'] ?? '') : '';
$home_cta_button_title = is_array($home_cta_button) ? ($home_cta_button['title'] ?? '') : '';
$home_cta_button_target = !empty($home_cta_button['target']) ? 'target="' . esc_attr($home_cta_button['target']) . '"' : '';
$home_cta_button_title = !empty($home_cta_button_title) ? $home_cta_button_title : __('تماس بگیرید', 'novavilla');
$home_cta_button_url = !empty($home_cta_button_url) ? $home_cta_button_url : (!empty($phone) ? 'tel:' . $phone : '');
$home_cta_image_url = is_array($home_cta_image) ? ($home_cta_image['url'] ?? '') : $home_cta_image;

if (empty($home_cta_title) || empty($home_cta_image_url)) return;
if (empty($home_cta_button_url) && empty($home_cta_button_title)) return;
?>

<section class="container my-11 md:my-16">
    <div class="flex flex-col-reverse lg:flex-row lg:items-center lg:justify-between gap-4 lg:gap-10 rounded-3xl bg-[linear-gradient(90deg,#FF7174_0%,#FFD966_100%)] dark:bg-[linear-gradient(90deg,rgba(53,53,53,0.29)_0%,rgba(255,232,81,0.29)_62.71%,rgba(255,80,11,0.29)_100%)] backdrop-blur-sm px-6 py-6 lg:px-6 lg:py-0 overflow-hidden">
        <div class="w-full lg:w-1/2 flex flex-col gap-6 lg:py-15">
            <div class="flex flex-col">
                <h2 class="text-4xl lg:text-7xl font-bold text-cynTextPrimary leading-14 lg:leading-tight">
                    <?php echo esc_html($home_cta_title); ?>
                </h2>

                <?php if (!empty($home_cta_subtitle)) : ?>
                    <p class="text-base lg:text-2xl font-normal text-cynTextPrimary leading-5 lg:leading-tight">
                        <?php echo esc_html($home_cta_subtitle); ?>
                    </p>
                <?php endif; ?>
            </div>

            <a href="<?php echo esc_url($home_cta_button_url); ?>" <?php echo $home_cta_button_target; ?> class="flex dark:!hidden rounded-xl w-fit !py-2.5 !ps-3 !pe-4 items-center gap-0.5 whitespace-nowrap bg-cynWhite">
                <i class="size-5 flex items-center justify-center [&_svg]:stroke-[1.5] [&_svg_g_path]:fill-cynBlack">
                    <?php Icon::print('Phone,-Call-11'); ?>
                </i>
                <span class="flex items-center gap-1 whitespace-nowrap text-sm font-semibold text-cynBlack">
                    <?php echo esc_html($home_cta_button_title); ?>
                </span>
            </a>
            <a href="<?php echo esc_url($home_cta_button_url); ?>" <?php echo $home_cta_button_target; ?> class="!hidden dark:!inline-flex primary-button rounded-xl w-fit !py-2.5 !ps-3 !pe-4 items-center gap-0.5 whitespace-nowrap">
                <i class="size-5 flex items-center justify-center [&_svg]:stroke-[1.5] [&_svg_g_path]:fill-cynBlack">
                    <?php Icon::print('Phone,-Call-11'); ?>
                </i>
                <span class="flex items-center gap-1 whitespace-nowrap text-sm font-semibold text-cynBlack">
                    <?php echo esc_html($home_cta_button_title); ?>
                </span>
            </a>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center lg:justify-end">
            <img src="<?php echo esc_url($home_cta_image_url); ?>" alt="<?php echo esc_attr($home_cta_title); ?>" class="w-[438px] h-auto object-contain dark:drop-shadow-[-5px_3px_14px_rgba(0,0,0,0.79)]" loading="lazy" decoding="async" />
        </div>
    </div>
</section>