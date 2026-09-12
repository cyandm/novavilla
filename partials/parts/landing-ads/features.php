<?php
$items = [];
for ($i = 1; $i <= 3; $i++) {
	$img = get_field("ads_feature_image_{$i}");
	$title = get_field("ads_feature_title_{$i}");
	if ($img || $title) $items[] = ['image' => $img, 'title' => $title];
}
if (empty($items)) return;
?>
<section class="flex flex-wrap items-start justify-center gap-8 md:gap-16 lg:gap-40">
	<?php foreach ($items as $item): ?>
		<div class="w-36 md:w-44 flex flex-col items-center gap-1">
			<?php if (!empty($item['image'])): ?>
				<span class="block size-24 bg-cynBorderHover dark:bg-cynWhite" style="-webkit-mask:url('<?php echo esc_url($item['image']); ?>') center / contain no-repeat;mask:url('<?php echo esc_url($item['image']); ?>') center / contain no-repeat;" aria-hidden="true"></span>
			<?php endif; ?>
			<?php if (!empty($item['title'])): ?>
				<p class="text-sm font-medium text-cynTextPrimary text-center">
					<?php echo esc_html($item['title']); ?>
				</p>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</section>