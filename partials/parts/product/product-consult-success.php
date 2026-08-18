<?php

use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;
?>

<div class="flex items-center gap-3 rounded-3xl border border-cynBorderHover/40 bg-cynWhite/8 backdrop-blur-md p-4" role="status" aria-live="polite">
	<div class="size-11 shrink-0 rounded-full bg-[#4e9658] flex items-center justify-center text-white [&_svg]:size-5 [&_svg]:stroke-current [&_svg]:stroke-[2]">
		<?php Icon::print('check'); ?>
	</div>
	<div class="flex flex-col gap-1 min-w-0 flex-1">
		<span class="text-base md:text-xl font-semibold text-cynTextPrimary leading-6">
			<?php esc_html_e('درخواست شما با موفقیت ثبت شد.', 'novavilla'); ?>
		</span>
		<span class="text-xs md:text-sm font-light md:font-medium text-cynTextPrimary leading-5 md:leading-6">
			<?php esc_html_e('مشاورین ما در اسرع وقت با شما تماس خواهند گرفت.', 'novavilla'); ?>
		</span>
	</div>
</div>
