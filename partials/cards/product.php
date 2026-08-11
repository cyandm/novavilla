<?php

use Cyan\Theme\Helpers\Icon;

$args = get_query_var('args', []);
$post_id = $args['post-id'] ?? get_the_ID();

if (empty($post_id)) {
	return;
}

$title = get_the_title($post_id);
$permalink = get_permalink($post_id);
$price_raw = get_field('product_price', $post_id);
$area_raw = get_field('product_area', $post_id);
$rooms_raw = get_field('product_rooms', $post_id);
$price_html = '';

if ($price_raw !== null && $price_raw !== '') {
	$price_number = is_numeric($price_raw) ? number_format_i18n((float) $price_raw) : $price_raw;
	$price_html = $price_number . ' ' . __('تومان', 'novavilla');
}

$area_html = '';
if ($area_raw !== null && $area_raw !== '') {
	$area_html = is_numeric($area_raw) ? number_format_i18n((float) $area_raw) . ' ' . __('متر', 'novavilla') : $area_raw;
}

$rooms_html = '';
if ($rooms_raw !== null && $rooms_raw !== '') {
	$rooms_html = is_numeric($rooms_raw) ? number_format_i18n((float) $rooms_raw) : $rooms_raw;
}
?>

<a href="<?php echo esc_url($permalink); ?>" class="group flex flex-col rounded-3xl overflow-hidden border border-transparent hover:border-cynBorderHover transition-all duration-300">
	<div class="w-full h-[276px] 2xl:h-80 overflow-hidden">
		<?php if (has_post_thumbnail($post_id)) : ?>
			<?php echo get_the_post_thumbnail($post_id, 'medium_large', ['class' => 'w-full h-full object-cover', 'alt' => esc_attr($title), 'loading' => 'lazy', 'decoding' => 'async']); ?>
		<?php else : ?>
			<div class="w-full h-full bg-cynBgSocial/40" aria-hidden="true"></div>
		<?php endif; ?>
	</div>

	<div class="flex flex-col gap-1 bg-cynWhite/8 border-t border-transparent group-hover:border-t-cynBorderHover transition-all duration-300">
		<div class="flex flex-col gap-1.5 px-3 py-2">
			<?php if (!empty($title)) : ?>
				<h3 class="text-base font-normal text-cynWhite leading-6 line-clamp-1"><?php echo esc_html($title); ?></h3>
			<?php endif; ?>

			<?php if (!empty($area_html) || !empty($rooms_html)) : ?>
				<div class="flex items-center gap-3">
					<?php if (!empty($area_html)) : ?>
						<span class="flex items-center gap-0.5 text-xs font-semibold text-[#A3A3A3]">
							<i class="size-5 shrink-0 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-current [&_svg]:stroke-[1.5]"><?php Icon::print('home-house-big'); ?></i>
							<span><?php echo esc_html($area_html); ?></span>
						</span>
					<?php endif; ?>
					<?php if (!empty($rooms_html)) : ?>
						<span class="flex items-center gap-0.5 text-xs font-semibold text-[#A3A3A3]">
							<i class="size-5 shrink-0 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-current [&_svg]:stroke-[1.5]"><?php Icon::print('Double,-Bed-1'); ?></i>
							<span><?php echo esc_html($rooms_html); ?></span>
						</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if (!empty($price_html)) : ?>
				<div class="flex items-center justify-between rounded-lg bg-cynBlack px-3 py-1.5 group-hover:bg-cynBorderHover transition-all duration-300">
					<span class="text-sm font-normal text-cynWhite group-hover:text-cynBlack transition-all duration-300"><?php esc_html_e('قیمت', 'novavilla'); ?></span>
					<span class="text-sm font-normal text-cynWhite group-hover:text-cynBlack transition-all duration-300"><?php echo esc_html($price_html); ?></span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</a>