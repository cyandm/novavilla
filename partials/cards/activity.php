<?php

use Cyan\Theme\Helpers\Icon;

$args = get_query_var('args', []);
$post_id = $args['post-id'] ?? get_the_ID();

if (empty($post_id)) {
    return;
}

$title = get_the_title($post_id);
$desc = get_field('activity_description', $post_id);
$btn = get_field('activity_btn', $post_id);
?>

<article class="flex flex-col gap-2 rounded-3xl border border-cynBorder hover:border-cynBorderHover transition-all duration-300 bg-cynBgItem backdrop-blur-xl overflow-hidden h-full p-4">

    <?php if (has_post_thumbnail($post_id)) : ?>
        <?php echo get_the_post_thumbnail($post_id, 'large', ['class' => 'w-full h-[187px] lg:h-[170px] object-cover rounded-3xl', 'alt' => esc_attr($title), 'loading' => 'lazy', 'decoding' => 'async']); ?>
    <?php else : ?>
        <div class="w-full h-[187px] lg:h-[170px] rounded-3xl bg-cynBgSocial/40" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="flex flex-col gap-6 flex-1">
        <div class="flex flex-col gap-1">
            <?php if (!empty($title)) : ?>
                <h3 class="text-base font-medium text-cynTextPrimary">
                    <?php echo esc_html($title); ?>
                </h3>
            <?php endif; ?>

            <?php if (!empty($desc)) : ?>
                <p class="text-xs font-light text-cynTextPrimary leading-4.5">
                    <?php echo esc_html($desc); ?>
                </p>
            <?php endif; ?>
        </div>

        <?php if (!empty($btn['url'])) : ?>
            <div class="flex justify-center mt-auto">
                <a href="<?php echo esc_url($btn['url']); ?>" <?php echo !empty($btn['target']) ? 'target="' . esc_attr($btn['target']) . '"' : ''; ?> class="primary-button btn-have-icon">
                    <span class="flex items-center gap-1 whitespace-nowrap">
                        <?php echo esc_html($btn['title'] ?: __('مشاهده محصولات', 'novavilla')); ?>
                        <i class="size-5 flex items-center justify-center [&_svg]:stroke-[2]">
                            <?php Icon::print('Arrow-27'); ?>
                        </i>
                    </span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</article>