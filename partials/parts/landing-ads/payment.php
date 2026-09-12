<?php
$title = get_field('ads_payment_title');
$subtitle = get_field('ads_payment_subtitle');
$desc = get_field('ads_payment_desc');
if (!$title && !$subtitle && !$desc) return;
?>
<div class="flex flex-col gap-3" id="calc">
	<?php if ($title): ?>
		<h2 class="text-3xl md:text-4xl font-medium text-cynTextPrimary">
			<?php echo esc_html($title); ?>
		</h2>
	<?php endif; ?>
	<?php if ($subtitle): ?>
		<p class="text-xl md:text-2xl font-normal text-cynTextPrimary leading-6">
			<?php echo esc_html($subtitle); ?>
		</p>
	<?php endif; ?>
	<?php if ($desc): ?>
		<p class="text-lg md:text-xl font-normal text-cynTextPrimary leading-6">
			<?php echo esc_html($desc); ?>
		</p>
	<?php endif; ?>
</div>