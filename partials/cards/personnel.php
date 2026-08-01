<?php

$args = get_query_var('args', []);
$post_id = $args['post-id'] ?? get_the_ID();

if (empty($post_id)) {
	return;
}

$title = get_the_title($post_id);
$role = wp_strip_all_tags(get_post_field('post_content', $post_id));
?>

<article class="flex flex-col gap-3 rounded-[20px] border border-cynBorder hover:border-cynBorderHover transition-all duration-300 bg-cynBgItem backdrop-blur-xl overflow-hidden h-full p-4">
	<div class="relative flex items-center justify-center mx-auto size-[121px] lg:size-[134px]">
		<span class="pointer-events-none absolute size-[78px] rounded-full bg-[#fbb963] blur-[50px] opacity-80" aria-hidden="true"></span>
		<div class="relative z-10 size-full rounded-full overflow-hidden border-[2px] border-[#ffc291] bg-[#d9d9d9]">
			<?php if (has_post_thumbnail($post_id)) : ?>
				<?php echo get_the_post_thumbnail($post_id, 'medium', ['class' => 'size-full object-cover', 'alt' => esc_attr($title), 'loading' => 'lazy', 'decoding' => 'async']); ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="flex flex-col gap-1 text-center">
		<?php if (!empty($title)) : ?>
			<h3 class="text-sm font-medium text-cynTextPrimary leading-6"><?php echo esc_html($title); ?></h3>
		<?php endif; ?>

		<?php if (!empty($role)) : ?>
			<p class="text-xs font-normal text-cynTextPrimary leading-4"><?php echo esc_html($role); ?></p>
		<?php endif; ?>
	</div>
</article>