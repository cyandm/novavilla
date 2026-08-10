<?php

$args = get_query_var('args', []);
$post_id = $args['post-id'] ?? get_the_ID();

if (empty($post_id)) {
	return;
}

$title = get_the_title($post_id);
$permalink = get_permalink($post_id);
?>

<a href="<?php echo esc_url($permalink); ?>" class="flex flex-col gap-3 rounded-[20px] border border-cynBorder bg-cynBgItem backdrop-blur-2xl p-4 min-h-[102px] max-lg:min-h-[134px] hover:border-cynBorderHover transition-all duration-300 no-underline">
	<?php if (!empty($title)) : ?>
		<h3 class="text-base font-normal text-cynTextPrimary leading-8 line-clamp-2 max-lg:line-clamp-4"><?php echo esc_html($title); ?></h3>
	<?php endif; ?>
	<span class="text-base font-normal text-cynTextPrimary/60"><?php esc_html_e('مقالات', 'novavilla'); ?></span>
</a>
