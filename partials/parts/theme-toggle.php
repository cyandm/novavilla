<?php

/**
 * Theme toggle — floating assistant-style control
 * @package CyanThemeSetup
 */

use Cyan\Theme\Helpers\Icon;
?>

<div theme-toggle-dock data-expanded="false" class="group/dock fixed start-0 top-1/2 z-[100] -translate-y-1/2 flex flex-col items-center gap-1.5">

	<div class="pointer-events-none select-none translate-x-2/5 xl:translate-x-1/5 transition-all duration-300 ease-out group-data-[expanded=true]/dock:translate-x-0">
		<span class="dark:hidden block [writing-mode:vertical-rl] [text-orientation:mixed] rotate-180 text-[10px] md:text-xs font-medium tracking-wide text-cynTextPrimary whitespace-nowrap">
			<?php esc_html_e('حالت شب', 'novavilla'); ?>
		</span>
		<span class="hidden dark:block [writing-mode:vertical-rl] [text-orientation:mixed] rotate-180 text-[10px] md:text-xs font-medium tracking-wide text-cynTextPrimary/70 whitespace-nowrap">
			<?php esc_html_e('حالت روز', 'novavilla'); ?>
		</span>
	</div>

	<button type="button" theme-toggle aria-label="فعال کردن تم تیره" aria-pressed="false" aria-expanded="false" data-expanded="false" class="group theme-toggle-btn inline-flex size-8 xl:size-9 items-center justify-center rounded-e-xl rounded-s-none border border-s-0 border-cynBorder bg-cynBgItem/70 backdrop-blur-lg text-cynSvg transition-all duration-300 ease-out hover:text-cynSvgHover hover:scale-125 active:scale-150 translate-x-2/5 xl:translate-x-1/5 data-[expanded=true]:translate-x-0 data-[expanded=true]:scale-125">

		<span class="flex items-center justify-center transition-transform duration-300 -translate-x-1.5 xl:-translate-x-0.5 group-data-[expanded=true]:translate-x-0">
			<span class="block dark:hidden" aria-hidden="true">
				<span class="theme-toggle-icon block size-4 xl:size-5 [&_svg]:size-full">
					<?php Icon::print('Moon'); ?>
				</span>
			</span>
			<span class="hidden dark:block" aria-hidden="true">
				<span class="theme-toggle-icon block size-4 xl:size-5 [&_svg]:size-full">
					<?php Icon::print('Sun'); ?>
				</span>
			</span>
		</span>
	</button>
</div>