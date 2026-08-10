<?php

$args = get_query_var('args', []);
$post_id = $args['post-id'] ?? get_the_ID();

if (empty($post_id)) {
	return;
}

$title = get_the_title($post_id);
$permalink = get_permalink($post_id);
$price_html = '';

if (function_exists('wc_get_product')) {
	$product = wc_get_product($post_id);
	if ($product) {
		$price_html = $product->get_price_html();
	}
}
?>

<a href="<?php echo esc_url($permalink); ?>" class="flex gap-3 rounded-[20px] border border-cynBorder bg-cynBgItem backdrop-blur-2xl p-4 min-h-[152px] hover:border-cynBorderHover transition-all duration-300 no-underline">
	<div class="flex flex-col flex-1 gap-3 min-w-0">
		<div class="flex flex-col gap-2">
			<?php if (!empty($title)) : ?>
				<h3 class="text-base font-normal text-cynTextPrimary leading-[22px] line-clamp-2"><?php echo esc_html($title); ?></h3>
			<?php endif; ?>
			<?php if (!empty($price_html)) : ?>
				<div class="text-base font-normal text-cynTextPrimary [&_bdi]:font-normal"><?php echo wp_kses_post($price_html); ?></div>
			<?php endif; ?>
		</div>
		<span class="text-base font-normal text-cynTextPrimary/60"><?php esc_html_e('محصولات', 'novavilla'); ?></span>
	</div>
	<div class="shrink-0">
		<?php if (has_post_thumbnail($post_id)) : ?>
			<?php echo get_the_post_thumbnail($post_id, 'medium', ['class' => 'w-[169px] h-[120px] object-cover rounded-[20px]', 'alt' => esc_attr($title), 'loading' => 'lazy', 'decoding' => 'async']); ?>
		<?php else : ?>
			<div class="w-[169px] h-[120px] rounded-[20px] bg-cynBgSocial/40" aria-hidden="true"></div>
		<?php endif; ?>
	</div>
</a>
