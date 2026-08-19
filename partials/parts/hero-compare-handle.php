<?php

use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;
?>

<div data-hero-compare-line class="absolute inset-y-0 left-1/2 z-20 w-[3px] -translate-x-1/2 bg-cynBorderHover dark:bg-cynWhite pointer-events-none">
	<span class="absolute top-1/2 left-1/2 flex size-14 lg:size-16 -translate-x-1/2 -translate-y-1/2 items-center justify-center gap-3 rounded-full border border-cynBorderHover bg-cynBorderHover/8 dark:border-cynWhite dark:bg-cynWhite/8 backdrop-blur-xl text-cynBorderHover dark:text-cynWhite">
		<i class="size-8 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
			<?php Icon::print('Arrow-27'); ?>
		</i>
		<i class="size-8 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
			<?php Icon::print('Arrow-19'); ?>
		</i>
	</span>
</div>