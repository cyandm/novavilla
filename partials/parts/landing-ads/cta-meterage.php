<?php
$title = get_field('ads_meterage_cta_title');
$button = get_field('ads_meterage_cta_button');
if (!$title && empty($button['url'])) return;
?>
<section class="rounded-3xl bg-blue-600 p-5 md:p-6">
	<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
		<?php if ($title): ?>
			<h2 class="text-2xl md:text-3xl font-bold text-white">
				<?php echo esc_html($title); ?>
			</h2>
		<?php endif; ?>
		<?php if (!empty($button['url'])): ?>
			<a href="<?php echo esc_url($button['url']); ?>" <?php echo !empty($button['target']) ? 'target="' . esc_attr($button['target']) . '"' : ''; ?> class="inline-flex items-center justify-center rounded-[10px] bg-white px-5 py-2 text-sm font-semibold text-black transition-all duration-300 hover:bg-white/90 shrink-0">
				<span>
					<?php echo esc_html($button['title'] ?: __('استعلام متراژ مورد نظر', 'novavilla')); ?>
				</span>
			</a>
		<?php endif; ?>
	</div>
</section>