<?php

/**
 * Desktop Header
 * @package CyanThemeSetup
 */

use Cyan\Theme\Helpers\Templates;
use Cyan\Theme\Helpers\Icon;
?>

<section id="site-header" class="relative z-30">
	<div class="container flex justify-between items-center max-lg:[&>div]:flex-1 py-4 lg:py-8">

		<div class="mobile-menu flex lg:hidden">
			<div class="p-2 rounded-xl text-cynTextPrimary border border-cynBorderHover cursor-pointer" modal-opener data-modal-name="menu-modal">
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
					'menu_class' => 'gap-5 text-sm font-semibold flex items-center text-cynTextPrimary [&>li]:px-2 [&>li]:py-1.5 [&>li]:transition-all [&>li]:duration-300 [&>li>a]:transition-all [&>li>a]:duration-300 [&>li:hover>a]:text-cynTextPrimaryHover [&>li[aria-current=page]>a]:text-cynTextPrimaryHover [&>li>ul>li:hover]:text-cynTextPrimaryHover [&>li>ul>li>a]:transition-all [&>li>ul>li>a]:duration-300 [&_li_a_svg]:transition-transform [&_li_a_svg]:duration-300 [&>li:hover>a_svg]:rotate-180 [&>li>ul>li:hover>a_svg]:rotate-90',
					'depth' => '3',
					'theme_location' => 'header-menu',
					'container' => 'ul'
				]); ?>
			</div>

		</div>

		<div class="flex justify-end items-stretch gap-3">
			<?php if (!is_search()) : ?>
				<?php Templates::getPart('searchbox', [
					'id' => 'desktop-header-search',
					'class' => 'hidden lg:flex items-center w-full max-w-[28rem]',
				]); ?>
			<?php endif; ?>

			<a href="tel:<?php echo get_option('phone_number'); ?>" class="primary-button !py-2.5 !ps-2.5 !pe-3.5 group">
				<span class="flex items-center gap-0.5 whitespace-nowrap">
					<i class="size-5 flex items-center justify-center [&_svg]:stroke-[1.5] [&_svg_g_path]:fill-cynTextSecondary group-hover:[&_svg_g_path]:fill-cynBlack">
						<?php Icon::print('Phone,-Call-11'); ?>
					</i>
					<?php _e('تماس بگیرید', 'novavilla'); ?>
				</span>
			</a>
		</div>
	</div>
</section>