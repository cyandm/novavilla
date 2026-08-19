<?php

use Cyan\Theme\Helpers\Icon;

$logo_footer = get_option('logo_footer');
$address_text = get_option('address_text');
$address_link = get_option('address_link');
$phone_number = get_option('phone_number');
$phone_number_support = get_option('phone_number_support');
$telegram_link = get_option('telegram_link');
$telegram_text = get_option('telegram_text');
$bale_link = get_option('bale_link');
$bale_text = get_option('bale_text');
$instagram_link = get_option('instagram_link');
$instagram_text = get_option('instagram_text');
$whatsapp_number = get_option('whatsapp_number');
$twitter_link = get_option('twitter_link');
$twitter_text = get_option('twitter_text');
$linkedin_link = get_option('linkedin_link');
$linkedin_text = get_option('linkedin_text');
$pinterest_link = get_option('pinterest_link');
$pinterest_text = get_option('pinterest_text');

$locations = [];
for ($i = 1; $i <= 6; $i++) {
	$image = get_option("location_image_$i");
	$link = get_option("location_link_$i");
	if ($image || $link) {
		$locations[] = [
			'image' => $image,
			'link' => $link,
		];
	}
}

$menu_class = "text-cynTextPrimary text-sm font-medium flex flex-col gap-3 [&>li]:w-fit [&>li]:flex [&>li]:items-center [&>li]:gap-2 [&>li]:before:content-[''] [&>li]:before:size-1.5 [&>li]:before:rounded-full [&>li]:before:bg-cynTextPrimary [&>li]:before:shrink-0 [&>li]:hover:before:bg-cynBorderHover [&>li]:before:transition-colors [&>li]:before:duration-300 [&>li[aria-current=page]]:before:bg-cynBorderHover [&_li]:whitespace-nowrap [&>li>a]:hover:text-cynTextPrimary/80 [&>li>a]:transition-colors [&>li>a]:duration-300";

$has_social = $telegram_link || $bale_link || $instagram_link || $whatsapp_number || $twitter_link || $linkedin_link || $pinterest_link;
$has_contact = $phone_number || $phone_number_support || $address_text || $locations;
?>

<section class="container">

	<div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8 py-16 md:py-14 px-10 bg-cynBgBase/80 rounded-4xl">

		<div class="order-1 lg:order-4 shrink-0 mx-auto lg:mx-0 -mt-32 lg:mt-0">
			<?php if ($logo_footer) : ?>
				<img src="<?php echo esc_url($logo_footer); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="w-36 md:w-44 h-auto object-contain">
			<?php else : ?>
				<div class="[&_img]:w-36 lg:[&_img]:w-44 [&_img]:h-auto">
					<?php the_custom_logo(); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="order-2 lg:contents flex flex-col md:flex-row md:items-start md:justify-between gap-8 w-full">

			<div class="flex gap-14 md:gap-11 w-full md:w-auto">
				<?php wp_nav_menu([
					'menu_id' => '',
					'menu_class' => $menu_class,
					'depth' => 1,
					'theme_location' => 'footer-menu-col-1',
					'container' => 'ul',
					'fallback_cb' => false,
				]); ?>

				<?php wp_nav_menu([
					'menu_id' => '',
					'menu_class' => $menu_class,
					'depth' => 1,
					'theme_location' => 'footer-menu-col-2',
					'container' => 'ul',
					'fallback_cb' => false,
				]); ?>
			</div>

			<?php if ($has_contact) : ?>
				<div class="w-full md:w-auto grid grid-cols-2 md:flex md:flex-col gap-5">

					<?php if ($address_text || $locations) : ?>
						<div class="md:order-2 flex flex-col gap-3 md:max-w-56">
							<?php if ($address_text) : ?>
								<div class="flex flex-col gap-1.5">
									<span class="text-sm font-semibold text-cynTextPrimary"><?php _e('آدرس', 'novavilla'); ?></span>
									<?php if ($address_link) : ?>
										<a href="<?php echo esc_url($address_link); ?>" target="_blank" rel="noopener noreferrer" class="text-cynTextPrimary/70 text-sm font-medium leading-6"><?php echo esc_html($address_text); ?></a>
									<?php else : ?>
										<p class="text-cynTextPrimary/70 text-sm font-medium leading-6"><?php echo esc_html($address_text); ?></p>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<?php if ($locations) : ?>
								<div class="flex items-center gap-2 flex-wrap">
									<?php foreach ($locations as $location) : ?>
										<?php
										$location_class = 'size-6 rounded-sm overflow-hidden flex items-center justify-center shrink-0';
										$location_image = !empty($location['image'])
											? '<img src="' . esc_url($location['image']) . '" alt="" class="size-full object-contain">'
											: '';
										?>
										<?php if (!empty($location['link'])) : ?>
											<a href="<?php echo esc_url($location['link']); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo $location_class; ?>">
												<?php echo $location_image; ?>
											</a>
										<?php else : ?>
											<span class="<?php echo $location_class; ?>">
												<?php echo $location_image; ?>
											</span>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ($phone_number || $phone_number_support) : ?>
						<div class="md:order-1 flex flex-col gap-1.5">
							<span class="text-sm font-semibold text-cynTextPrimary"><?php _e('شماره تماس', 'novavilla'); ?></span>
							<div class="flex flex-col gap-1">
								<?php if ($phone_number) : ?>
									<a href="tel:<?php echo esc_attr($phone_number); ?>" class="text-cynBlue text-sm font-medium tracking-wide"><?php echo esc_html($phone_number); ?></a>
								<?php endif; ?>
								<?php if ($phone_number_support) : ?>
									<a href="tel:<?php echo esc_attr($phone_number_support); ?>" class="text-cynBlue text-sm font-medium tracking-wide"><?php echo esc_html($phone_number_support); ?></a>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>

				</div>
			<?php endif; ?>

			<?php if ($has_social) : ?>
				<div class="flex flex-col gap-3 w-full md:max-w-56">
					<span class="text-sm font-semibold text-cynTextPrimary"><?php _e('شبکه‌های اجتماعی', 'novavilla'); ?></span>

					<ul class="flex flex-col gap-2.5">

						<?php if ($telegram_link) : ?>
							<li>
								<a href="<?php echo esc_url($telegram_link); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1 rounded-xl border border-transparent bg-cynBgSocial hover:bg-cynBgSocialHover px-0.5 py-1 hover:border-cynBgSocialBorder text-cynBgSocialText hover:text-cynBgSocialTextHover transition-colors duration-300">
									<i class="size-7 rounded-full transition-colors duration-300 flex items-center justify-center shrink-0">
										<?php Icon::print('Telegram'); ?>
									</i>
									<span class="text-sm font-medium truncate"><?php echo esc_html($telegram_text ?: $telegram_link); ?></span>
								</a>
							</li>
						<?php endif; ?>

						<?php if ($instagram_link) : ?>
							<li>
								<a href="<?php echo esc_url($instagram_link); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1 rounded-xl border border-transparent bg-cynBgSocial hover:bg-cynBgSocialHover px-0.5 py-1 hover:border-cynBgSocialBorder text-cynBgSocialText hover:text-cynBgSocialTextHover transition-colors duration-300">
									<i class="size-7 rounded-full transition-colors duration-300 flex items-center justify-center shrink-0">
										<?php Icon::print('Instagram'); ?>
									</i>
									<span class="text-sm font-medium truncate"><?php echo esc_html($instagram_text ?: $instagram_link); ?></span>
								</a>
							</li>
						<?php endif; ?>

						<?php if ($bale_link) : ?>
							<li>
								<a href="<?php echo esc_url($bale_link); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1 rounded-xl border border-transparent bg-cynBgSocial hover:bg-cynBgSocialHover px-0.5 py-1 hover:border-cynBgSocialBorder text-cynBgSocialText hover:text-cynBgSocialTextHover transition-colors duration-300">
									<i class="size-7 rounded-full transition-colors duration-300 flex items-center justify-center shrink-0">
										<?php echo file_get_contents(THEME_DIR . '/assets/icon/bale.svg'); ?>
									</i>
									<span class="text-sm font-medium truncate"><?php echo esc_html($bale_text ?: $bale_link); ?></span>
								</a>
							</li>
						<?php endif; ?>

						<?php if ($whatsapp_number) : ?>
							<li>
								<a href="https://wa.me/<?php echo esc_attr(preg_replace('/\D+/', '', $whatsapp_number)); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1 rounded-xl border border-transparent bg-cynBgSocial hover:bg-cynBgSocialHover px-0.5 py-1 hover:border-cynBgSocialBorder text-cynBgSocialText hover:text-cynBgSocialTextHover transition-colors duration-300">
									<i class="size-7 rounded-full transition-colors duration-300 flex items-center justify-center shrink-0">
										<?php Icon::print('Whatsup'); ?>
									</i>
									<span class="text-sm font-medium truncate"><?php echo esc_html($whatsapp_number); ?></span>
								</a>
							</li>
						<?php endif; ?>

						<?php if ($twitter_link) : ?>
							<li>
								<a href="<?php echo esc_url($twitter_link); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1 rounded-xl border border-transparent bg-cynBgSocial hover:bg-cynBgSocialHover px-0.5 py-1 hover:border-cynBgSocialBorder text-cynBgSocialText hover:text-cynBgSocialTextHover transition-colors duration-300">
									<i class="size-7 rounded-full transition-colors duration-300 flex items-center justify-center shrink-0">
										<?php echo file_get_contents(THEME_DIR . '/assets/icon/x.svg'); ?>
									</i>
									<span class="text-sm font-medium truncate"><?php echo esc_html($twitter_text ?: $twitter_link); ?></span>
								</a>
							</li>
						<?php endif; ?>

						<?php if ($linkedin_link) : ?>
							<li>
								<a href="<?php echo esc_url($linkedin_link); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1 rounded-xl border border-transparent bg-cynBgSocial hover:bg-cynBgSocialHover px-0.5 py-1 hover:border-cynBgSocialBorder text-cynBgSocialText hover:text-cynBgSocialTextHover transition-colors duration-300">
									<i class="size-7 rounded-full transition-colors duration-300 flex items-center justify-center shrink-0">
										<?php Icon::print('Linkedin'); ?>
									</i>
									<span class="text-sm font-medium truncate"><?php echo esc_html($linkedin_text ?: $linkedin_link); ?></span>
								</a>
							</li>
						<?php endif; ?>

						<?php if ($pinterest_link) : ?>
							<li>
								<a href="<?php echo esc_url($pinterest_link); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1 rounded-xl border border-transparent bg-cynBgSocial hover:bg-cynBgSocialHover px-0.5 py-1 hover:border-cynBgSocialBorder text-cynBgSocialText hover:text-cynBgSocialTextHover transition-colors duration-300">
									<i class="size-7 rounded-full transition-colors duration-300 flex items-center justify-center shrink-0">
										<?php Icon::print('Pinterest'); ?>
									</i>
									<span class="text-sm font-medium truncate"><?php echo esc_html($pinterest_text ?: $pinterest_link); ?></span>
								</a>
							</li>
						<?php endif; ?>

					</ul>
				</div>
			<?php endif; ?>

		</div>

	</div>

</section>

<section class="container text-cynTextPrimary text-xs font-medium text-center pb-6">
	<p class="whitespace-nowrap [&>a]:text-[#ff8f34]">طراحی و توسعه توسط <a href="https://cyandm.com">سایان</a></p>
</section>