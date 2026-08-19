<?php

/**
 * The template for displaying single blog posts
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * @package CyanTheme
 */

use Cyan\Theme\Helpers\Templates;
use Cyan\Theme\Helpers\Icon;

$current_post_id = get_the_ID();
$categories = get_the_category();
$tags = get_the_tags();

$related_args = [
    'post_type' => 'post',
    'posts_per_page' => 4,
    'post__not_in' => [$current_post_id],
];

if (!empty($categories)) {
    $related_args['category__in'] = wp_list_pluck($categories, 'term_id');
} elseif ($tags && !empty($tags)) {
    $related_args['tag__in'] = wp_list_pluck(array_values($tags), 'term_id');
}

$related_posts_query = new WP_Query($related_args);

get_header(); ?>

<?php Templates::getPart('breadcrumb'); ?>

<main class="single-post">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <section class="container flex flex-col gap-2 mb-5">

                <div class="text-cynTextPrimary/80 [&_a]:text-cynTextSecondaryHover text-xs font-medium flex gap-2 items-center"><?php the_category('|'); ?></div>

                <h1 class="text-3xl text-cynTextPrimary leading-11"><?php the_title(); ?></h1>

                <img src="<?= get_template_directory_uri(); ?>/assets/icon/zigzag.svg" alt="zigzag" class="w-32">

                <div class="flex gap-4 items-center mt-2">

                    <div class="flex flex-row gap-1 items-center justify-center">

                        <?= get_avatar('', '', '', '', ['class' => 'size-6 rounded-full']); ?>
                        <span class="text-cynTextPrimary/80 text-xs font-semibold"><?php the_author(); ?></span>

                    </div>

                    <div class="flex flex-row gap-1 items-center justify-center">

                        <i class="size-6 text-cynTextPrimary/80">
                            <?php Icon::print('calendar-schedule-1-1') ?>
                        </i>
                        <span class="text-cynTextPrimary/80 text-xs font-semibold"><?= get_the_date(); ?></span>

                    </div>

                </div>

                <div class="flex justify-between p-6 rounded-3xl bg-cynBgItem border border-cynBorder mt-2 items-center">

                    <div class="flex items-center">
                        <i class="size-6 text-cynTextPrimary cursor-pointer stroke-[1.5]" id="shareBtn">
                            <?php Icon::print('Share-2') ?>
                        </i>
                    </div>

                    <div class="flex items-center gap-2">

                        <span class="text-cynTextPrimary text-base font-medium"><?= get_comments_number(); ?></span>

                        <i class="size-6 text-cynTextPrimary stroke-[1.5]">
                            <?php Icon::print('Chat,-Messages,-Bubble-6') ?>
                        </i>

                    </div>

                </div>
            </section>

            <section class="container single-post-content">

                <div class="w-full">
                    <?php the_post_thumbnail('full', ['class' => 'w-full h-[320px] md:h-[460px] lg:h-[770px] rounded-3xl object-cover object-center']) ?>
                </div>

                <div class="text-cynTextPrimary [&_a]:text-cynBlue [&_a]:font-normal [&_h2]:text-2xl [&_h2]:my-4 [&_h3]:text-xl [&_h3]:my-4 [&_h4]:text-xl [&_h4]:my-4 [&_p]:text-base [&_p]:font-light [&_p]:leading-8 [&_p]:my-4 [&_img]:w-full [&_img]:object-cover [&_blockquote]:bg-cynBgItem [&_blockquote]:backdrop-blur-xl [&_blockquote]:rounded-3xl [&_blockquote]:p-3 [&_blockquote_p]:m-0 [&_blockquote]:my-4 [&_blockquote]:text-base [&_blockquote]:font-medium [&_img]:rounded-3xl">
                    <?php the_content(); ?>
                </div>

            </section>

            <!-- Comments Section -->
            <?php if (comments_open() || get_comments_number()) : ?>
                <section class="container comments-section mt-5">
                    <?php Templates::getPart('comment'); ?>
                </section>
            <?php endif; ?>

            <!-- Related Posts Section -->
            <?php if ($related_posts_query->have_posts()) : ?>
                <section class="container flex flex-col gap-3 my-14">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-2xl text-cynTextPrimary leading-11"><?php esc_html_e('شاید بپسندید', 'novavilla'); ?></p>

                        <div class="flex items-center gap-2 lg:hidden">
                            <button type="button" id="relatedPostsPrev" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('قبلی', 'novavilla'); ?>">
                                <i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]"><?php Icon::print('Arrow-19'); ?></i>
                            </button>
                            <button type="button" id="relatedPostsNext" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('بعدی', 'novavilla'); ?>">
                                <i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]"><?php Icon::print('Arrow-27'); ?></i>
                            </button>
                        </div>
                    </div>

                    <div class="hidden lg:grid lg:grid-cols-4 gap-3">
                        <?php while ($related_posts_query->have_posts()) : $related_posts_query->the_post(); ?>
                            <?php Templates::getCard('blog'); ?>
                        <?php endwhile; ?>
                    </div>

                    <div class="lg:hidden">
                        <?php $related_posts_query->rewind_posts(); ?>
                        <swiper-container class="w-full" slides-per-view="auto" space-between="12" loop="true" pagination="false" navigation="true" navigation-next-el="#relatedPostsNext" navigation-prev-el="#relatedPostsPrev" breakpoints='{"768": {"slidesPerView": 2}}'>
                            <?php while ($related_posts_query->have_posts()) : $related_posts_query->the_post(); ?>
                                <swiper-slide>
                                    <?php Templates::getCard('blog'); ?>
                                </swiper-slide>
                            <?php endwhile; ?>
                        </swiper-container>
                    </div>
                </section>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>

    <?php endwhile;
    endif; ?>

</main>

<?php get_footer();
