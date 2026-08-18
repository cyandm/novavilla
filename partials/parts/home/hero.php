<?php

use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;

$home_id = get_the_ID();
$use_default = get_field('home_top_hero_default', $home_id);
$use_default = $use_default === null ? true : (bool) $use_default;
$banner_image = get_field('home_top_hero_image', $home_id);
$banner_title = __('ویــــــلا داشته باش، قسطی پرداخت کن', 'novavilla');

if (! $use_default && empty($banner_image)) return;
?>

<section class="container">
	<?php if ($use_default) : ?>
		<div class="relative flex items-center justify-center gap-2.5 rounded-2xl bg-[#FFC400] py-2.5 md:py-4 px-3 overflow-hidden">
			<i class="absolute start-2 top-1/2 -translate-y-1/2 md:static md:translate-y-0 size-8 shrink-0 rotate-180 [&_svg]:size-full" aria-hidden="true">
				<?php Icon::print('banner-ornament'); ?>
			</i>
			<span class="text-xl leading-8 md:text-3xl md:leading-11 font-bold bg-[linear-gradient(170deg,#180C72_0%,#FF6739_100%)] bg-clip-text text-transparent whitespace-nowrap">
				<?php echo esc_html($banner_title); ?>
			</span>
			<i class="absolute end-2 top-1/2 -translate-y-1/2 md:static md:translate-y-0 size-8 shrink-0 rotate-180 [&_svg]:size-full" aria-hidden="true">
				<?php Icon::print('banner-ornament'); ?>
			</i>
		</div>
	<?php else : ?>
		<div class="w-full h-10 md:h-16 rounded-2xl overflow-hidden">
			<img src="<?php echo esc_url($banner_image); ?>" alt="<?php echo esc_attr($banner_title); ?>" class="w-full h-full object-cover" loading="eager" decoding="async" />
		</div>
	<?php endif; ?>
</section>