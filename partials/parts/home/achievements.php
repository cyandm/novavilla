<?php

use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;

$home_id = get_the_ID();
$home_achievement_icons = [
	1 => 'Vector-5',
	2 => 'Vector-4',
	3 => 'Vector-3',
	4 => 'Vector-2',
	5 => 'Vector',
];
$home_achievements = [];

foreach ($home_achievement_icons as $i => $home_achievement_icon) {
	$home_achievement_title = get_field("home_achievement_title_{$i}", $home_id);
	if (empty($home_achievement_title)) continue;
	$home_achievements[] = [
		'title' => $home_achievement_title,
		'icon' => $home_achievement_icon,
	];
}

if (empty($home_achievements)) return;
?>

<section class="container my-11 md:my-16">
	<div class="flex flex-wrap justify-center lg:justify-between gap-6 lg:gap-16">
		<?php foreach ($home_achievements as $home_achievement) : ?>
			<div class="group flex flex-col items-center gap-2 lg:gap-6 w-24 lg:w-36">
				<i class="size-20 lg:size-32 flex items-center justify-center text-cynSvg group-hover:text-cynSvgHover transition-all duration-300 [&_svg]:!w-auto [&_svg]:!h-auto [&_svg]:max-w-full [&_svg]:max-h-full" aria-hidden="true">
					<?php Icon::print($home_achievement['icon']); ?>
				</i>
				<span class="text-sm lg:text-xl font-semibold text-center text-cynSvg group-hover:text-cynSvgHover leading-4 lg:leading-6 transition-all duration-300">
					<?php echo esc_html($home_achievement['title']); ?>
				</span>
			</div>
		<?php endforeach; ?>
	</div>
</section>
