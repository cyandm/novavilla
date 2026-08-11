<?php

$args = get_query_var('args', []);
$post_id = $args['post-id'] ?? get_the_ID();

if (empty($post_id)) {
	return;
}

$title = get_the_title($post_id);
$permalink = get_permalink($post_id);
$price_raw = get_field('product_price', $post_id);
$price_html = '';

if ($price_raw !== null && $price_raw !== '') {
	$price_number = is_numeric($price_raw) ? number_format_i18n((float) $price_raw) : $price_raw;
	$price_html = $price_number . ' ' . __('تومان', 'novavilla');
}
?>

<a href="<?php echo esc_url($permalink); ?>" class="flex gap-3 rounded-3xl border border-cynBorder bg-cynBgItem backdrop-blur-2xl p-4 hover:border-cynBorderHover transition-all duration-300">
	<div class="shrink-0">
		<?php if (has_post_thumbnail($post_id)) : ?>
			<?php echo get_the_post_thumbnail($post_id, 'medium', ['class' => 'w-[169px] h-[120px] object-cover rounded-3xl', 'alt' => esc_attr($title), 'loading' => 'lazy', 'decoding' => 'async']); ?>
		<?php else : ?>
			<div class="w-[169px] h-[120px] rounded-3xl bg-cynBgSocial/40" aria-hidden="true"></div>
		<?php endif; ?>
	</div>
	<div class="flex flex-col flex-1 gap-3 min-w-0">
		<div class="flex flex-col gap-2">
			<?php if (!empty($title)) : ?>
				<h3 class="text-base font-normal text-cynTextPrimary leading-6 line-clamp-2"><?php echo esc_html($title); ?></h3>
			<?php endif; ?>
			<?php if (!empty($price_html)) : ?>
				<div class="text-sm font-normal text-cynTextPrimary"><?php echo esc_html($price_html); ?></div>
			<?php endif; ?>
		</div>
		<span class="text-base font-normal text-cynTextPrimary/60"><?php esc_html_e('محصولات', 'novavilla'); ?></span>
	</div>
</a>
