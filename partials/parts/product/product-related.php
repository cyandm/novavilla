<?php

use Cyan\Theme\Classes\Product;
use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

defined('ABSPATH') || exit;

$post_id = (int) get_the_ID();
$related_ids = Product::getRelatedProductIds($post_id);
$suggested_ids = Product::getSuggestedProductIds($post_id, 4, $related_ids);

$sections = [
	['title' => __('محصولات مشابه', 'novavilla'), 'ids' => $related_ids, 'nav' => 'relatedProducts'],
	['title' => __('شاید بپسندید', 'novavilla'), 'ids' => $suggested_ids, 'nav' => 'suggestedProducts'],
];
?>

<?php foreach ($sections as $section) :
	if ($section['ids'] === []) continue;
	$nav = $section['nav'];
	?>
	<section class="container flex flex-col gap-3 md:gap-5 my-11 lg:my-16">
		<div class="flex items-center justify-between gap-3">
			<h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12">
				<?php echo esc_html($section['title']); ?>
			</h2>
			<div class="flex items-center gap-2 lg:hidden">
				<button type="button" id="<?php echo esc_attr($nav); ?>Prev" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('قبلی', 'novavilla'); ?>">
					<i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
						<?php Icon::print('Arrow-19'); ?>
					</i>
				</button>
				<button type="button" id="<?php echo esc_attr($nav); ?>Next" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('بعدی', 'novavilla'); ?>">
					<i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
						<?php Icon::print('Arrow-27'); ?>
					</i>
				</button>
			</div>
		</div>

		<div class="hidden lg:grid lg:grid-cols-4 gap-2.5">
			<?php foreach ($section['ids'] as $product_id) : ?>
				<?php Templates::getCard('product', ['post-id' => $product_id]); ?>
			<?php endforeach; ?>
		</div>

		<div class="lg:hidden">
			<swiper-container class="w-full" slides-per-view="1" space-between="12" loop="true" pagination="false" navigation="true" navigation-next-el="#<?php echo esc_attr($nav); ?>Next" navigation-prev-el="#<?php echo esc_attr($nav); ?>Prev" breakpoints='{"768": {"slidesPerView": 2}}'>
				<?php foreach ($section['ids'] as $product_id) : ?>
					<swiper-slide>
						<?php Templates::getCard('product', ['post-id' => $product_id]); ?>
					</swiper-slide>
				<?php endforeach; ?>
			</swiper-container>
		</div>
	</section>
<?php endforeach; ?>
