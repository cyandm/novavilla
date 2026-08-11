<?php

use Cyan\Theme\Classes\ProductFilter;
use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;

$current_area = isset($_GET['area']) ? sanitize_key(wp_unslash($_GET['area'])) : '';
$current_rooms = isset($_GET['rooms']) ? sanitize_key(wp_unslash($_GET['rooms'])) : '';
$has_price_min = isset($_GET['price_min']) && $_GET['price_min'] !== '';
$has_price_max = isset($_GET['price_max']) && $_GET['price_max'] !== '';
$current_price_min = $has_price_min ? max(0, (int) wp_unslash($_GET['price_min'])) : 0;
$current_price_max = $has_price_max ? max(0, (int) wp_unslash($_GET['price_max'])) : 0;

$price_bounds_args = [];
if (is_tax(['product_cat', 'product_tag'])) {
	$bounds_term = get_queried_object();
	if ($bounds_term && ! is_wp_error($bounds_term)) {
		$price_bounds_args = ['term_id' => (int) $bounds_term->term_id, 'taxonomy' => $bounds_term->taxonomy];
	}
}
$price_bounds = ProductFilter::getPriceBounds($price_bounds_args);
$price_floor = $price_bounds ? $price_bounds['min'] : 0;
$price_ceiling = $price_bounds ? $price_bounds['max'] : 0;
if ($price_bounds && $price_ceiling <= $price_floor) {
	$price_ceiling = $price_floor + ProductFilter::getPriceStep($price_floor, $price_floor + 1000000);
}
$price_step = $price_bounds ? ProductFilter::getPriceStep($price_floor, $price_ceiling) : 1;
$slider_min = $has_price_min ? max($price_floor, min($current_price_min, $price_ceiling)) : $price_floor;
$slider_max = $has_price_max ? max($price_floor, min($current_price_max, $price_ceiling)) : $price_ceiling;
$clear_url = is_tax(['product_cat', 'product_tag']) ? (($term = get_queried_object()) && ! is_wp_error($term) ? get_term_link($term) : get_post_type_archive_link('product')) : get_post_type_archive_link('product');
if (is_wp_error($clear_url)) {
	$clear_url = home_url('/');
}

$format_price_label = static function (int $amount): string {
	if ($amount >= 1000000000) {
		return number_format_i18n((int) round($amount / 1000000000)) . ' ' . __('میلیارد', 'novavilla');
	}
	if ($amount >= 1000000) {
		return number_format_i18n((int) round($amount / 1000000)) . ' ' . __('میلیون', 'novavilla');
	}
	return number_format_i18n($amount);
};

$format_price_input = static function (int $amount): string {
	return number_format_i18n($amount) . ' ' . __('تومان', 'novavilla');
};

$area_filters = [
	['value' => 'under_80', 'id' => 'product-area-under-80', 'label' => __('زیر ۸۰ متر', 'novavilla')],
	['value' => '80_150', 'id' => 'product-area-80-150', 'label' => __('از ۸۰ تا ۱۵۰', 'novavilla')],
	['value' => '150_200', 'id' => 'product-area-150-200', 'label' => __('از ۱۵۰ تا ۲۰۰', 'novavilla')],
	['value' => 'above_200', 'id' => 'product-area-above-200', 'label' => __('بالای ۲۰۰', 'novavilla')],
];

$rooms_filters = [
	['value' => '1', 'id' => 'product-rooms-1', 'label' => '1'],
	['value' => '2', 'id' => 'product-rooms-2', 'label' => '2'],
	['value' => '3', 'id' => 'product-rooms-3', 'label' => '3'],
	['value' => '4plus', 'id' => 'product-rooms-4plus', 'label' => '4+'],
];

$accordion_panel_class = 'rounded-3xl border border-cynBorderHover/40 bg-cynBgItem/80 backdrop-blur-2xl p-4 flex flex-col';
$accordion_content_class = 'grid transition-[grid-template-rows] duration-300 ease-out !mt-0';
$chip_class = 'flex h-10 w-full items-center rounded-xl px-3 text-sm font-medium transition-colors duration-300 cursor-pointer has-[:checked]:bg-cynBorderHover has-[:checked]:text-cynBlack bg-cynBlack text-cynWhite';
?>

<form id="product-filter-form" method="get" action="<?php echo esc_url($clear_url); ?>" class="flex flex-col gap-3">
	<div class="<?php echo esc_attr($accordion_panel_class); ?>">
		<button type="button" class="accordion-button flex w-full items-center justify-between gap-2 cursor-pointer bg-transparent border-none p-0 text-start" data-accordion-target="product-filter-area" data-accordion-icon-rotate="180" aria-expanded="true" aria-controls="product-filter-area">
			<span class="text-base font-medium text-cynTextPrimary"><?php esc_html_e('متراژ', 'novavilla'); ?></span>
			<i class="accordion-icon size-6 shrink-0 text-cynBorderHover transition-transform duration-300 [&_svg]:stroke-[1.5]" style="transform: rotate(180deg);"><?php Icon::print('Arrow-28'); ?></i>
		</button>
		<div id="product-filter-area" class="<?php echo esc_attr($accordion_content_class); ?>" data-accordion-content="product-filter-area" style="grid-template-rows: 1fr;">
			<div class="min-h-0 overflow-hidden">
				<div class="flex flex-col gap-3 pt-3">
					<?php foreach ($area_filters as $filter) : ?>
						<label for="<?php echo esc_attr($filter['id']); ?>" class="<?php echo esc_attr($chip_class); ?>">
							<input class="hidden" type="radio" name="area" id="<?php echo esc_attr($filter['id']); ?>" value="<?php echo esc_attr($filter['value']); ?>" <?php checked($current_area, $filter['value']); ?>>
							<span><?php echo esc_html($filter['label']); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="<?php echo esc_attr($accordion_panel_class); ?>">
		<button type="button" class="accordion-button flex w-full items-center justify-between gap-2 cursor-pointer bg-transparent border-none p-0 text-start" data-accordion-target="product-filter-rooms" data-accordion-icon-rotate="180" aria-expanded="true" aria-controls="product-filter-rooms">
			<span class="text-base font-medium text-cynTextPrimary"><?php esc_html_e('تعداد اتاق', 'novavilla'); ?></span>
			<i class="accordion-icon size-6 shrink-0 text-cynBorderHover transition-transform duration-300 [&_svg]:stroke-[1.5]" style="transform: rotate(180deg);"><?php Icon::print('Arrow-28'); ?></i>
		</button>
		<div id="product-filter-rooms" class="<?php echo esc_attr($accordion_content_class); ?>" data-accordion-content="product-filter-rooms" style="grid-template-rows: 1fr;">
			<div class="min-h-0 overflow-hidden">
				<div class="flex flex-col gap-3 pt-3">
					<?php foreach ($rooms_filters as $filter) : ?>
						<label for="<?php echo esc_attr($filter['id']); ?>" class="<?php echo esc_attr($chip_class); ?>">
							<input class="hidden" type="radio" name="rooms" id="<?php echo esc_attr($filter['id']); ?>" value="<?php echo esc_attr($filter['value']); ?>" <?php checked($current_rooms, $filter['value']); ?>>
							<span><?php echo esc_html($filter['label']); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<?php if ($price_bounds) : ?>
		<div class="<?php echo esc_attr($accordion_panel_class); ?>">
			<button type="button" class="accordion-button flex w-full items-center justify-between gap-2 cursor-pointer bg-transparent border-none p-0 text-start" data-accordion-target="product-filter-price" data-accordion-icon-rotate="180" aria-expanded="true" aria-controls="product-filter-price">
				<span class="text-base font-medium text-cynTextPrimary"><?php esc_html_e('رنج قیمت', 'novavilla'); ?></span>
				<i class="accordion-icon size-6 shrink-0 text-cynBorderHover transition-transform duration-300 [&_svg]:stroke-[1.5]" style="transform: rotate(180deg);"><?php Icon::print('Arrow-28'); ?></i>
			</button>
			<div id="product-filter-price" class="<?php echo esc_attr($accordion_content_class); ?>" data-accordion-content="product-filter-price" style="grid-template-rows: 1fr;">
				<div class="min-h-0 overflow-hidden">
					<div class="flex flex-col gap-3 pt-3" data-product-price-range data-price-floor="<?php echo esc_attr($price_floor); ?>" data-price-ceiling="<?php echo esc_attr($price_ceiling); ?>">
						<div class="flex items-center gap-1 md:grid md:grid-cols-[auto_1fr] md:gap-1 lg:flex lg:items-center">
							<span class="shrink-0 text-sm text-cynTextPrimary"><?php esc_html_e('شروع از', 'novavilla'); ?></span>
							<input type="text" inputmode="numeric" name="price_min" data-price-min-input value="<?php echo $has_price_min ? esc_attr($format_price_input($current_price_min)) : ''; ?>" placeholder="<?php echo esc_attr($format_price_input($price_floor)); ?>" class="product-filter-price-input flex-1 min-w-0 h-[37px] rounded-lg border border-cynTextPrimary/10 bg-cynBlack px-1.5 text-xs font-light text-cynTextPrimary outline-none transition-colors focus:border-cynBorderHover placeholder:text-cynTextPrimary/50">
							<span class="shrink-0 text-sm text-cynTextPrimary"><?php esc_html_e('تا', 'novavilla'); ?></span>
							<input type="text" inputmode="numeric" name="price_max" data-price-max-input value="<?php echo $has_price_max ? esc_attr($format_price_input($current_price_max)) : ''; ?>" placeholder="<?php echo esc_attr($format_price_input($price_ceiling)); ?>" class="product-filter-price-input flex-1 min-w-0 h-[37px] rounded-lg border border-cynTextPrimary/10 bg-cynBlack px-1.5 text-xs font-light text-cynTextPrimary outline-none transition-colors focus:border-cynBorderHover placeholder:text-cynTextPrimary/50">
						</div>

						<div class="product-price-range relative h-3 pt-1">
							<div class="absolute inset-x-0 top-1/2 h-0.5 -translate-y-1/2 rounded-full bg-[#EAEAEA]"></div>
							<div class="product-price-range__fill absolute top-1/2 h-0.5 -translate-y-1/2 rounded-full bg-cynWhite" data-price-range-fill></div>
							<input type="range" data-price-min-range min="<?php echo esc_attr($price_floor); ?>" max="<?php echo esc_attr($price_ceiling); ?>" step="<?php echo esc_attr($price_step); ?>" value="<?php echo esc_attr($slider_min); ?>" class="product-price-range__input" aria-label="<?php esc_attr_e('حداقل قیمت', 'novavilla'); ?>">
							<input type="range" data-price-max-range min="<?php echo esc_attr($price_floor); ?>" max="<?php echo esc_attr($price_ceiling); ?>" step="<?php echo esc_attr($price_step); ?>" value="<?php echo esc_attr($slider_max); ?>" class="product-price-range__input" aria-label="<?php esc_attr_e('حداکثر قیمت', 'novavilla'); ?>">
						</div>

						<div class="flex items-center justify-between gap-2 text-xs font-normal text-cynTextPrimary">
							<span data-price-min-label><?php echo esc_html($format_price_label($slider_min)); ?></span>
							<span data-price-max-label><?php echo esc_html($format_price_label($slider_max)); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<a href="<?php echo esc_url($clear_url); ?>" class="flex w-full items-center justify-center rounded-3xl border border-cynBorder px-3 py-3 text-sm font-medium text-cynTextPrimary/80 transition-all duration-300 hover:border-cynBorderHover hover:text-cynTextPrimary"><?php esc_html_e('پاک کردن فیلترها', 'novavilla'); ?></a>
	<button type="submit" class="md:hidden flex w-full items-center justify-center rounded-3xl bg-cynBorderHover px-3 py-3 text-sm font-medium text-cynBlack transition-all duration-300"><?php esc_html_e('اعمال فیلترها', 'novavilla'); ?></button>
</form>