<?php

use Cyan\Theme\Helpers\Icon;

$args = get_query_var('args', []);
$total = max(0, (int) ($args['total'] ?? 0));
$current = max(1, (int) ($args['current'] ?? 1));
$base_url = !empty($args['base_url']) ? (string) $args['base_url'] : get_permalink();
$page_param = sanitize_key((string) ($args['page_param'] ?? 'paged'));
$query_args = is_array($args['query_args'] ?? null) ? $args['query_args'] : [];
$hash = sanitize_text_field((string) ($args['hash'] ?? ''));
$class = sanitize_text_field((string) ($args['class'] ?? ''));
$aria_label = !empty($args['aria_label']) ? (string) $args['aria_label'] : __('صفحه‌بندی', 'novavilla');

if ($total <= 1) {
	return;
}

$link_for = static function (int $page) use ($base_url, $page_param, $query_args, $hash): string {
	$params = array_merge($query_args, [$page_param => $page]);
	$url = add_query_arg($params, $base_url);

	if ($hash !== '') {
		$url .= '#' . ltrim($hash, '#');
	}

	return esc_url($url);
};

$nav_class = trim('pagination flex items-center justify-center md:justify-end gap-2 ' . $class);
?>

<nav class="<?php echo esc_attr($nav_class); ?>" aria-label="<?php echo esc_attr($aria_label); ?>">
	<?php if ($current > 1) : ?>
		<a href="<?php echo $link_for($current - 1); ?>" class="flex items-center justify-center size-10 rounded-full bg-cynBorderHover text-cynBlack shrink-0 transition-opacity hover:opacity-90 no-underline" aria-label="<?php esc_attr_e('صفحه قبل', 'novavilla'); ?>">
			<i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:fill-cynBlack"><?php Icon::print('Arrow-19'); ?></i>
		</a>
	<?php else : ?>
		<span class="flex items-center justify-center size-10 rounded-full bg-cynBorderHover text-cynBlack shrink-0 opacity-40 cursor-not-allowed" aria-hidden="true">
			<i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:fill-cynBlack"><?php Icon::print('Arrow-19'); ?></i>
		</span>
	<?php endif; ?>

	<?php
	$show_pages = 2;
	$dots_before = false;
	$dots_after = false;

	for ($i = 1; $i <= $total; $i++) :
		$show = ($i === 1 || $i === $total || ($i >= $current - $show_pages && $i <= $current + $show_pages));

		if ($show) :
			if ($i > $current) $dots_after = false;
			if ($i < $current) $dots_before = false;

			if ($i === $current) : ?>
				<span class="flex items-center justify-center size-10 min-w-10 rounded-full bg-cynBorder text-sm font-medium text-cynTextPrimary shrink-0" aria-current="page"><?php echo (int) $i; ?></span>
			<?php else : ?>
				<a href="<?php echo $link_for($i); ?>" class="flex items-center justify-center size-10 min-w-10 rounded-full bg-cynBgItem text-sm font-medium text-cynTextPrimary shrink-0 transition-colors hover:bg-cynBorderHover/25 no-underline"><?php echo (int) $i; ?></a>
			<?php endif;
		else :
			if ($i < $current && !$dots_before) {
				$dots_before = true;
				echo '<span class="text-cynTextPrimary/50 px-1">…</span>';
			} elseif ($i > $current && !$dots_after) {
				$dots_after = true;
				echo '<span class="text-cynTextPrimary/50 px-1">…</span>';
			}
		endif;
	endfor;
	?>

	<?php if ($current < $total) : ?>
		<a href="<?php echo $link_for($current + 1); ?>" class="flex items-center justify-center size-10 rounded-full bg-cynBorderHover text-cynBlack shrink-0 transition-opacity hover:opacity-90 no-underline" aria-label="<?php esc_attr_e('صفحه بعد', 'novavilla'); ?>">
			<i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:fill-cynBlack"><?php Icon::print('Arrow-27'); ?></i>
		</a>
	<?php else : ?>
		<span class="flex items-center justify-center size-10 rounded-full bg-cynBorderHover text-cynBlack shrink-0 opacity-40 cursor-not-allowed" aria-hidden="true">
			<i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:fill-cynBlack"><?php Icon::print('Arrow-27'); ?></i>
		</span>
	<?php endif; ?>
</nav>
