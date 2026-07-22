<?php

use Cyan\Theme\Helpers\Icon;

$address_link = get_option('address_link');
$telegram_link = get_option('telegram_link');
$telegram_text = get_option('telegram_text');
$bale_link = get_option('bale_link');
$bale_text = get_option('bale_text');
$whatsapp_number = get_option('whatsapp_number');
$twitter_link = get_option('twitter_link');
$twitter_text = get_option('twitter_text');
$pinterest_link = get_option('pinterest_link');
$pinterest_text = get_option('pinterest_text');
$logo_mobile_menu = get_option('logo_mobile_menu');
$instagram_link = get_option('instagram_link');
$instagram_text = get_option('instagram_text');
?>

<section class="mobile-menu-scroll h-screen bg-cynBgBase p-5 w-0 fixed inset-0 z-50 opacity-0 pointer-events-none overflow-y-auto data-[active='true']:w-full data-[active='true']:opacity-100 data-[active='true']:pointer-events-auto duration-500" modal data-modal-name="menu-modal" data-modal-layer="drawer" data-active="false">

	<div class="flex justify-between items-center">

		<div class="flex justify-center items-center text-cynTextPrimaryHover" modal-closer data-modal-name="menu-modal">
			<i class="size-8 flex items-center justify-center [&_svg]:stroke-[1.5]">
				<?php Icon::print('Arrow,-Forward'); ?>
			</i>
			<span class="text-sm font-medium"><?php _e('بستن', 'novavilla'); ?></span>
		</div>

		<?php if ($logo_mobile_menu) : ?>
			<div class="logo flex justify-center items-center [&_img]:w-12 lg:[&_img]:w-8 [&_img]:h-auto"><img src="<?php echo $logo_mobile_menu ?>" alt="logo" class="w-full h-full object-contain"></div>
		<?php else : ?>
			<div class="logo flex justify-center items-center [&_img]:w-12 lg:[&_img]:w-8 [&_img]:h-auto"><?php the_custom_logo() ?></div>
		<?php endif; ?>

	</div>

	<div class="mt-2.5">

		<?php wp_nav_menu([
			'menu_id' => 'mobile-menu',
			'menu_class' => 'gap-0.5 [&>li]:border-t [&>li]:border-cynBorder [&>li]:first:border-t-0 flex-col text-cynTextPrimary [&_li_a]:flex [&_li_a]:py-3 [&_li_a]:w-full text-base font-medium [&_li_ul]:px-3',
			'depth' => '3',
			'theme_location' => 'mobile-menu',
			'container' => 'ul'
		]); ?>

	</div>

	<div class="flex gap-2 flex-col text-cynTextPrimary text-sm font-medium mt-8 mb-12">

		<?php if ($whatsapp_number || $twitter_link || $telegram_link || $bale_link || $instagram_accounts || $pinterest_link) : ?>

			<p class="text-sm font-semibold"><?php _e('شبکه های اجتماعی', 'taghechian'); ?></p>

			<div class="flex gap-3">

				<?php if ($bale_link) : ?>
					<a href="<?php echo $bale_link ?>" class="bg-cynBgItem rounded-xl p-2 flex items-center gap-1">
						<i class="size-6 flex items-center justify-center text-cynTextPrimary">
							<?php echo file_get_contents(THEME_DIR . '/assets/icon/bale.svg'); ?>
						</i>
					</a>
				<?php endif; ?>

				<?php if ($whatsapp_number) : ?>
					<a href="<?php echo $whatsapp_number ?>" class="bg-cynBgItem rounded-xl p-2 flex items-center gap-1">
						<i class="size-6 flex items-center justify-center text-cynTextPrimary">
							<?php Icon::print('Whatsup'); ?>
						</i>
					</a>
				<?php endif; ?>

				<?php if ($telegram_link) : ?>
					<a href="<?php echo $telegram_link ?>" class="bg-cynBgItem rounded-xl p-2 flex items-center gap-1">
						<i class="size-6 flex items-center justify-center text-cynTextPrimary">
							<?php Icon::print('Telegram'); ?>
						</i>
					</a>
				<?php endif; ?>

				<?php if ($instagram_link) : ?>
					<a href="<?php echo $instagram_link ?>" class="bg-cynBgItem rounded-xl p-2 flex items-center gap-1">
						<i class="size-6 flex items-center justify-center text-cynTextPrimary">
							<?php Icon::print('Instagram'); ?>
						</i>
					</a>
				<?php endif; ?>

				<?php if ($twitter_link) : ?>
					<a href="<?php echo $twitter_link ?>" class="bg-cynBgItem rounded-xl p-2 flex items-center justify-center gap-1 size-10">
						<i class="size-5 flex items-center justify-center text-cynTextPrimary/80 p-0.5">
							<?php echo file_get_contents(THEME_DIR . '/assets/icon/x.svg'); ?>
						</i>
					</a>
				<?php endif; ?>

				<?php if ($pinterest_link) : ?>
					<a href="<?php echo $pinterest_link ?>" class="bg-cynBgItem rounded-xl p-2 flex items-center gap-1">
						<i class="size-6 flex items-center justify-center text-cynTextPrimary">
							<?php Icon::print('Pinterest'); ?>
						</i>
					</a>
				<?php endif; ?>

			</div>
		<?php endif; ?>
	</div>

</section>