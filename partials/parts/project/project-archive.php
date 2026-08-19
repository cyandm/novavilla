<?php

use Cyan\Theme\Classes\ProjectFilter;
use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

defined('ABSPATH') || exit;

global $wp_query;

$home_id = (int) get_option('page_on_front');
$project_archive_hero_title = get_field('project_archive_hero_title', $home_id);
$project_archive_hero_title_colored = get_field('project_archive_hero_title_colored', $home_id);
$project_archive_hero_description = get_field('project_archive_hero_description', $home_id);
$project_archive_hero_image = get_field('project_archive_hero_image', $home_id);
$project_archive_hero_image_url = is_array($project_archive_hero_image) ? ($project_archive_hero_image['url'] ?? '') : $project_archive_hero_image;
$project_archive_hero_title = $project_archive_hero_title ?: __('پروژه‌هایی که', 'novavilla');
$project_archive_hero_title_colored = $project_archive_hero_title_colored ?: __('از ایده به واقعیت رسیدند', 'novavilla');
$project_archive_hero_alt = trim($project_archive_hero_title . ' ' . $project_archive_hero_title_colored);

$project_archive_stats = [];
for ($i = 1; $i <= 4; $i++) {
	$project_archive_stat_title = get_field("project_archive_stat_title_{$i}", $home_id);
	$project_archive_stat_number = get_field("project_archive_stat_number_{$i}", $home_id);
	$project_archive_stat_icon = get_field("project_archive_stat_icon_{$i}", $home_id);
	$project_archive_stat_number = is_numeric($project_archive_stat_number) ? (int) $project_archive_stat_number : null;
	if (empty($project_archive_stat_title) && $project_archive_stat_number === null && empty($project_archive_stat_icon)) continue;
	$project_archive_stats[] = [
		'title' => $project_archive_stat_title,
		'number' => $project_archive_stat_number,
		'icon' => $project_archive_stat_icon,
	];
}
?>

<section class="flex flex-col gap-3">
	<div class="flex flex-col-reverse lg:flex-row lg:items-center gap-3 lg:gap-7">
		<div class="w-full lg:w-1/2 flex flex-col gap-3">
			<h1 class="text-2xl md:text-4xl font-bold leading-9 md:leading-14">
				<?php if (!empty($project_archive_hero_title)) : ?>
					<span class="block text-cynTextPrimary">
						<?php echo esc_html($project_archive_hero_title); ?>
					</span>
				<?php endif; ?>
				<?php if (!empty($project_archive_hero_title_colored)) : ?>
					<span class="block text-cynBorderHover">
						<?php echo esc_html($project_archive_hero_title_colored); ?>
					</span>
				<?php endif; ?>
			</h1>
			<?php if (!empty($project_archive_hero_description)) : ?>
				<p class="text-sm md:text-base font-light text-cynTextPrimary leading-5 md:leading-7">
					<?php echo esc_html($project_archive_hero_description); ?>
				</p>
			<?php endif; ?>
		</div>

		<?php if (!empty($project_archive_hero_image_url)) : ?>
			<div class="w-full lg:w-1/2 shrink-0">
				<img src="<?php echo esc_url($project_archive_hero_image_url); ?>" alt="<?php echo esc_attr($project_archive_hero_alt); ?>" class="w-full h-40 lg:h-64 object-contain drop-shadow-[-10px_6px_26px_rgba(0,0,0,0.2)]" loading="eager" decoding="async" />
			</div>
		<?php endif; ?>
	</div>

	<?php if (!empty($project_archive_stats)) : ?>
		<div class="grid grid-cols-2 lg:grid-cols-4 gap-2 lg:gap-3">
			<?php foreach ($project_archive_stats as $project_archive_stat) : ?>
				<div class="flex items-center justify-center gap-3 lg:gap-5 rounded-3xl border border-cynBorderHover/40 hover:border-cynBorderHover transition-all duration-300 bg-cynWhite/10 backdrop-blur-xl px-10 py-6">
					<div class="flex flex-col gap-1">
						<?php if ($project_archive_stat['number'] !== null) : ?>
							<span class="text-lg md:text-4xl font-bold text-cynBorderHover leading-5 md:leading-10 text-end" dir="ltr" data-stat-count="<?php echo esc_attr($project_archive_stat['number']); ?>">+0</span>
						<?php endif; ?>
						<?php if (!empty($project_archive_stat['title'])) : ?>
							<span class="text-xs md:text-xl font-medium text-cynTextPrimary leading-4 md:leading-6">
								<?php echo esc_html($project_archive_stat['title']); ?>
							</span>
						<?php endif; ?>
					</div>
					<?php if (!empty($project_archive_stat['icon'])) : ?>
						<i class="size-8 lg:size-12 shrink-0 flex items-center justify-center text-cynBorderHover [&_svg]:size-full [&_svg]:stroke-[1.5]">
							<?php Icon::print($project_archive_stat['icon']); ?>
						</i>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>

<section class="flex flex-col gap-5 md:gap-4">
	<button type="button" modal-opener data-modal-name="project-filter-modal" class="md:hidden w-fit flex items-center gap-1 rounded-xl border border-cynBorderHover/40 bg-cynWhite/10 px-3 py-3 text-sm font-medium text-cynTextPrimary transition-all duration-300">
		<span>
			<?php esc_html_e('نمایش فیلتر ها', 'novavilla'); ?>
		</span>
		<i class="size-5 flex items-center justify-center text-cynBorderHover [&_svg]:stroke-[1]">
			<?php Icon::print('Filter,-Sort-1'); ?>
		</i>
	</button>

	<div class="flex flex-col md:grid md:grid-cols-4 gap-4">
		<aside
			id="project-filter-panel"
			modal
			data-modal-name="project-filter-modal"
			data-modal-layer="popup"
			data-active="false"
			aria-label="<?php esc_attr_e('فیلتر پروژه‌ها', 'novavilla'); ?>"
			class="fixed inset-x-3 top-1/2 z-50 max-h-[calc(100dvh-3rem)] -translate-y-1/2 overflow-y-auto scrollbar rounded-3xl border border-cynBorderHover/40 bg-cynWhite/10 backdrop-blur-md p-4 flex flex-col gap-5 opacity-0 pointer-events-none invisible data-[active='true']:opacity-100 data-[active='true']:pointer-events-auto data-[active='true']:visible transition-all duration-300 md:static md:inset-auto md:z-auto md:max-h-none md:translate-y-0 md:overflow-visible md:rounded-none md:border-0 md:bg-transparent md:backdrop-blur-none md:p-0 md:opacity-100 md:pointer-events-auto md:visible md:col-span-1">
			<div class="flex items-center justify-between gap-3 md:hidden">
				<span class="text-lg font-medium text-cynTextPrimary">
					<?php esc_html_e('فیلترها', 'novavilla'); ?>
				</span>
				<button type="button" modal-closer data-modal-name="project-filter-modal" class="flex size-10 shrink-0 items-center justify-center rounded-full bg-cynBorderHover text-cynWhite dark:text-cynBlack" aria-label="<?php esc_attr_e('بستن', 'novavilla'); ?>">
					<i class="size-5 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5] rotate-45">
						<?php Icon::print('plus'); ?>
					</i>
				</button>
			</div>

			<?php Templates::getPart('project/project-filter'); ?>
		</aside>

		<div class="flex flex-col gap-5 md:col-span-3">
			<?php if (have_posts()) : ?>
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
					<?php while (have_posts()) : the_post(); ?>
						<?php Templates::getCard('project', ['post-id' => get_the_ID()]); ?>
					<?php endwhile; ?>
				</div>

				<?php Templates::getPart('pagination', [
					'total' => (int) $wp_query->max_num_pages,
					'current' => max(1, (int) get_query_var('paged')),
					'base_url' => get_post_type_archive_link('project'),
					'query_args' => ProjectFilter::currentQueryArgs(),
					'aria_label' => __('صفحه‌بندی پروژه‌ها', 'novavilla'),
				]); ?>
			<?php else : ?>
				<div class="flex flex-col items-center justify-center gap-3 rounded-3xl border border-cynBorder bg-cynBgItem backdrop-blur-2xl p-8 text-center">
					<p class="text-lg font-medium text-cynTextPrimary">
						<?php esc_html_e('پروژه‌ای یافت نشد', 'novavilla'); ?>
					</p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>