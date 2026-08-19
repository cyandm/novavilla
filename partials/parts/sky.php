<?php

defined('ABSPATH') || exit;

$star_count = 110;
$seed = 2147483647;
$show_milky_way = is_front_page();
$milky_way = get_stylesheet_directory_uri() . '/assets/image/milky-way.webp';
?>

<div class="home-sky pointer-events-none fixed inset-0 -z-10 overflow-hidden hidden dark:block" aria-hidden="true">
	<?php if ($show_milky_way) : ?>
		<img src="<?php echo esc_url($milky_way); ?>" alt="" class="absolute left-1/2 top-1/2 w-[160%] max-w-none h-96 -translate-x-1/2 -translate-y-1/2 object-contain object-center opacity-70 lg:w-full lg:max-w-[1222px] lg:h-[685px]" loading="eager" decoding="async">
	<?php endif; ?>

	<?php for ($i = 0; $i < $star_count; $i++) :
		$seed = (1103515245 * $seed + 12345) & 0x7fffffff;
		$x = ($seed % 10000) / 100;
		$seed = (1103515245 * $seed + 12345) & 0x7fffffff;
		$y = 4 + (($seed % 7200) / 100);
		$seed = (1103515245 * $seed + 12345) & 0x7fffffff;
		$size = 10 + ($seed % 26);
		$seed = (1103515245 * $seed + 12345) & 0x7fffffff;
		$delay = ($seed % 90) / 10;
		$seed = (1103515245 * $seed + 12345) & 0x7fffffff;
		$dur = 3.2 + ($seed % 55) / 10;
		$seed = (1103515245 * $seed + 12345) & 0x7fffffff;
		$tx = -6 + ($seed % 13);
		$seed = (1103515245 * $seed + 12345) & 0x7fffffff;
		$ty = -5 + ($seed % 11);
		$hide_mobile = $i >= 64 ? ' max-lg:hidden' : '';
		?>
		<span class="home-sky-star absolute rounded-full<?php echo esc_attr($hide_mobile); ?>" style="left: <?php echo esc_attr($x); ?>%; top: <?php echo esc_attr($y); ?>%; width: <?php echo esc_attr($size); ?>px; height: <?php echo esc_attr($size); ?>px; --delay: <?php echo esc_attr($delay); ?>s; --dur: <?php echo esc_attr($dur); ?>s; --tx: <?php echo esc_attr($tx); ?>px; --ty: <?php echo esc_attr($ty); ?>px;"></span>
	<?php endfor; ?>
</div>
