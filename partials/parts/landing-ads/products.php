<?php

use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

$title = get_field('ads_products_title');
$note = get_field('ads_products_note');
$per_page = 8;
$area_filters = [
	['key' => 'all', 'label' => __('همه', 'novavilla')],
	['key' => 'lte40', 'label' => __('تا ۴۰ متر', 'novavilla')],
	['key' => '41-80', 'label' => __('۴۱ تا ۸۰ متر', 'novavilla')],
	['key' => 'gt80', 'label' => __('بیش از ۸۰ متر', 'novavilla')],
];
$rooms_filters = [
	['key' => 'all', 'label' => __('همه', 'novavilla')],
	['key' => 'rooms1', 'label' => __('۱ خواب', 'novavilla')],
	['key' => 'rooms2', 'label' => __('۲ خواب', 'novavilla')],
	['key' => 'rooms3plus', 'label' => __('۳ خواب یا بیشتر', 'novavilla')],
];
$btn_base = 'ads-filter-btn rounded-md px-3 py-1 text-base font-normal transition-all duration-300 outline outline-1 outline-offset-[-1px] outline-cynBorderHover/20';
$btn_active = 'is-active bg-cynBorderHover text-black';
$btn_idle = 'bg-white/10 text-cynTextPrimary';
$modal_name = 'ads-products-filter';

$product_ids = get_posts([
	'post_type' => 'product',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'fields' => 'ids',
	'no_found_rows' => true,
]);
usort($product_ids, static function ($a, $b) {
	return ((float) get_post_meta($a, 'product_price', true)) <=> ((float) get_post_meta($b, 'product_price', true));
});
$total = count($product_ids);
?>
<section class="flex flex-col gap-5" data-ads-products data-ads-per-page="<?php echo esc_attr((string) $per_page); ?>">
	<div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
		<?php if ($title): ?>
			<h2 class="text-2xl md:text-3xl font-bold text-cynTextPrimary">
				<?php echo esc_html($title); ?>
			</h2>
		<?php endif; ?>
		<div class="flex flex-wrap items-center gap-2">
			<div class="flex flex-wrap gap-1.5" data-ads-filters data-ads-filter-group="area">
				<?php foreach ($area_filters as $i => $filter): ?>
					<button type="button" data-ads-filter="<?php echo esc_attr($filter['key']); ?>" class="<?php echo esc_attr($btn_base . ' ' . ($i === 0 ? $btn_active : $btn_idle)); ?>">
						<span>
							<?php echo esc_html($filter['label']); ?>
						</span>
					</button>
				<?php endforeach; ?>
			</div>
			<button type="button" modal-opener data-modal-name="<?php echo esc_attr($modal_name); ?>" data-ads-more-filters class="inline-flex items-center gap-1.5 rounded-md px-3 py-1 text-base font-normal text-cynTextPrimary bg-white/10 outline outline-1 outline-offset-[-1px] outline-cynBorderHover/20 transition-all duration-300">
				<span class="hidden size-1.5 rounded-full bg-cynBorderHover" data-ads-filter-dot aria-hidden="true"></span>
				<i class="size-4 flex items-center justify-center text-cynBorderHover [&_svg]:size-full [&_svg]:stroke-[1.5]">
					<?php Icon::print('Filter,-Sort-1'); ?>
				</i>
				<span>
					<?php esc_html_e('فیلتر بیشتر', 'novavilla'); ?>
				</span>
			</button>
		</div>
	</div>

	<?php if ($total > 0): ?>
		<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 overflow-visible transition-[opacity,transform] duration-300 ease-out" data-ads-grid>
			<?php foreach ($product_ids as $i => $product_id): ?>
				<?php $is_pending = $i >= $per_page; ?>
				<div class="relative z-0 w-full overflow-visible transition-[opacity,transform] duration-500 ease-out has-[[data-ads-card][data-open=true]]:z-[60] <?php echo $is_pending ? 'hidden opacity-0 translate-y-3' : 'opacity-100 translate-y-0'; ?>" data-ads-item>
					<?php Templates::getCard('product-ads', ['post-id' => (int) $product_id]); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="hidden opacity-0 text-center text-base md:text-lg font-medium text-cynTextPrimary rounded-3xl border border-cynBorderHover/40 bg-cynWhite/10 backdrop-blur-md px-4 py-6 md:py-8 transition-opacity duration-300" data-ads-empty>
			<?php esc_html_e('محصول مدنظر شما یافت نشد', 'novavilla'); ?>
		</p>
		<div class="flex justify-center <?php echo $total > $per_page ? '' : 'hidden'; ?>" data-ads-more-wrap>
			<button type="button" class="primary-button" data-ads-more>
				<span>
					<?php esc_html_e('مشاهده بیشتر', 'novavilla'); ?>
				</span>
			</button>
		</div>
	<?php endif; ?>

	<?php if ($note): ?>
		<p class="text-lg md:text-xl font-bold text-cynTextPrimary mt-6">
			<?php echo esc_html($note); ?>
		</p>
	<?php endif; ?>

	<div data-ads-card-backdrop class="fixed inset-0 z-40 bg-black/60 dark:bg-black/70 backdrop-blur-md opacity-0 pointer-events-none invisible transition-all duration-300 data-[active=true]:opacity-100 data-[active=true]:pointer-events-auto data-[active=true]:visible"></div>
</section>

<section modal data-modal-name="<?php echo esc_attr($modal_name); ?>" data-modal-layer="popup" data-active="false" data-ads-filter-modal class="fixed inset-x-3 md:inset-x-auto md:left-1/2 top-1/2 z-50 w-auto md:w-[min(480px,calc(100%-3rem))] md:-translate-x-1/2 max-h-[calc(100dvh-3rem)] -translate-y-1/2 overflow-y-auto scrollbar flex flex-col gap-5 opacity-0 pointer-events-none invisible data-[active='true']:opacity-100 data-[active='true']:pointer-events-auto data-[active='true']:visible transition-all duration-300">
	<div class="rounded-3xl border border-cynBorderHover/40 bg-cynWhite/10 backdrop-blur-md p-4 flex flex-col gap-5 transition-all duration-300 hover:border-cynBorderHover">
		<div class="flex items-center justify-between gap-2">
			<span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6">
				<?php esc_html_e('فیلتر محصولات', 'novavilla'); ?>
			</span>
			<i class="size-7 rounded-full border border-cynBorder dark:border-white/40 hover:border-cynBorderHover dark:hover:border-cynWhite transition-all duration-300 bg-cynWhite/8 flex items-center justify-center text-cynTextPrimary dark:text-cynWhite [&_svg]:stroke-[1.5] cursor-pointer" modal-closer data-modal-name="<?php echo esc_attr($modal_name); ?>">
				<?php Icon::print('Delete,-Disabled'); ?>
			</i>
		</div>

		<div class="flex flex-col gap-4">
			<div class="flex flex-col gap-2">
				<span class="text-sm font-medium text-cynTextMuted">
					<?php esc_html_e('متراژ', 'novavilla'); ?>
				</span>
				<div class="flex flex-wrap gap-1.5" data-ads-filters data-ads-filter-group="area">
					<?php foreach ($area_filters as $i => $filter): ?>
						<button type="button" data-ads-filter="<?php echo esc_attr($filter['key']); ?>" class="<?php echo esc_attr($btn_base . ' ' . ($i === 0 ? $btn_active : $btn_idle)); ?>">
							<span>
								<?php echo esc_html($filter['label']); ?>
							</span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="flex flex-col gap-2">
				<span class="text-sm font-medium text-cynTextMuted">
					<?php esc_html_e('تعداد خواب', 'novavilla'); ?>
				</span>
				<div class="flex flex-wrap gap-1.5" data-ads-filters data-ads-filter-group="rooms">
					<?php foreach ($rooms_filters as $i => $filter): ?>
						<button type="button" data-ads-filter="<?php echo esc_attr($filter['key']); ?>" class="<?php echo esc_attr($btn_base . ' ' . ($i === 0 ? $btn_active : $btn_idle)); ?>">
							<span>
								<?php echo esc_html($filter['label']); ?>
							</span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<button type="button" modal-closer data-modal-name="<?php echo esc_attr($modal_name); ?>" class="primary-button w-full justify-center" data-ads-filter-apply>
			<span>
				<?php esc_html_e('اعمال فیلتر', 'novavilla'); ?>
			</span>
		</button>
	</div>
</section>