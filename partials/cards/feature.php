<?php

$args = get_query_var('args', []);

$title = $args['title'] ?? '';
$desc = $args['desc'] ?? '';
$icon = $args['icon'] ?? null;

if (empty($title) && empty($desc)) {
	return;
}

$icon_url = is_array($icon) ? ($icon['url'] ?? '') : '';
$icon_alt = is_array($icon) ? ($icon['alt'] ?? $title) : $title;
?>

<article class="flex flex-col rounded-3xl border border-cynBorder hover:border-cynBorderHover transition-all duration-300 bg-cynBgItem backdrop-blur-xl overflow-hidden min-h-[218px]">
	<div class="relative flex items-center justify-center h-[110px] mt-4 mx-[13px]">
		<span class="pointer-events-none absolute size-[78px] rounded-full bg-[#fbb963] blur-[50px] opacity-80" aria-hidden="true"></span>
		<?php if (!empty($icon_url)) : ?>
			<img
				src="<?php echo esc_url($icon_url); ?>"
				alt="<?php echo esc_attr($icon_alt ?: $title); ?>"
				class="relative z-10 w-[100px] h-auto object-contain drop-shadow-[0_6px_12px_rgba(0,0,0,0.35)]"
				loading="lazy"
				decoding="async" />
		<?php endif; ?>
	</div>

	<div class="flex flex-col gap-1 px-4 pb-4 pt-0">
		<?php if (!empty($title)) : ?>
			<h3 class="text-sm font-medium text-cynTextPrimary text-center">
				<?php echo esc_html($title); ?>
			</h3>
		<?php endif; ?>

		<?php if (!empty($desc)) : ?>
			<p class="text-[10px] font-normal text-cynTextPrimary text-center leading-4">
				<?php echo esc_html($desc); ?>
			</p>
		<?php endif; ?>
	</div>
</article>
