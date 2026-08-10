<?php

use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

defined('ABSPATH') || exit;

global $wp_query;

$search_type = empty($_GET['search-type']) ? 'all' : sanitize_key(wp_unslash($_GET['search-type']));
$search_query = get_search_query();
$has_search = !empty($_GET['s']);

$search_filters = [
	['value' => 'all', 'id' => 'search-all', 'label' => __('همه', 'novavilla')],
	['value' => 'product', 'id' => 'search-product', 'label' => __('فروشگاه', 'novavilla')],
	['value' => 'post', 'id' => 'search-blog', 'label' => __('بلاگ', 'novavilla')],
];

?>

<?php get_header(); ?>

<?php Templates::getPart('breadcrumb'); ?>

<main id="search-page" class="flex flex-col gap-5 lg:gap-8">

    <section class="container">
        <form id="search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
			<label for="search-page-input" class="flex w-full items-center gap-2.5 rounded-xl border border-[#30303033] bg-cynWhite py-1 pe-1.5 ps-2.5 transition-all duration-300 focus-within:border-cynBorderHover lg:w-[379px] lg:shrink-0">
				<span class="shrink-0 text-[#303030]" aria-hidden="true">
					<i class="size-6 flex items-center justify-center [&_svg]:stroke-[1.5]"><?php Icon::print('Search,-Loupe'); ?></i>
				</span>
				<input id="search-page-input" type="search" name="s" value="<?php echo esc_attr($search_query); ?>" placeholder="<?php esc_attr_e('دنبال چی میگردی؟', 'novavilla'); ?>" class="flex-1 min-w-0 border-0 bg-transparent text-sm font-normal text-[#303030] outline-none placeholder:text-[#303030]/50">
				<button type="submit" class="shrink-0 rounded-lg bg-[#272727] px-2.5 py-2 text-xs font-semibold text-cynWhite transition-all duration-300 hover:bg-cynBgButtonHover hover:text-cynBlack"><?php esc_html_e('جستجو کن', 'novavilla'); ?></button>
			</label>

            <div class="flex items-center justify-between gap-6 border-b border-cynTextPrimary/25 pb-5 lg:border-0 lg:pb-0">
                <span class="text-base font-medium text-cynTextPrimary shrink-0"><?php esc_html_e('جستجو در:', 'novavilla'); ?></span>
                <div class="flex items-center gap-4 lg:gap-6">
                    <?php foreach ($search_filters as $filter) : ?>
                        <label for="<?php echo esc_attr($filter['id']); ?>" class="group flex items-center gap-1 cursor-pointer">
                            <span class="text-sm font-medium text-cynTextPrimary/60 transition-colors group-has-[:checked]:text-cynTextPrimary"><?php echo esc_html($filter['label']); ?></span>
                            <span class="relative flex size-6 shrink-0 items-center justify-center rounded-full border border-[#ccc] bg-cynWhite group-has-[:checked]:border-cynBorderHover">
                                <input class="peer sr-only" type="radio" name="search-type" id="<?php echo esc_attr($filter['id']); ?>" value="<?php echo esc_attr($filter['value']); ?>" <?php checked($search_type, $filter['value']); ?>>
                                <span class="size-4 rounded-full bg-cynBorderHover opacity-0 transition-opacity peer-checked:opacity-100"></span>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </form>
    </section>

    <?php if ($has_search) : ?>
        <section class="container flex flex-col gap-5 lg:gap-5">
            <div class="flex justify-start">
                <p class="text-base font-normal text-cynTextPrimary/60">
                    <?php echo esc_html(sprintf(_n('%s نتیجه', '%s نتیجه', (int) $wp_query->found_posts, 'novavilla'), number_format_i18n((int) $wp_query->found_posts))); ?>
                </p>
            </div>

            <?php if (have_posts()) : ?>
                <?php
                $product_ids = [];
                $post_ids = [];

                while (have_posts()) {
                    the_post();
                    if (get_post_type() === 'product') {
                        $product_ids[] = get_the_ID();
                    } else {
                        $post_ids[] = get_the_ID();
                    }
                }

                $search_total = (int) $wp_query->max_num_pages;
                $search_current = max(1, (int) get_query_var('paged'));
                ?>

                <div class="flex flex-col gap-11 lg:gap-16">
                    <?php if (!empty($product_ids)) : ?>
                        <div id="searchProductsWrapper" class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                            <?php foreach ($product_ids as $product_id) : ?>
                                <?php Templates::getCard('search-product', ['post-id' => $product_id]); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($post_ids)) : ?>
                        <div id="searchPostsWrapper" class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                            <?php foreach ($post_ids as $post_id) : ?>
                                <?php Templates::getCard('search-blog', ['post-id' => $post_id]); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

				<?php
				$search_query_args = ['s' => $search_query];
				if ($search_type !== 'all') {
					$search_query_args['search-type'] = $search_type;
				}

				Templates::getPart('pagination', [
					'total' => $search_total,
					'current' => $search_current,
					'base_url' => home_url('/'),
					'query_args' => $search_query_args,
					'aria_label' => __('صفحه‌بندی جستجو', 'novavilla'),
				]);
				?>
            <?php else : ?>
                <?php Templates::getCard('search-not-found'); ?>
            <?php endif; ?>
        </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>