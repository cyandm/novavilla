<?php

use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;

$home_id = get_the_ID();
$home_3d_title = get_field('home_3d_title', $home_id);
$home_3d_title_colored = get_field('home_3d_title_colored', $home_id);
$home_3d_description = get_field('home_3d_description', $home_id);
$home_3d_image = get_field('home_3d_image', $home_id);
$home_3d_image_light = get_field('home_3d_image_light', $home_id);
$home_3d_button = get_field('home_3d_button', $home_id);
$home_3d_image_url = is_array($home_3d_image) ? ($home_3d_image['url'] ?? '') : $home_3d_image;
$home_3d_image_light_url = is_array($home_3d_image_light) ? ($home_3d_image_light['url'] ?? '') : $home_3d_image_light;
$home_3d_button_url = is_array($home_3d_button) ? ($home_3d_button['url'] ?? '') : '';
$home_3d_button_title = is_array($home_3d_button) ? ($home_3d_button['title'] ?? '') : '';
$home_3d_button_target = !empty($home_3d_button['target']) ? 'target="' . esc_attr($home_3d_button['target']) . '"' : '';
$home_3d_button_title = !empty($home_3d_button_title) ? $home_3d_button_title : __('جزئیات خدمات', 'novavilla');

if (empty($home_3d_button_url)) {
    $structure_page_ids = get_posts(['post_type' => 'page', 'meta_key' => '_wp_page_template', 'meta_value' => 'templates/3d-structure.php', 'numberposts' => 1, 'fields' => 'ids']);
    $home_3d_button_url = !empty($structure_page_ids) ? get_permalink($structure_page_ids[0]) : '';
}

if (empty($home_3d_image_url) && empty($home_3d_image_light_url)) return;
?>

<section class="container my-11 md:my-16">
    <div class="flex flex-col lg:flex-row lg:items-stretch gap-3 rounded-3xl border border-cynBorderHover/40 bg-cynWhite/10 p-4">

        <div class="w-full lg:w-4/7">
            <?php if (!empty($home_3d_image_light_url)) : ?>
                <img src="<?php echo esc_url($home_3d_image_light_url); ?>" alt="<?php echo esc_attr(trim($home_3d_title . ' ' . $home_3d_title_colored)); ?>" class="w-full h-48 sm:h-full object-cover rounded-2xl<?php echo !empty($home_3d_image_url) ? ' dark:hidden' : ''; ?>" loading="lazy" decoding="async" />
            <?php endif; ?>
            <?php if (!empty($home_3d_image_url)) : ?>
                <img src="<?php echo esc_url($home_3d_image_url); ?>" alt="<?php echo esc_attr(trim($home_3d_title . ' ' . $home_3d_title_colored)); ?>" class="w-full h-48 sm:h-full object-cover rounded-2xl<?php echo !empty($home_3d_image_light_url) ? ' hidden dark:block' : ''; ?>" loading="lazy" decoding="async" />
            <?php endif; ?>
        </div>

        <div class="w-full lg:w-3/7 flex flex-col gap-3 justify-center">
            <div class="flex flex-col">
                <h2 class="text-3xl md:text-5xl font-bold leading-tight md:leading-18">
                    <?php if (!empty($home_3d_title)) : ?>
                        <span class="block text-cynTextPrimary">
                            <?php echo esc_html($home_3d_title); ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($home_3d_title_colored)) : ?>
                        <span class="block text-cynBorderHover">
                            <?php echo esc_html($home_3d_title_colored); ?>
                        </span>
                    <?php endif; ?>
                </h2>
            </div>

            <?php if (!empty($home_3d_description)) : ?>
                <div class="text-sm md:text-base font-light text-cynTextPrimary leading-7">
                    <?php echo wp_kses_post($home_3d_description); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($home_3d_button_url)) : ?>
                <a href="<?php echo esc_url($home_3d_button_url); ?>" <?php echo $home_3d_button_target; ?> class="primary-button btn-have-icon !py-2 w-fit">
                    <span class="text-xs font-semibold whitespace-nowrap">
                        <?php echo esc_html($home_3d_button_title); ?>
                    </span>
                    <i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
                        <?php Icon::print('Arrow-27'); ?>
                    </i>
                </a>
            <?php endif; ?>
        </div>

    </div>
</section>