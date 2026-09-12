<?php

use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

defined('ABSPATH') || exit;

$args = get_query_var('args', []);
$wrap_container = !array_key_exists('wrap_container', $args) || !empty($args['wrap_container']);

$home_id = (int) get_option('page_on_front');
$price = (float) (get_field('product_price') ?: 0);
if (isset($args['price']) && (float) $args['price'] > 0) $price = (float) $args['price'];

if (!$home_id || $price <= 0) {
	return;
}

$title = get_field('product_installment_title', $home_id) ?: __('شرایط پیش پرداخت و اقساط', 'novavilla');
$image = get_field('product_installment_default_image', $home_id);
$image_url = '';
if (is_array($image) && !empty($image['url'])) $image_url = (string) $image['url'];
elseif (is_string($image) && $image !== '') $image_url = $image;

$prepay_section_title = get_field('product_prepay_section_title', $home_id) ?: __('پیش پرداخت', 'novavilla');
$period_section_title = get_field('product_period_section_title', $home_id) ?: __('مدت بازپرداخت (تعداد اقساط)', 'novavilla');
$calc_section_title = get_field('product_calc_section_title', $home_id) ?: __('محاسبه اقساط', 'novavilla');
$interest_rate = (float) (get_field('product_interest_rate', $home_id) ?: 3);
$note = get_field('product_installment_note', $home_id) ?: __('مبلغ نهایی با توجه به مبلغ سفارش و تعداد اقساط محاسبه می‌شود.', 'novavilla');

$prepays = [];
for ($i = 1; $i <= 3; $i++) {
	$percent = (float) get_field("product_prepay_percent_{$i}", $home_id);
	if ($percent <= 0) {
		continue;
	}
	$prepays[] = [
		'percent' => $percent,
		'title' => get_field("product_prepay_title_{$i}", $home_id) ?: sprintf(__('پیش پرداخت %s%%', 'novavilla'), rtrim(rtrim(number_format($percent, 1, '.', ''), '0'), '.')),
		'desc' => (string) (get_field("product_prepay_desc_{$i}", $home_id) ?: ''),
	];
}

$periods = [];
$period_defaults = [
	1 => ['months' => 3, 'label' => __('3 ماه', 'novavilla')],
	2 => ['months' => 6, 'label' => __('6 ماه', 'novavilla')],
	3 => ['months' => 12, 'label' => __('12 ماه', 'novavilla')],
	4 => ['months' => 16, 'label' => __('16 ماه', 'novavilla')],
	5 => ['months' => 18, 'label' => __('18 ماه', 'novavilla')],
	6 => ['months' => 24, 'label' => __('24 ماه', 'novavilla')],
];
for ($i = 1; $i <= 6; $i++) {
	$months_raw = get_field("product_period_months_{$i}", $home_id);
	$label_raw = get_field("product_period_label_{$i}", $home_id);
	if ($months_raw === null || $months_raw === false || $months_raw === '') {
		$months = (int) ($period_defaults[$i]['months'] ?? 0);
		$label = (string) ($label_raw ?: ($period_defaults[$i]['label'] ?? ''));
	} else {
		$months = (int) $months_raw;
		if ($months <= 0) continue;
		$label = (string) ($label_raw ?: sprintf(__('%s ماه', 'novavilla'), $months));
	}
	if ($months <= 0) continue;
	$periods[] = ['months' => $months, 'label' => $label];
}

if (empty($prepays) || empty($periods)) {
	return;
}

$default_prepay = $prepays[0]['percent'];
$default_months = $periods[0]['months'];
foreach ($periods as $period) {
	if ((int) $period['months'] === 3) {
		$default_months = (int) $period['months'];
		break;
	}
}

$currency = __('تومان', 'novavilla');
$format_amount = static fn($n) => number_format_i18n((float) $n);
$calc_remaining = $price * (1 - ($default_prepay / 100));
$calc_sum = $calc_remaining * (1 + (($default_months * $interest_rate) / 100));
$calc_monthly = $default_months > 0 ? $calc_sum / $default_months : 0;

$option_card = 'group flex items-start gap-5 rounded-3xl border border-cynBorder dark:border-cynWhite/30 bg-cynWhite/8 backdrop-blur-lg p-4 transition-all duration-300 cursor-pointer [&.is-selected]:border-cynBorderHover';
$check_box = 'size-6 shrink-0 rounded-md border border-cynBorder dark:border-cynWhite flex items-center justify-center transition-all duration-300 group-[.is-selected]:border-cynBorderHover group-[.is-selected]:bg-cynBorderHover';
$period_card = 'group flex items-center gap-5 rounded-3xl border border-cynBorder dark:border-cynWhite/30 bg-cynWhite/8 backdrop-blur-lg p-4 transition-all duration-300 cursor-pointer [&.is-selected]:border-cynBorderHover';
$stat_label = 'text-sm md:text-base font-medium text-cynTextPrimary leading-5';
$stat_value = 'text-xs md:text-sm font-normal text-cynTextPrimary leading-6';
$stat_accent = 'text-xs md:text-sm font-medium text-cynBorderHover leading-6';
$stat_cell = 'flex flex-col gap-2 p-4 border-cynBorder dark:border-cynWhite/25 max-sm:[&:nth-child(even)]:border-r [&:not(:nth-last-child(-n+2))]:border-b sm:[&:not(:nth-last-child(-n+2))]:border-b-0 sm:[&:not(:nth-last-child(-n+3))]:border-b sm:[&:nth-child(3n+2)]:border-r sm:[&:nth-child(3n)]:border-r';
$title_underline_class = 'flex items-center gap-2 pb-2 border-b border-cynBorder dark:border-white/25 w-fit';
?>

<section class="<?php echo $wrap_container ? 'container my-12 lg:my-20' : 'w-full'; ?>" data-product-installment data-price="<?php echo esc_attr((string) $price); ?>" data-rate="<?php echo esc_attr((string) $interest_rate); ?>" data-currency="<?php echo esc_attr($currency); ?>">
	<div class="flex flex-col gap-5">

		<?php if ($image_url) : ?>
			<div class="flex lg:hidden w-full items-center justify-center">
				<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full max-w-md h-auto object-contain" loading="lazy" decoding="async" />
			</div>
		<?php endif; ?>

		<?php if (!empty($args['include_payment'])) Templates::getPart('landing-ads/payment'); ?>

		<div class="flex flex-col-reverse lg:flex-row gap-3 items-stretch">
			<div class="w-full lg:w-3/5 lg:shrink-0 rounded-3xl border border-cynBorderHover/40 bg-cynBgItem backdrop-blur-md p-4 transition-all duration-300 hover:border-cynBorderHover">
				<div class="flex flex-col gap-3">
					<div class="flex flex-col gap-6">
						<div class="flex flex-col gap-3">
							<div class="<?php echo esc_attr($title_underline_class); ?>">
								<span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6">
									<?php echo esc_html($prepay_section_title); ?>
								</span>
							</div>
							<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
								<?php foreach ($prepays as $prepay) :
									$is_selected = abs($prepay['percent'] - $default_prepay) < 0.001;
								?>
									<button type="button" data-installment-prepay data-percent="<?php echo esc_attr((string) $prepay['percent']); ?>" class="<?php echo esc_attr($option_card . ($is_selected ? ' is-selected' : '')); ?>" aria-pressed="<?php echo $is_selected ? 'true' : 'false'; ?>">
										<span class="<?php echo esc_attr($check_box); ?>" aria-hidden="true">
											<i class="size-3.5 opacity-0 group-[.is-selected]:opacity-100 text-cynBlack flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-current">
												<?php Icon::print('check'); ?>
											</i>
										</span>
										<span class="flex flex-col gap-2">
											<span class="text-sm md:text-base font-medium text-cynTextPrimary leading-5 group-[.is-selected]:text-cynBorderHover transition-all duration-300 text-start">
												<?php echo esc_html($prepay['title']); ?>
											</span>
											<?php if ($prepay['desc'] !== '') : ?>
												<span class="text-xs font-light text-cynTextMuted leading-4 md:leading-6">
													<?php echo esc_html($prepay['desc']); ?>
												</span>
											<?php endif; ?>
										</span>
									</button>
								<?php endforeach; ?>
							</div>
						</div>

						<div class="flex flex-col gap-3">
							<div class="<?php echo esc_attr($title_underline_class); ?>">
								<span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6">
									<?php echo esc_html($period_section_title); ?>
								</span>
							</div>
							<div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
								<?php foreach ($periods as $period) :
									$is_selected = (int) $period['months'] === (int) $default_months;
								?>
									<button type="button" data-installment-period data-months="<?php echo esc_attr((string) $period['months']); ?>" class="<?php echo esc_attr($period_card . ($is_selected ? ' is-selected' : '')); ?>" aria-pressed="<?php echo $is_selected ? 'true' : 'false'; ?>">
										<span class="<?php echo esc_attr($check_box); ?>" aria-hidden="true">
											<i class="size-3.5 opacity-0 group-[.is-selected]:opacity-100 text-cynBlack flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-current">
												<?php Icon::print('check'); ?>
											</i>
										</span>
										<span class="text-sm md:text-base font-medium text-cynTextPrimary leading-5 group-[.is-selected]:text-cynBorderHover transition-all duration-300 text-start">
											<?php echo esc_html($period['label']); ?>
										</span>
									</button>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<div class="rounded-3xl border border-cynBorderHover/40 p-4 hover:border-cynBorderHover transition-all duration-300">
						<div class="flex flex-col gap-3">
							<div class="<?php echo esc_attr($title_underline_class); ?>">
								<i class="size-6 flex items-center justify-center text-cynBorderHover [&_svg]:size-full [&_svg]:stroke-[1.5]">
									<?php Icon::print('Calculator'); ?>
								</i>
								<span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6">
									<?php echo esc_html($calc_section_title); ?>
								</span>
							</div>

							<div class="grid grid-cols-2 sm:grid-cols-3">
								<div class="<?php echo esc_attr($stat_cell); ?>">
									<span class="<?php echo esc_attr($stat_label); ?>">
										<?php esc_html_e('قیمت کل :', 'novavilla'); ?>
									</span>
									<span class="<?php echo esc_attr($stat_value); ?>" data-installment-total-price>
										<span class="<?php echo esc_attr($stat_accent); ?>" data-installment-amount><?php echo esc_html($format_amount($price)); ?></span>
										<span class="ms-0.5"><?php echo esc_html($currency); ?></span>
									</span>
								</div>
								<div class="<?php echo esc_attr($stat_cell); ?>">
									<span class="<?php echo esc_attr($stat_label); ?>">
										<?php esc_html_e('پیش پرداخت :', 'novavilla'); ?>
									</span>
									<span class="<?php echo esc_attr($stat_accent); ?>" data-installment-prepay-percent>
										<?php echo esc_html(rtrim(rtrim(number_format($default_prepay, 1, '.', ''), '0'), '.') . ' %'); ?>
									</span>
								</div>
								<div class="<?php echo esc_attr($stat_cell); ?>">
									<span class="<?php echo esc_attr($stat_label); ?>">
										<?php esc_html_e('مانده :', 'novavilla'); ?>
									</span>
									<span class="<?php echo esc_attr($stat_value); ?>" data-installment-remaining>
										<span class="<?php echo esc_attr($stat_accent); ?>" data-installment-amount><?php echo esc_html($format_amount($calc_remaining)); ?></span>
										<span class="ms-0.5"><?php echo esc_html($currency); ?></span>
									</span>
								</div>
								<div class="<?php echo esc_attr($stat_cell); ?>">
									<span class="<?php echo esc_attr($stat_label); ?>">
										<?php esc_html_e('تعداد اقساط :', 'novavilla'); ?>
									</span>
									<span class="<?php echo esc_attr($stat_accent); ?>" data-installment-months>
										<?php echo esc_html(sprintf(__('%s ماه', 'novavilla'), $default_months)); ?>
									</span>
								</div>
								<div class="<?php echo esc_attr($stat_cell); ?>">
									<span class="<?php echo esc_attr($stat_label); ?>">
										<?php esc_html_e('سود ماهانه :', 'novavilla'); ?>
									</span>
									<span class="<?php echo esc_attr($stat_accent); ?>">
										<?php echo esc_html(rtrim(rtrim(number_format($interest_rate, 1, '.', ''), '0'), '.') . ' %'); ?>
									</span>
								</div>
								<div class="<?php echo esc_attr($stat_cell); ?>">
									<span class="<?php echo esc_attr($stat_label); ?>">
										<?php esc_html_e('جمع مبلغ اقساط :', 'novavilla'); ?>
									</span>
									<span class="<?php echo esc_attr($stat_value); ?>" data-installment-sum>
										<span class="<?php echo esc_attr($stat_accent); ?>" data-installment-amount><?php echo esc_html($format_amount($calc_sum)); ?></span>
										<span class="ms-0.5"><?php echo esc_html($currency); ?></span>
									</span>
								</div>
							</div>

							<div class="flex items-center justify-center gap-2 rounded-3xl border border-cynBorderHover/40 bg-cynWhite/8 px-4 py-4">
								<span class="flex items-center gap-2">
									<i class="size-5 flex items-center justify-center text-cynBorderHover [&_svg]:size-full [&_svg]:stroke-[1.5]">
										<?php Icon::print('Sale,-Discount,-Promotion'); ?>
									</i>
									<span class="text-sm md:text-base font-medium text-cynBorderHover leading-6">
										<?php esc_html_e('مبلغ هر قسط :', 'novavilla'); ?>
									</span>
								</span>
								<span class="text-base md:text-2xl font-medium text-cynTextPrimary leading-6" data-installment-monthly>
									<span class="text-cynBorderHover" data-installment-amount><?php echo esc_html($format_amount($calc_monthly)); ?></span>
									<span class="ms-0.5"><?php echo esc_html($currency); ?></span>
								</span>
							</div>
						</div>
					</div>

					<div class="flex items-center justify-center gap-2 rounded-3xl border border-cynBorderHover/40 bg-cynWhite/8 py-3 px-2 sm:px-4 sm:py-4">
						<i class="size-6 shrink-0 flex items-center justify-center text-cynBorderHover [&_svg]:size-full [&_svg]:stroke-[1.5]">
							<?php Icon::print('Infornation,-Info'); ?>
						</i>
						<p class="text-xs md:text-base font-normal text-cynTextPrimary leading-6">
							<?php echo esc_html($note); ?>
						</p>
					</div>
				</div>
			</div>

			<?php if ($image_url) : ?>
				<div class="hidden lg:flex w-full lg:w-2/5 items-center justify-center">
					<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full max-w-md h-auto object-contain" loading="lazy" decoding="async" />
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>