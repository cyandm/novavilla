<?php

use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;

$args = get_query_var('args', []);
$post_id = !empty($args['post-id']) ? (int) $args['post-id'] : get_the_ID();
if (!$post_id) return;

$title = get_the_title($post_id);
$permalink = get_permalink($post_id);
$thumb_id = get_post_thumbnail_id($post_id);
$price = (float) get_field('product_price', $post_id);
$area = (string) get_field('product_area', $post_id);
$rooms = (string) get_field('product_rooms', $post_id);
$description = get_field('product_description', $post_id);
$half_price = $price > 0 ? $price * 0.5 : 0;
$area_num = is_numeric($area) ? (float) $area : 0;
$rooms_num = is_numeric($rooms) ? (int) $rooms : 0;

$page_id = get_queried_object_id();
$label_half = (string) get_field('ads_card_prepay_label', $page_id);
$label_cta = (string) get_field('ads_card_cta_label', $page_id);
$label_details = (string) get_field('ads_card_details_label', $page_id);
if ($label_half === '') $label_half = __('50% قیمت پایه :', 'novavilla');
if ($label_cta === '') $label_cta = __('قیمت روز و اقساط این مدل', 'novavilla');
if ($label_details === '') $label_details = __('جزئیات خرید و تحویل', 'novavilla');
$label_details_close = sprintf(__('بستن %s', 'novavilla'), $label_details);
?>

<div data-ads-card data-area="<?php echo esc_attr((string) $area_num); ?>" data-rooms="<?php echo esc_attr((string) $rooms_num); ?>" class="relative w-full z-0 transition-[z-index] duration-300 data-[open=true]:z-[60] [&[data-open=true]_[data-ads-card-icon]]:-rotate-90 [&[data-elevated=true]_[data-ads-card-surface]]:shadow-xl [&[data-open=true]_[data-ads-card-drawer]]:pointer-events-auto [&[data-open=true]_[data-ads-card-toggle]]:text-cynTextPrimaryHover [&[data-open=true]_[data-ads-card-label-open]]:hidden [&[data-open=true]_[data-ads-card-label-close]]:inline [&[data-elevated=true]_[data-ads-open-text]]:!text-cynWhite [&[data-elevated=true]_[data-ads-open-muted]]:!text-cynWhite/80 [&[data-elevated=true]_[data-ads-open-muted]_svg]:!fill-cynWhite/80">
	<div data-ads-card-frame aria-hidden="true" class="absolute inset-x-0 top-0 bottom-0 z-[3] rounded-3xl border border-cynBorderHover/40 dark:border-transparent pointer-events-none transition-[bottom] duration-300 ease-out"></div>

	<div class="relative w-full flex flex-col rounded-3xl transition-all duration-300" data-ads-card-surface>
		<a href="<?php echo esc_url($permalink); ?>" class="block w-full">
			<?php if ($thumb_id) : ?>
				<?php echo wp_get_attachment_image($thumb_id, 'large', false, ['class' => 'w-full h-72 object-cover rounded-t-3xl', 'alt' => esc_attr($title)]); ?>
			<?php else : ?>
				<div class="w-full h-72 rounded-t-3xl bg-cynBgItem"></div>
			<?php endif; ?>
		</a>

		<div class="relative w-full" data-ads-card-body>
			<div data-ads-card-frost aria-hidden="true" class="absolute inset-x-0 top-0 bottom-0 rounded-b-3xl bg-cynWhite/10 backdrop-blur-md pointer-events-none transition-[bottom] duration-300 ease-out"></div>

			<div class="relative z-[1] px-3 py-2 flex flex-col gap-1.5">
				<a href="<?php echo esc_url($permalink); ?>" data-ads-open-text class="text-sm font-bold text-cynTextPrimary leading-6 transition-all duration-300">
					<?php echo esc_html($title); ?>
				</a>

				<div data-ads-open-muted class="flex items-center gap-3 text-cynTextMuted transition-all duration-300">
					<?php if ($rooms !== '') : ?>
						<span class="inline-flex items-center gap-1">
							<i class="size-4 flex items-center justify-center [&_svg]:size-full [&_svg]:fill-cynTextMuted">
								<?php Icon::print('Double,-Bed-1'); ?>
							</i>
							<span class="text-xs font-semibold">
								<?php echo esc_html($rooms); ?>
							</span>
						</span>
					<?php endif; ?>
					<?php if ($area !== '') : ?>
						<span class="inline-flex items-center gap-1">
							<i class="size-3.5 flex items-center justify-center [&_svg]:size-full [&_svg]:fill-cynTextMuted">
								<?php Icon::print('home-house-big'); ?>
							</i>
							<span class="text-xs font-semibold">
								<?php echo esc_html(sprintf(__('%s متر', 'novavilla'), $area)); ?>
							</span>
						</span>
					<?php endif; ?>
				</div>

				<?php if ($price > 0) : ?>
					<div class="w-full px-3 py-1 rounded-lg bg-cynBorderHover/10 flex items-center justify-between gap-2">
						<span class="text-xs font-normal text-cynBorderHover leading-5">
							<?php esc_html_e('قیمت پایه', 'novavilla'); ?>
						</span>
						<span class="text-sm font-normal text-cynBorderHover leading-6">
							<?php echo esc_html(number_format_i18n($price) . ' ' . __('تومان', 'novavilla')); ?>
						</span>
					</div>

					<div class="w-full flex items-center gap-1.5">
						<span data-ads-open-text class="text-sm font-medium text-cynTextPrimary leading-6 transition-all duration-300">
							<?php echo esc_html($label_half); ?>
						</span>
						<span data-ads-open-text class="text-sm font-bold text-cynTextPrimary leading-6 transition-all duration-300">
							<?php echo esc_html(number_format_i18n($half_price)); ?>
						</span>
					</div>
				<?php endif; ?>

				<a href="<?php echo esc_url($permalink); ?>" class="w-full px-3 py-2 rounded-lg bg-[#FFE375] text-center text-xs font-medium text-cynBlack leading-5 transition-all duration-300 hover:brightness-95">
					<?php echo esc_html($label_cta); ?>
				</a>

				<button type="button" data-ads-card-toggle class="flex w-full items-center text-sm font-medium text-cynTextPrimary leading-6 transition-all duration-300">
					<i class="size-6 shrink-0 flex items-center justify-center transition-transform duration-300 [&_svg]:size-full [&_svg]:stroke-[1.5]" data-ads-card-icon>
						<?php Icon::print('Arrow-27'); ?>
					</i>
					<span data-ads-card-label-open>
						<?php echo esc_html($label_details); ?>
					</span>
					<span data-ads-card-label-close class="hidden">
						<?php echo esc_html($label_details_close); ?>
					</span>
				</button>
			</div>

			<?php if (!empty($description)) : ?>
				<div data-ads-card-drawer class="absolute inset-x-0 top-full z-[1] h-0 overflow-hidden pointer-events-none transition-[height] duration-300 ease-out">
					<div data-ads-card-expand class="w-full px-3 pb-3 pt-1">
						<div data-ads-open-text class="text-xs font-medium text-cynTextPrimary leading-6 transition-all duration-300 [&_ul]:list-disc [&_ul]:ps-6">
							<?php echo wp_kses_post($description); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>