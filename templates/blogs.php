<?php
/* Template Name: Blogs */

use Cyan\Theme\Helpers\Templates;
use Cyan\Theme\Helpers\Icon;

$blogs_title = get_field('blogs_title');

$newest_blogs = get_posts([
    'post_type' => 'post',
    'posts_per_page' => 4,
    'orderby' => 'date',
    'order' => 'DESC',
    'fields' => 'ids',
]);

get_header();
?>

<?php Templates::getPart('breadcrumb'); ?>

<main class="archive-blog container space-y-11 md:space-y-16">

    <?php if (!empty($newest_blogs)) : ?>
       <section class="flex flex-col gap-3">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12">
                    <?php echo $blogs_title ? esc_html($blogs_title) : __('جدیدترین مقالات', 'novavilla'); ?>
                </h2>

                <div class="flex items-center gap-2 lg:hidden">
                    <button type="button" id="blogsPrev" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('قبلی', 'novavilla'); ?>">
                        <i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
                            <?php Icon::print('Arrow-19'); ?>
                        </i>
                    </button>
                    <button type="button" id="blogsNext" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('بعدی', 'novavilla'); ?>">
                        <i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
                            <?php Icon::print('Arrow-27'); ?>
                        </i>
                    </button>
                </div>
            </div>

            <div class="hidden lg:grid lg:grid-cols-4 gap-3">
                <?php foreach ($newest_blogs as $blog_id) : ?>
                    <?php Templates::getCard('blog', ['post-id' => $blog_id]); ?>
                <?php endforeach; ?>
            </div>

            <div class="lg:hidden">
                <swiper-container class="w-full" slides-per-view="auto" space-between="12" loop="true" pagination="false" navigation="true" navigation-next-el="#blogsNext" navigation-prev-el="#blogsPrev" breakpoints='{"768": {"slidesPerView": 2}}'>
                    <?php foreach ($newest_blogs as $blog_id) : ?>
                        <swiper-slide>
                            <?php Templates::getCard('blog', ['post-id' => $blog_id]); ?>
                        </swiper-slide>
                    <?php endforeach; ?>
                </swiper-container>
            </div>
        </section>
    <?php endif; ?>

    <?php Templates::getPart('blog-archive'); ?>
</main>

<?php get_footer(); ?>