<?php

use Cyan\Theme\Helpers\Templates;

$home_page_id = get_option('page_on_front');

$faq_title = get_field('home_faq_title', $home_page_id);
$faq_under_title = get_field('home_faq_under_title', $home_page_id);
$faq_button = get_field('home_faq_button', $home_page_id);
$faq_button_link = get_option('phone_number_support');

$faq_place = get_query_var('args', [])['faq_place'] ?? '';

$faq_cats = get_terms([
    'taxonomy' => 'faq_cat',
    'hide_empty' => true
]);

$has_cats = !empty($faq_cats) && !is_wp_error($faq_cats);

// If no FAQs for this page (faq_place), don't show the section
$faq_count_args = [
    'post_type' => 'faq',
    'posts_per_page' => 1,
    'fields' => 'ids',
];
if (!empty($faq_place)) {
    $faq_count_args['tax_query'] = [
        [
            'taxonomy' => 'faq_place',
            'field' => 'slug',
            'terms' => $faq_place,
        ]
    ];
}
$has_faqs = !empty(get_posts($faq_count_args));
if (!$has_faqs) {
    return;
}
?>

<section class="my-14 flex flex-col gap-3" id="faq">

    <div class="container flex justify-between items-center gap-3">

        <div class="flex flex-col gap-2 max-md:w-full">

            <h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary">
                <?php echo $faq_title ? esc_html($faq_title) : esc_html__('سوالات متداول', 'novavilla'); ?>
            </h2>

            <?php if (!empty($faq_under_title)) : ?>
                <p class="text-cynTextPrimary/50 text-sm md:text-base font-medium"><?php echo esc_html($faq_under_title); ?></p>
            <?php endif; ?>

        </div>

        <a href="<?php echo esc_url($faq_button['url'] ?? (!empty($faq_button_link) ? 'tel:' . $faq_button_link : '/contact-us')); ?>" class="primary-button text-xs md:text-sm font-semibold hidden md:inline-flex">
            <span class="flex items-center justify-center whitespace-nowrap">
                <?php _e('تماس با ما', 'novavilla'); ?>
            </span>
        </a>

    </div>

    <div class="flex flex-col">
        <?php if ($has_cats) : ?>
            <div class="flex xl:justify-center items-center gap-2 overflow-x-scroll scrollbar py-5 max-lg:px-2 max-lg:ms-6 max-md:ms-2.5">
                <?php foreach ($faq_cats as $index => $category) : ?>
                    <div class="fade-in-down" anim-delay="<?php echo $index * 0.3 ?>">
                        <div id="<?php echo "faq-cat-" . $category->term_id ?>" class="faq-handler | bg-cynBgItem text-cynTextPrimary cursor-pointer hover:bg-cynTextPrimaryHover hover:text-cynBlack text-sm md:text-base font-medium duration-300 rounded-2xl py-3 px-4 max-md:px-2 w-full flex justify-center items-center whitespace-nowrap shadow-item">
                            <?php echo $category->name ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="container">
            <div class="col-span-5 max-md:col-span-6 bg-cynBgItem rounded-3xl border border-cynBorder overflow-hidden backdrop-blur-xl">
                <div class="fade-in-down"
                    anim-delay="0.8">
                    <?php if ($has_cats) : ?>
                        <?php foreach ($faq_cats as $index => $category) : ?>
                            <div class="faq-panel grid grid-rows-[0fr] transition-all duration-700"
                                controlled-by="<?php echo "faq-cat-" . $category->term_id ?>">
                                <div class="overflow-hidden">
                                    <?php Templates::getPart('faq-group', ['term_ids' => [$category->term_id], 'faq_place' => $faq_place]); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="faq-panel grid grid-rows-[1fr]">
                            <div class="overflow-hidden">
                                <?php Templates::getPart('faq-group', ['term_ids' => [], 'faq_place' => $faq_place]); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <div class="container flex justify-center items-center md:hidden mt-3">

        <a href="<?php echo esc_url($faq_button['url'] ?? (!empty($faq_button_link) ? 'tel:' . $faq_button_link : '/contact-us')); ?>" class="primary-button text-xs font-semibold w-full justify-center">
            <?php _e('تماس با ما', 'novavilla'); ?>
        </a>

    </div>
</section>