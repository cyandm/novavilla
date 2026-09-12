<?php
$title = get_field('ads_visit_title');
$desc = get_field('ads_visit_desc');
$listTitle = get_field('ads_visit_list_title');
$listText = get_field('ads_visit_list');
$button = get_field('ads_visit_button');
$image = get_field('ads_visit_image');
$listLines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $listText) ?: []));
if (!$title && !$desc && !$image) return;
?>
<section class="flex flex-col gap-5">
	<div class="flex flex-col gap-4">
		<?php if ($title): ?>
			<h2 class="text-3xl md:text-4xl font-medium text-cynTextPrimary">
				<?php echo esc_html($title); ?>
			</h2>
		<?php endif; ?>
		<?php if ($desc): ?>
			<p class="text-lg md:text-xl font-normal text-cynTextPrimary leading-6">
				<?php echo esc_html($desc); ?>
			</p>
		<?php endif; ?>
	</div>
	<div class="flex flex-col gap-5 lg:flex-row lg:items-center">
		<?php if ($image): ?>
			<div class="w-full lg:w-[623px] shrink-0">
				<img src="<?php echo esc_url($image); ?>" alt="" class="w-full h-72 lg:h-96 object-cover rounded-3xl" />
			</div>
		<?php endif; ?>
		<div class="flex flex-col gap-4 lg:max-w-md">
			<?php if ($listTitle): ?>
				<h3 class="text-xl md:text-2xl font-bold text-cynTextPrimary">
					<?php echo esc_html($listTitle); ?>
				</h3>
			<?php endif; ?>
			<?php if (!empty($listLines)): ?>
				<ul class="flex flex-col gap-2 text-lg md:text-xl font-medium text-cynTextPrimary leading-10 list-disc ps-8">
					<?php foreach ($listLines as $line): ?>
						<li>
							<?php echo esc_html($line); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if (!empty($button['url'])): ?>
				<a href="<?php echo esc_url($button['url']); ?>" <?php echo !empty($button['target']) ? 'target="' . esc_attr($button['target']) . '"' : ''; ?> class="primary-button self-start">
					<span>
						<?php echo esc_html($button['title'] ?: __('هماهنگی بازدید حضوری', 'novavilla')); ?>
					</span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>