<?php

/**
 * Theme toggle — floating assistant-style control
 * @package CyanThemeSetup
 */

use Cyan\Theme\Helpers\Icon;
?>

<div theme-toggle-dock data-expanded="false" class="fixed start-0 top-1/2 z-[100] -translate-y-1/2">

	<button type="button" theme-toggle aria-label="فعال کردن تم تیره" aria-pressed="false" aria-expanded="false" data-expanded="false" class="group inline-flex size-8 items-center justify-center rounded-e-xl rounded-s-none border border-s-0 border-cynBorder bg-cynBgItem backdrop-blur-lg text-cynSvg shadow-md transition-all duration-300 ease-out hover:text-cynSvgHover hover:scale-125 active:scale-150 translate-x-2/5 data-[expanded=true]:translate-x-0 data-[expanded=true]:scale-125">

		<span class="flex items-center justify-center transition-transform duration-300 -translate-x-1.5 group-data-[expanded=true]:translate-x-0">
			<span class="block dark:hidden" aria-hidden="true">
				<span class="block size-3.5">
					<?php Icon::print('Moon'); ?>
				</span>
			</span>
			<span class="hidden dark:block" aria-hidden="true">
				<span class="block size-3.5">
					<?php Icon::print('Sun'); ?>
				</span>
			</span>
		</span>
	</button>
</div>