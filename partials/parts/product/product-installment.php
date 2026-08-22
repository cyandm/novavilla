<?php

use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;

$title = get_field('product_installment_title') ?: __('شرایط پیش پرداخت و اقساط', 'novavilla');
$subtitle = get_field('product_installment_subtitle') ?: __('یکی از دو حالت زیر را انتخاب کنید', 'novavilla');
$image = get_field('product_installment_image');
$price = (float) (get_field('product_price') ?: 0);

$home_id = (int) get_option('page_on_front');
if (!$home_id || $price <= 0) {
	return;
}

$default_image = get_field('product_installment_default_image', $home_id);
$image_url = is_string($image) && $image !== '' ? $image : (is_string($default_image) && $default_image !== '' ? $default_image : '');
$prepay_section_title = get_field('product_prepay_section_title', $home_id) ?: __('پیش پرداخت', 'novavilla');
$period_section_title = get_field('product_period_section_title', $home_id) ?: __('مدت بازپرداخت (تعداد اقساط)', 'novavilla');
$calc_section_title = get_field('product_calc_section_title', $home_id) ?: __('محاسبه اقساط', 'novavilla');
$interest_rate = (float) (get_field('product_interest_rate', $home_id) ?: 3);
$note = get_field('product_installment_note', $home_id) ?: __('مبلغ نهایی با توجه به مبلغ سفارش و تعداد اقساط محاسبه می‌شود.', 'novavilla');

$prepays = [];
for ($i = 1; $i <= 2; $i++) {
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
for ($i = 1; $i <= 3; $i++) {
	$months = (int) get_field("product_period_months_{$i}", $home_id);
	if ($months <= 0) {
		continue;
	}
	$periods[] = [
		'months' => $months,
		'label' => get_field("product_period_label_{$i}", $home_id) ?: sprintf(__('%s ماه', 'novavilla'), $months),
	];
}

if (empty($prepays) || empty($periods)) {
	return;
}

// RTL: Figma shows 30% then 50% from right; reverse ACF order (50, 30).
$prepays = array_reverse($prepays);
$default_prepay = $prepays[0]['percent'];
foreach ($prepays as $prepay) {
	if ((int) $prepay['percent'] === 30) {
		$default_prepay = $prepay['percent'];
		break;
	}
}
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

$option_card = 'group flex items-start gap-5 rounded-3xl border border-cynWhite/30 bg-cynWhite/8 backdrop-blur-lg p-4 transition-all duration-300 cursor-pointer [&.is-selected]:border-cynBorderHover';
$check_box = 'size-6 shrink-0 rounded-md border border-cynWhite flex items-center justify-center transition-all duration-300 group-[.is-selected]:border-cynBorderHover group-[.is-selected]:bg-cynBorderHover';
$period_card = 'group flex items-center gap-5 rounded-3xl border border-cynWhite/30 bg-cynWhite/8 backdrop-blur-lg p-4 transition-all duration-300 cursor-pointer [&.is-selected]:border-cynBorderHover';
$stat_label = 'text-sm md:text-base font-medium text-cynTextPrimary leading-5';
$stat_value = 'text-xs md:text-sm font-normal text-cynTextPrimary leading-6';
$stat_accent = 'text-xs md:text-sm font-medium text-cynBorderHover leading-6';
$stat_cell = 'flex flex-col gap-2 p-4 border-cynWhite/25 max-sm:[&:nth-child(even)]:border-r [&:not(:nth-last-child(-n+2))]:border-b sm:[&:not(:nth-last-child(-n+2))]:border-b-0 sm:[&:not(:nth-last-child(-n+3))]:border-b sm:[&:nth-child(3n+2)]:border-r sm:[&:nth-child(3n)]:border-r';
?>

<section class="container my-12 lg:my-20" data-product-installment data-price="<?php echo esc_attr((string) $price); ?>" data-rate="<?php echo esc_attr((string) $interest_rate); ?>" data-currency="<?php echo esc_attr($currency); ?>">
	<div class="flex flex-col">

		<?php if ($image_url) : ?>
			<div class="flex lg:hidden w-full items-center justify-center">
				<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full max-w-md h-auto object-contain" loading="lazy" decoding="async" />
			</div>
		<?php endif; ?>

		<div class="flex flex-col gap-2 md:gap-3 mt-5 mb-3 lg:mb-5">
			<h2 class="text-2xl md:text-4xl font-medium text-cynTextPrimary leading-8 md:leading-14">
				<?php echo esc_html($title); ?>
			</h2>
			<p class="text-sm md:text-2xl font-normal text-cynTextPrimary leading-6">
				<?php echo esc_html($subtitle); ?>
			</p>
		</div>

		<div class="flex flex-col-reverse lg:flex-row gap-3 items-stretch">
			<div class="w-full lg:w-3/5 lg:shrink-0 rounded-3xl border border-cynBorderHover/40 bg-cynBgItem backdrop-blur-md p-4 transition-all duration-300 hover:border-cynBorderHover">
				<div class="flex flex-col gap-3">
					<div class="flex flex-col gap-6">
						<div class="flex flex-col gap-3">
							<div class="flex items-center gap-2 pb-2 border-b border-white/25 w-fit">
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
							<div class="flex items-center gap-2 pb-2 border-b border-white/25 w-fit">
								<span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6">
									<?php echo esc_html($period_section_title); ?>
								</span>
							</div>
							<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
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
							<div class="flex items-center gap-2 pb-2 border-b border-white/25 w-fit">
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