<?php

use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;

$archive_url = get_post_type_archive_link('project') ?: home_url('/');
$current_pcat = isset($_GET['pcat']) ? sanitize_title(wp_unslash($_GET['pcat'])) : 'all';
$current_pstate = isset($_GET['pstate']) ? sanitize_title(wp_unslash($_GET['pstate'])) : 'all';
$current_city = isset($_GET['city']) ? sanitize_text_field(wp_unslash($_GET['city'])) : '';
$project_cats = get_terms(['taxonomy' => 'project_cat', 'hide_empty' => false]);
$project_states = get_terms(['taxonomy' => 'project_state', 'hide_empty' => false]);
if (is_wp_error($project_cats)) $project_cats = [];
if (is_wp_error($project_states)) $project_states = [];

$accordion_panel_class = 'rounded-3xl border border-cynBorderHover/40 bg-cynBgItem/80 backdrop-blur-2xl p-4 flex flex-col hover:border-cynBorderHover transition-all duration-300';
$accordion_content_class = 'grid transition-[grid-template-rows] duration-300 ease-out !mt-0';
$chip_class = 'flex h-10 w-full items-center rounded-xl px-3 text-sm font-medium transition-all duration-300 cursor-pointer has-[:checked]:bg-cynBorderHover has-[:checked]:text-cynBlack dark:bg-cynBlack bg-[#515151] text-cynWhite';
?>

<form id="project-filter-form" method="get" action="<?php echo esc_url($archive_url); ?>" class="flex flex-col gap-3">
	<div class="<?php echo esc_attr($accordion_panel_class); ?>">
		<button type="button" class="accordion-button flex w-full items-center justify-between gap-2 cursor-pointer bg-transparent border-none p-0 text-start" data-accordion-target="project-filter-cat" data-accordion-icon-rotate="180" aria-expanded="true" aria-controls="project-filter-cat">
			<span class="text-base font-medium text-cynTextPrimary">
				<?php esc_html_e('دسته بندی اصلی', 'novavilla'); ?>
			</span>
			<i class="accordion-icon size-6 shrink-0 text-cynBorderHover transition-transform duration-300 [&_svg]:stroke-[1.5]" style="transform: rotate(180deg);">
				<?php Icon::print('Arrow-28'); ?>
			</i>
		</button>
		<div id="project-filter-cat" class="<?php echo esc_attr($accordion_content_class); ?>" data-accordion-content="project-filter-cat" style="grid-template-rows: 1fr;">
			<div class="min-h-0 overflow-hidden">
				<div class="flex flex-col gap-3 pt-3">
					<label for="project-cat-all" class="<?php echo esc_attr($chip_class); ?>">
						<input class="hidden" type="radio" name="pcat" id="project-cat-all" value="all" <?php checked($current_pcat, 'all'); ?>>
						<span>
							<?php esc_html_e('همه پروژه‌ها', 'novavilla'); ?>
						</span>
					</label>
					<?php foreach ($project_cats as $project_cat) : ?>
						<label for="<?php echo esc_attr('project-cat-' . $project_cat->slug); ?>" class="<?php echo esc_attr($chip_class); ?>">
							<input class="hidden" type="radio" name="pcat" id="<?php echo esc_attr('project-cat-' . $project_cat->slug); ?>" value="<?php echo esc_attr($project_cat->slug); ?>" <?php checked($current_pcat, $project_cat->slug); ?>>
							<span>
								<?php echo esc_html($project_cat->name); ?>
							</span>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="<?php echo esc_attr($accordion_panel_class); ?>">
		<button type="button" class="accordion-button flex w-full items-center justify-between gap-2 cursor-pointer bg-transparent border-none p-0 text-start" data-accordion-target="project-filter-state" data-accordion-icon-rotate="180" aria-expanded="true" aria-controls="project-filter-state">
			<span class="text-base font-medium text-cynTextPrimary">
				<?php esc_html_e('وضعیت پروژه', 'novavilla'); ?>
			</span>
			<i class="accordion-icon size-6 shrink-0 text-cynBorderHover transition-transform duration-300 [&_svg]:stroke-[1.5]" style="transform: rotate(180deg);">
				<?php Icon::print('Arrow-28'); ?>
			</i>
		</button>
		<div id="project-filter-state" class="<?php echo esc_attr($accordion_content_class); ?>" data-accordion-content="project-filter-state" style="grid-template-rows: 1fr;">
			<div class="min-h-0 overflow-hidden">
				<div class="flex flex-col gap-3 pt-3">
					<label for="project-state-all" class="<?php echo esc_attr($chip_class); ?>">
						<input class="hidden" type="radio" name="pstate" id="project-state-all" value="all" <?php checked($current_pstate, 'all'); ?>>
						<span>
							<?php esc_html_e('همه', 'novavilla'); ?>
						</span>
					</label>
					<?php foreach ($project_states as $project_state) : ?>
						<label for="<?php echo esc_attr('project-state-' . $project_state->slug); ?>" class="<?php echo esc_attr($chip_class); ?>">
							<input class="hidden" type="radio" name="pstate" id="<?php echo esc_attr('project-state-' . $project_state->slug); ?>" value="<?php echo esc_attr($project_state->slug); ?>" <?php checked($current_pstate, $project_state->slug); ?>>
							<span>
								<?php echo esc_html($project_state->name); ?>
							</span>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="<?php echo esc_attr($accordion_panel_class); ?>">
		<span class="text-base font-medium text-cynTextPrimary">
			<?php esc_html_e('شهر', 'novavilla'); ?>
		</span>
		<input type="search" name="city" value="<?php echo esc_attr($current_city); ?>" placeholder="<?php esc_attr_e('جستجوی شهر', 'novavilla'); ?>" class="mt-3 w-full rounded-xl border border-cynBorderHover/40 bg-cynWhite/8 px-3 py-3 text-xs font-light text-cynTextPrimary placeholder:text-cynTextPrimary/60 outline-none transition-all duration-300 focus:border-cynBorderHover" />
		<p class="hidden md:block mt-2 text-xs font-light text-cynTextPrimary/60">
			<?php esc_html_e('بعد از نوشتن نام شهر، دکمه Enter کیبورد را فشار دهید.', 'novavilla'); ?>
		</p>
	</div>

	<a href="<?php echo esc_url($archive_url); ?>" class="flex w-full items-center justify-center rounded-3xl border px-3 py-3 text-sm font-medium text-cynTextPrimary border-cynBorderHover/40 bg-cynBgItem/80 backdrop-blur-2xl hover:border-cynBorderHover transition-all duration-300">
		<?php esc_html_e('پاک کردن فیلترها', 'novavilla'); ?>
	</a>
	<button type="submit" class="md:hidden flex w-full items-center justify-center rounded-3xl bg-cynBorderHover px-3 py-3 text-sm font-medium text-cynBlack transition-all duration-300">
		<?php esc_html_e('اعمال فیلترها', 'novavilla'); ?>
	</button>
</form>
