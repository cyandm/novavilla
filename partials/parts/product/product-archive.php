<?php

use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

defined('ABSPATH') || exit;

global $wp_query;

$home_id = (int) get_option('page_on_front');
$is_main_archive = is_post_type_archive('product');
$is_tax_archive = is_tax(['product_cat', 'product_tag']);

$filter_query_args = [];
if (! empty($_GET['area'])) {
	$filter_query_args['area'] = sanitize_key(wp_unslash($_GET['area']));
}
if (! empty($_GET['rooms'])) {
	$filter_query_args['rooms'] = sanitize_key(wp_unslash($_GET['rooms']));
}
if (! empty($_GET['price_min'])) {
	$filter_query_args['price_min'] = (int) wp_unslash($_GET['price_min']);
}
if (! empty($_GET['price_max'])) {
	$filter_query_args['price_max'] = (int) wp_unslash($_GET['price_max']);
}

$base_url = $is_tax_archive ? get_term_link(get_queried_object()) : get_post_type_archive_link('product');
if (is_wp_error($base_url)) {
	$base_url = home_url('/');
}

$archive_total = (int) $wp_query->max_num_pages;
$archive_current = max(1, (int) get_query_var('paged'));
?>

<section id="product-archive" class="flex flex-col <?php echo $is_main_archive ? 'gap-11 md:gap-16' : 'gap-5 md:gap-4'; ?>">
	<?php if ($is_main_archive) : ?>
		<?php
		$hero_image_before = get_field('product_archive_hero_image_before', $home_id) ?: '';
		$hero_image_after = get_field('product_archive_hero_image_after', $home_id) ?: '';
		$hero_title_line_one = get_field('product_archive_hero_title_one', $home_id) ?: __('ویلایــــــــــــــی مـــــــدرن', 'novavilla');
		$hero_title_line_two = get_field('product_archive_hero_title_two', $home_id) ?: __('سریع‌تر از آنچه تصور می‌کنید', 'novavilla');
		$hero_description = get_field('product_archive_hero_description', $home_id) ?: '';
		$hero_button = get_field('product_archive_hero_button', $home_id);
		$hero_alt = trim($hero_title_line_one . ' ' . $hero_title_line_two);
		$hero_image_single = $hero_image_after ?: $hero_image_before;
		?>
		<section class="flex flex-col-reverse lg:flex-row lg:items-center gap-3 lg:gap-7">
			<div class="w-full lg:w-1/2 flex flex-col gap-3">
				<div class="flex flex-col gap-3">
					<h1 class="text-2xl lg:text-[40px] font-bold text-cynTextPrimary leading-9 lg:leading-14">
						<span class="text-cynPrimary">
							<?php echo esc_html($hero_title_line_one); ?>
							<br>
							<?php echo esc_html($hero_title_line_two); ?>
						</span>
					</h1>
					<?php if (!empty($hero_description)) : ?>
						<p class="text-sm lg:text-base font-light text-cynTextPrimary leading-5 lg:leading-7"><?php echo esc_html($hero_description); ?></p>
					<?php endif; ?>
				</div>

				<?php if (!empty($hero_button['url'])) : ?>
					<a href="<?php echo esc_url($hero_button['url']); ?>" <?php echo !empty($hero_button['target']) ? 'target="' . esc_attr($hero_button['target']) . '"' : ''; ?> class="primary-button w-fit">
						<span class="whitespace-nowrap text-xs lg:text-sm font-semibold"><?php echo esc_html($hero_button['title'] ?: __('درباره ی ما', 'novavilla')); ?></span>
					</a>
				<?php endif; ?>
			</div>

			<div class="relative w-full lg:w-1/2 shrink-0">
				<span class="pointer-events-none absolute left-1/2 top-1/2 size-32 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#FBB963] blur-[76px] lg:size-64 lg:blur-[148px]" aria-hidden="true"></span>
				<?php if ($hero_image_before && $hero_image_after) : ?>
					<div dir="ltr" data-hero-compare class="relative z-10 w-full h-56 lg:h-[439px] select-none touch-none cursor-ew-resize">
						<img src="<?php echo esc_url($hero_image_after); ?>" alt="<?php echo esc_attr($hero_alt); ?>" class="absolute inset-0 size-full object-contain pointer-events-none" loading="eager" decoding="async" />
						<div data-hero-compare-before class="absolute inset-0" style="clip-path: inset(0 50% 0 0);">
							<img src="<?php echo esc_url($hero_image_before); ?>" alt="" class="absolute inset-0 size-full object-contain pointer-events-none" loading="eager" decoding="async">
						</div>
						<?php Templates::getPart('hero-compare-handle'); ?>
					</div>
				<?php elseif (!empty($hero_image_single)) : ?>
					<img src="<?php echo esc_url($hero_image_single); ?>" alt="<?php echo esc_attr($hero_alt); ?>" class="relative z-10 w-full h-56 lg:h-[439px] object-contain" loading="eager" decoding="async" />
				<?php else : ?>
					<div class="relative z-10 w-full h-56 lg:h-[439px] bg-cynBgItem/40" aria-hidden="true"></div>
				<?php endif; ?>
			</div>
		</section>
	<?php elseif ($is_tax_archive) : ?>
		<?php
		$term = get_queried_object();
		$tax_title = ($term && ! is_wp_error($term)) ? $term->name : __('محصولات', 'novavilla');
		?>
		<h1 class="text-2xl md:text-4xl font-bold text-cynTextPrimary leading-9 md:leading-14"><?php echo esc_html($tax_title); ?></h1>
	<?php endif; ?>

	<div class="flex flex-col gap-5 md:gap-4">
		<button type="button" modal-opener data-modal-name="product-filter-modal" class="md:hidden w-fit flex items-center gap-1 rounded-xl border border-cynBorderHover/40 bg-cynWhite/10 px-3 py-3 text-sm font-medium text-cynTextPrimary transition-all duration-300">
			<span><?php esc_html_e('نمایش فیلتر ها', 'novavilla'); ?></span>
			<i class="size-5 flex items-center justify-center text-cynBorderHover [&_svg]:stroke-[1]"><?php Icon::print('Filter,-Sort-1'); ?></i>
		</button>

		<div class="flex flex-col md:grid md:grid-cols-4 gap-4">
			<aside
				id="product-filter-panel"
				modal
				data-modal-name="product-filter-modal"
				data-modal-layer="popup"
				data-active="false"
				aria-label="<?php esc_attr_e('فیلتر محصولات', 'novavilla'); ?>"
				class="fixed inset-x-3 top-1/2 z-50 max-h-[calc(100dvh-3rem)] -translate-y-1/2 overflow-y-auto scrollbar rounded-3xl border border-cynBorderHover/40 bg-cynWhite/10 backdrop-blur-md p-4 flex flex-col gap-5 opacity-0 pointer-events-none invisible data-[active='true']:opacity-100 data-[active='true']:pointer-events-auto data-[active='true']:visible transition-all duration-300 md:static md:inset-auto md:z-auto md:max-h-none md:translate-y-0 md:overflow-visible md:rounded-none md:border-0 md:bg-transparent md:backdrop-blur-none md:p-0 md:opacity-100 md:pointer-events-auto md:visible md:col-span-1">
				<div class="flex items-center justify-between gap-3 md:hidden">
					<span class="text-lg font-medium text-cynTextPrimary"><?php esc_html_e('فیلترها', 'novavilla'); ?></span>
					<button type="button" modal-closer data-modal-name="product-filter-modal" class="flex size-10 shrink-0 items-center justify-center rounded-full bg-cynBorderHover text-cynWhite dark:text-cynBlack" aria-label="<?php esc_attr_e('بستن', 'novavilla'); ?>">
						<i class="size-5 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5] rotate-45"><?php Icon::print('plus'); ?></i>
					</button>
				</div>

				<?php Templates::getPart('product/product-filter'); ?>
			</aside>

			<div class="flex flex-col gap-5 md:col-span-3">
				<?php if (have_posts()) : ?>
					<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
						<?php while (have_posts()) : the_post(); ?>
							<?php Templates::getCard('product', ['post-id' => get_the_ID()]); ?>
						<?php endwhile; ?>
					</div>

					<?php Templates::getPart('pagination', [
						'total' => $archive_total,
						'current' => $archive_current,
						'base_url' => $base_url,
						'query_args' => $filter_query_args,
						'aria_label' => __('صفحه‌بندی محصولات', 'novavilla'),
					]); ?>
				<?php else : ?>
					<?php Templates::getCard('product-not-found'); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>