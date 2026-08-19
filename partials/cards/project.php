<?php

use Cyan\Theme\Helpers\Icon;

$args = get_query_var('args', []);
$post_id = $args['post-id'] ?? get_the_ID();

if (empty($post_id)) {
	return;
}

$title = get_the_title($post_id);
$permalink = get_permalink($post_id);
$project_type = get_field('project_type', $post_id);
$project_location = get_field('project_location', $post_id);
$project_area = get_field('project_area', $post_id);
$project_floors = get_field('project_floors', $post_id);
$project_duration = get_field('project_duration', $post_id);
$project_year = get_field('project_year', $post_id);

$project_specs = array_filter([
	['label' => __('نوع پروژه', 'novavilla'), 'value' => $project_type],
	['label' => __('محل اجرا', 'novavilla'), 'value' => $project_location],
	['label' => __('متراژ', 'novavilla'), 'value' => $project_area],
	['label' => __('تعداد طبقات', 'novavilla'), 'value' => $project_floors],
	['label' => __('مدت زمان اجرا', 'novavilla'), 'value' => $project_duration],
	['label' => __('سال اجرا', 'novavilla'), 'value' => $project_year],
], static fn($item) => $item['value'] !== null && $item['value'] !== '');
?>

<a href="<?php echo esc_url($permalink); ?>" class="group flex flex-col h-full rounded-3xl overflow-hidden border dark:border-transparent border-cynBorder hover:border-cynBorderHover transition-all duration-300">
	<div class="w-full h-[276px] 2xl:h-80 shrink-0 overflow-hidden">
		<?php if (has_post_thumbnail($post_id)) : ?>
			<?php echo get_the_post_thumbnail($post_id, 'medium_large', ['class' => 'w-full h-full object-cover', 'alt' => esc_attr($title), 'loading' => 'lazy', 'decoding' => 'async']); ?>
		<?php else : ?>
			<div class="w-full h-full bg-cynBgSocial/40" aria-hidden="true"></div>
		<?php endif; ?>
	</div>

	<div class="flex flex-1 flex-col bg-cynWhite/8 border-t dark:border-transparent border-cynBorder group-hover:border-t-cynBorderHover transition-all duration-300">
		<div class="flex flex-1 flex-col gap-1.5 px-3 py-4 md:py-2">
			<h3 class="text-base font-bold text-cynTextPrimary leading-6 line-clamp-1 min-h-6">
				<?php echo esc_html($title); ?>
			</h3>

			<?php if (!empty($project_specs)) : ?>
				<div class="flex flex-col gap-0.5 text-xs font-noraml text-cynTextPrimary leading-5">
					<?php foreach ($project_specs as $project_spec) : ?>
						<p>
							<?php echo esc_html($project_spec['label'] . ': ' . $project_spec['value']); ?>
						</p>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<span class="primary-button btn-have-icon mt-auto w-full justify-center !rounded-lg !py-2">
				<span class="flex items-center gap-1 whitespace-nowrap text-sm font-medium md:text-base md:font-semibold">
					<?php esc_html_e('مشاهده جزئیات پروژه', 'novavilla'); ?>
					<i class="size-5 flex items-center justify-center [&_svg]:stroke-[1.5]">
						<?php Icon::print('Arrow-27'); ?>
					</i>
				</span>
			</span>
		</div>
	</div>
</a>
