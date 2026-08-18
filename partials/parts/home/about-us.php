<?php

defined('ABSPATH') || exit;

$home_id = get_the_ID();
$home_about_title = get_field('home_about_title', $home_id);
$home_about_description = get_field('home_about_description', $home_id);
$home_about_image = get_field('home_about_image', $home_id);
$home_about_button = get_field('home_about_button', $home_id);
$home_about_image_url = is_array($home_about_image) ? ($home_about_image['url'] ?? '') : $home_about_image;
$home_about_button_url = is_array($home_about_button) ? ($home_about_button['url'] ?? '') : '';
$home_about_button_title = is_array($home_about_button) ? ($home_about_button['title'] ?? '') : '';
$home_about_button_target = !empty($home_about_button['target']) ? 'target="' . esc_attr($home_about_button['target']) . '"' : '';
$home_about_button_title = !empty($home_about_button_title) ? $home_about_button_title : __('درباره ما', 'novavilla');

if (empty($home_about_button_url)) {
    $about_page_ids = get_posts(['post_type' => 'page', 'meta_key' => '_wp_page_template', 'meta_value' => 'templates/about-us.php', 'numberposts' => 1, 'fields' => 'ids']);
    $home_about_button_url = !empty($about_page_ids) ? get_permalink($about_page_ids[0]) : '';
}

if (empty($home_about_title) && empty($home_about_description) && empty($home_about_image_url)) return;
?>

<section class="container my-11 md:my-16">
    <div class="flex flex-col-reverse lg:flex-row lg:items-center gap-3 lg:gap-16">

        <div class="w-full lg:w-3/5 flex flex-col gap-3">
            <?php if (!empty($home_about_title)) : ?>
                <h2 class="text-2xl md:text-4xl font-bold text-cynTextPrimary leading-8 md:leading-12">
                    <?php echo esc_html($home_about_title); ?>
                </h2>
            <?php endif; ?>

            <?php if (!empty($home_about_description)) : ?>
                <p class="text-sm md:text-base font-light text-cynTextPrimary leading-5 md:leading-7">
                    <?php echo esc_html($home_about_description); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($home_about_button_url)) : ?>
                <a href="<?php echo esc_url($home_about_button_url); ?>" <?php echo $home_about_button_target; ?> class="primary-button w-fit">
                    <span class="text-sm font-semibold whitespace-nowrap">
                        <?php echo esc_html($home_about_button_title); ?>
                    </span>
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($home_about_image_url)) : ?>
            <div class="w-full lg:w-2/5 flex justify-center">
                <img src="<?php echo esc_url($home_about_image_url); ?>" alt="<?php echo esc_attr($home_about_title); ?>" class="w-[330px] h-auto object-contain rotate-6" loading="lazy" decoding="async" />
            </div>
        <?php endif; ?>

    </div>
</section>