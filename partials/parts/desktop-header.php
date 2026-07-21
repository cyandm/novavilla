<?php

/**
 * Desktop Header
 * @package CyanThemeSetup
 */

use Cyan\Theme\Helpers\Templates;
use Cyan\Theme\Helpers\Icon;
?>

<section id="desktop-header" class="z-50">
	<div class="container flex justify-between items-center max-lg:[&>div]:flex-1 py-4 lg:py-8">

		<div class="mobile-menu flex lg:hidden">
			<div class="p-2.5 rounded-xl text-cynTextPrimary border border-cynBorderHover cursor-pointer" modal-opener data-modal-name="menu-modal">
				<i class="size-6 [&_svg]:scale-x-[-1] [&_svg]:transform [&_svg]:stroke-[1.5] text-cynBorderHover">
					<?php Icon::print('menu-burger-square-6') ?>
				</i>
			</div>
		</div>

		<div class="flex gap-11 items-center justify-center">

			<div class="logo flex justify-center items-center [&_img]:w-12 lg:[&_img]:w-8 [&_img]:h-auto">
				<?php the_custom_logo(); ?>
			</div>

			<div class="desktop-menu hidden lg:flex">
				<?php wp_nav_menu([
					'menu_id' => 'main-menu',
					'menu_class' => 'gap-8 text-sm font-semibold flex text-cynTextPrimary [&>li:hover]:text-cynTextPrimaryHover [&>li>ul>li:hover]:text-cynTextPrimaryHover [&_li]:flex [&_li]:items-center [&_li]:duration-200 [&_li]:transition-all [&_li_a_svg]:transition-all [&_li_a_svg]:duration-300 [&_li:hover_svg]:rotate-180',
					'depth' => '3',
					'theme_location' => 'header-menu',
					'container' => 'ul'
				]); ?>
			</div>

		</div>

		<div class="flex justify-end items-center gap-3">
			<?php Templates::getPart('searchbox', [
				'id' => 'desktop-header-search',
				'class' => 'hidden lg:flex items-center w-full max-w-[28rem]',
			]); ?>

			<?php Templates::getPart('theme-toggle'); ?>
		</div>
	</div>
</section>