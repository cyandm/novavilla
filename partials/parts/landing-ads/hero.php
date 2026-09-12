<?php
$eyebrow = get_field('ads_hero_eyebrow');
$titleBefore = get_field('ads_hero_title_before');
$titleHighlight = get_field('ads_hero_title_highlight');
$titleAfter = get_field('ads_hero_title_after');
$prepayValue = get_field('ads_hero_prepay_value') ?: '50%';
$prepayLabel = get_field('ads_hero_prepay_label') ?: __('پیش پرداخت', 'novavilla');
$periodValue = get_field('ads_hero_period_value') ?: __('24 ماه', 'novavilla');
$periodLabel = get_field('ads_hero_period_label') ?: __('پرداخت اقساط', 'novavilla');
$note = get_field('ads_hero_note');
$detailsLink = get_field('ads_hero_details_link');
$image = get_field('ads_hero_image');
?>
<section class="relative overflow-hidden rounded-3xl border-0 dark:border dark:border-cynBorderHover/40 bg-[linear-gradient(90deg,#FF7174_0%,#E8C04A_100%)] dark:bg-[linear-gradient(90deg,rgba(53,53,53,0.29)_0%,rgba(255,232,81,0.29)_62.71%,rgba(255,80,11,0.29)_100%)] backdrop-blur-sm p-5 md:p-8 lg:p-10">
	<div class="relative z-10 flex flex-col-reverse gap-6 lg:flex-row lg:items-center lg:justify-between">
		<div class="flex flex-col gap-4 lg:max-w-xl">
			<?php if ($eyebrow): ?>
				<p class="text-lg md:text-xl font-black text-cynTextPrimary dark:text-cynWhite">
					<?php echo esc_html($eyebrow); ?>
				</p>
			<?php endif; ?>
			<h1 class="text-[1.625rem] md:text-4xl font-black text-cynTextPrimary dark:text-cynWhite leading-tight md:leading-[64px]">
				<?php if ($titleBefore): ?><span><?php echo esc_html($titleBefore); ?></span><?php endif; ?>
				<?php if ($titleHighlight): ?><span class="text-cynWhite dark:text-cynBorderHover"><?php echo esc_html($titleHighlight); ?></span><?php endif; ?>
				<?php if ($titleAfter): ?><span><?php echo esc_html($titleAfter); ?></span><?php endif; ?>
			</h1>
			<div class="flex flex-wrap gap-3">
				<div class="w-fit text-center rounded-[10px] border border-cynTextPrimary/30 dark:border-transparent bg-white/20 p-2.5 backdrop-blur-sm">
					<p class="text-3xl md:text-4xl font-black text-cynTextPrimary dark:text-cynWhite">
						<?php echo esc_html($prepayValue); ?>
					</p>
					<p class="text-lg md:text-xl font-medium text-cynTextPrimary dark:text-cynWhite">
						<?php echo esc_html($prepayLabel); ?>
					</p>
				</div>
				<div class="w-fit text-center rounded-[10px] border border-cynTextPrimary/30 dark:border-transparent bg-white/20 p-2.5 backdrop-blur-sm">
					<p class="text-3xl md:text-4xl font-black text-cynTextPrimary dark:text-cynWhite">
						<?php echo esc_html($periodValue); ?>
					</p>
					<p class="text-lg md:text-xl font-medium text-cynTextPrimary dark:text-cynWhite">
						<?php echo esc_html($periodLabel); ?>
					</p>
				</div>
			</div>
			<?php if ($note || !empty($detailsLink['url'])): ?>
				<div class="flex flex-wrap items-center gap-2.5">
					<?php if ($note): ?>
						<p class="text-lg md:text-xl font-medium text-cynTextPrimary dark:text-cynWhite">
							<?php echo esc_html($note); ?>
						</p>
					<?php endif; ?>
					<?php if (!empty($detailsLink['url'])): ?>
						<a href="<?php echo esc_url($detailsLink['url']); ?>" <?php echo !empty($detailsLink['target']) ? 'target="' . esc_attr($detailsLink['target']) . '"' : ''; ?> class="text-base font-bold text-cynWhite dark:text-cynBorderHover underline transition-all duration-300">
							<?php echo esc_html($detailsLink['title'] ?: __('جزئیات شرایط پرداخت', 'novavilla')); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ($image): ?>
			<div class="w-full lg:w-80 shrink-0">
				<img src="<?php echo esc_url($image); ?>" alt="" class="w-full h-60 object-cover" />
			</div>
		<?php endif; ?>
	</div>
</section>