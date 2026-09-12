<?php

use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

$title = get_field('ads_bottom_cta_title');
$subtitle = get_field('ads_bottom_cta_subtitle');
$primary = get_field('ads_bottom_cta_primary');
$secondary = get_field('ads_bottom_cta_secondary');
if (!$title && !$subtitle && empty($secondary['url'])) return;

$primary_label = !empty($primary['title']) ? $primary['title'] : __('میخواهم با من تماس بگیرید', 'novavilla');
$page_id = get_queried_object_id();
?>
<section class="rounded-3xl bg-gradient-to-l from-teal-700 to-blue-500 p-5 md:p-6">
	<div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
		<div class="flex flex-col gap-2.5">
			<?php if ($title): ?>
				<h2 class="text-2xl md:text-3xl font-bold text-white">
					<?php echo esc_html($title); ?>
				</h2>
			<?php endif; ?>
			<?php if ($subtitle): ?>
				<p class="text-base font-bold text-white">
					<?php echo esc_html($subtitle); ?>
				</p>
			<?php endif; ?>
		</div>
		<div class="flex flex-wrap gap-3">
			<button type="button" modal-opener data-modal-name="product-consult" class="inline-flex items-center justify-center rounded-[10px] bg-white px-5 py-2 text-sm font-semibold text-black transition-all duration-300 hover:bg-white/90">
				<span>
					<?php echo esc_html($primary_label); ?>
				</span>
			</button>
			<?php if (!empty($secondary['url'])): ?>
				<a href="<?php echo esc_url($secondary['url']); ?>" <?php echo !empty($secondary['target']) ? 'target="' . esc_attr($secondary['target']) . '"' : ''; ?> class="inline-flex items-center justify-center rounded-[10px] bg-black px-5 py-2 text-sm font-semibold text-white transition-all duration-300 hover:bg-black/80">
					<span>
						<?php echo esc_html($secondary['title'] ?: __('تماس با دفتر فروش', 'novavilla')); ?>
					</span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>

<section modal data-modal-name="product-consult" data-modal-layer="popup" data-active="false" class="fixed inset-x-3 md:inset-x-auto md:left-1/2 top-1/2 z-50 w-auto md:w-[min(800px,calc(100%-3rem))] md:-translate-x-1/2 max-h-[calc(100dvh-3rem)] -translate-y-1/2 overflow-y-auto scrollbar flex flex-col gap-5 opacity-0 pointer-events-none invisible data-[active='true']:opacity-100 data-[active='true']:pointer-events-auto data-[active='true']:visible transition-all duration-300">
	<div id="product-consult-success" class="empty:hidden"></div>
	<div class="rounded-3xl border border-cynBorderHover/40 bg-cynWhite/10 backdrop-blur-md p-4 flex flex-col gap-5 transition-all duration-300 hover:border-cynBorderHover">
		<div class="flex items-center justify-between gap-2">
			<span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6">
				<?php esc_html_e('درخواست مشاوره', 'novavilla'); ?>
			</span>
			<i class="size-7 rounded-full border border-cynBorder dark:border-white/40 hover:border-cynBorderHover dark:hover:border-cynWhite transition-all duration-300 bg-cynWhite/8 flex items-center justify-center text-cynTextPrimary dark:text-cynWhite [&_svg]:stroke-[1.5] cursor-pointer" modal-closer data-modal-name="product-consult">
				<?php Icon::print('Delete,-Disabled'); ?>
			</i>
		</div>
		<?php Templates::getPart('product/product-consult-form', ['source_page_id' => $page_id]); ?>
	</div>
</section>
