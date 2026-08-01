<?php

use Cyan\Theme\Helpers\Icon;

$args = get_query_var('args', []);
$post_id = $args['post-id'] ?? get_the_ID();

if (empty($post_id)) {
	return;
}

if (is_object($post_id)) {
	$post_id = $post_id->ID ?? 0;
}

if (empty($post_id)) {
	return;
}

$variant = $args['variant'] ?? 'card';
$title = get_the_title($post_id);
$permalink = get_permalink($post_id);
$date = get_the_date('', $post_id);

$content = wp_strip_all_tags(get_post_field('post_content', $post_id));
$words = preg_split('/\s+/u', $content, -1, PREG_SPLIT_NO_EMPTY);
$reading_minutes = max(1, (int) ceil(count($words) / 200));
$reading_label = sprintf(
	/* translators: %d: estimated reading time in minutes */
	_n('%d دقیقه مطالعه', '%d دقیقه مطالعه', $reading_minutes, 'novavilla'),
	$reading_minutes
);

if ($variant === 'list') : ?>
	<a href="<?php echo esc_url($permalink); ?>" class="blog-card flex items-center gap-3 rounded-3xl border border-cynBorder hover:border-cynBorderHover transition-all duration-300 bg-cynBgItem backdrop-blur-xl overflow-hidden p-4 min-h-[152px]">
		<?php if (has_post_thumbnail($post_id)) : ?>
			<?php echo get_the_post_thumbnail($post_id, 'medium', ['class' => 'w-[169px] h-[120px] object-cover rounded-3xl shrink-0', 'alt' => esc_attr($title), 'loading' => 'lazy', 'decoding' => 'async']); ?>
		<?php else : ?>
			<div class="w-[169px] h-[120px] rounded-3xl bg-cynBgSocial/40 shrink-0" aria-hidden="true"></div>
		<?php endif; ?>
		<div class="flex flex-col gap-3 flex-1 min-w-0">
			<?php if (!empty($title)) : ?>
				<h3 class="text-base font-normal text-cynTextPrimary leading-5 line-clamp-2"><?php echo esc_html($title); ?></h3>
			<?php endif; ?>

			<div class="flex flex-wrap items-center gap-2.5 md:gap-6 text-[10px] md:text-xs font-light md:font-normal text-cynTextPrimary">
				<div class="flex items-center gap-1 whitespace-nowrap">
					<i class="size-3.5 flex items-center justify-center shrink-0 [&_svg]:size-full text-cynBorderHover [&_svg]:stroke-2">
						<?php Icon::print('Alarm,-Clock,-Time,-Timer-3'); ?>
					</i>
					<span><?php echo esc_html($reading_label); ?></span>
				</div>
				<div class="flex items-center gap-1 whitespace-nowrap">
					<i class="size-3.5 flex items-center justify-center shrink-0 [&_svg]:size-full text-cynBorderHover [&_svg]:stroke-2">
						<?php Icon::print('Calendar,-Dates,-Check-in,-Check-out'); ?>
					</i>
					<span><?php echo esc_html($date); ?></span>
				</div>
			</div>
		</div>
	</a>
<?php return;
endif; ?>

<a href="<?php echo esc_url($permalink); ?>" class="blog-card flex flex-col gap-3 rounded-3xl border border-cynBorder hover:border-cynBorderHover transition-all duration-300 bg-cynBgItem backdrop-blur-xl overflow-hidden h-full p-4">
	<?php if (has_post_thumbnail($post_id)) : ?>
		<?php echo get_the_post_thumbnail($post_id, 'large', ['class' => 'w-full h-[160px] md:h-[200px] object-cover rounded-3xl shrink-0', 'alt' => esc_attr($title), 'loading' => 'lazy', 'decoding' => 'async']); ?>
	<?php else : ?>
		<div class="w-full h-[160px] md:h-[200px] rounded-3xl bg-cynBgSocial/40 shrink-0" aria-hidden="true"></div>
	<?php endif; ?>

	<div class="flex flex-col gap-3 flex-1">
		<div class="flex flex-col gap-3.5">
			<?php if (!empty($title)) : ?>
				<h3 class="text-base font-normal text-cynTextPrimary leading-5 line-clamp-2"><?php echo esc_html($title); ?></h3>
			<?php endif; ?>

			<div class="flex items-center gap-6 text-xs font-light text-cynTextPrimary">
				<div class="flex items-center gap-1">
					<i class="size-3.5 flex items-center justify-center shrink-0 [&_svg]:size-full text-cynBorderHover [&_svg]:stroke-2">
						<?php Icon::print('Alarm,-Clock,-Time,-Timer-3'); ?>
					</i>
					<span><?php echo esc_html($reading_label); ?></span>
				</div>
				<div class="flex items-center gap-1">
					<i class="size-3.5 flex items-center justify-center shrink-0 [&_svg]:size-full text-cynBorderHover [&_svg]:stroke-2">
						<?php Icon::print('Calendar,-Dates,-Check-in,-Check-out'); ?>
					</i>
					<span><?php echo esc_html($date); ?></span>
				</div>

			</div>
		</div>

		<div class="mt-auto flex justify-end">
			<span class="primary-button btn-have-icon">
				<span class="flex items-center gap-1 whitespace-nowrap text-sm font-semibold bth-have-icon">
					<?php esc_html_e('مطالعه مقاله', 'novavilla'); ?>
					<i class="size-6 flex items-center justify-center [&_svg]:stroke-[1.5]"><?php Icon::print('Arrow-27'); ?></i>
				</span>
			</span>
		</div>
	</div>
</a>