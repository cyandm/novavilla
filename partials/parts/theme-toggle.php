<?php

/**
 * Theme toggle button
 * @package CyanThemeSetup
 */

use Cyan\Theme\Helpers\Icon;
?>

<button
	type="button"
	theme-toggle
	aria-label="فعال کردن تم تیره"
	aria-pressed="false"
	class="inline-flex size-11 items-center justify-center rounded-full border border-cynBorder bg-cynBgItem text-cynSvg transition-colors hover:text-cynSvgHover">
	<span class="block dark:hidden" aria-hidden="true">
		<span class="block h-5 w-5">
			<?php Icon::print('Moon'); ?>
		</span>
	</span>
	<span class="hidden dark:block" aria-hidden="true">
		<span class="block h-5 w-5">
			<?php Icon::print('Sun'); ?>
		</span>
	</span>
</button>