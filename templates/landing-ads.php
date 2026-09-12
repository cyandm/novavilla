<?php

/**
 * Template Name: Landing Ads
 */

use Cyan\Theme\Helpers\Templates;

$faq_place = get_field('ads_faq_place');
$faq_place_slug = '';
if (is_object($faq_place) && !empty($faq_place->slug)) $faq_place_slug = $faq_place->slug;
elseif (is_array($faq_place) && !empty($faq_place['slug'])) $faq_place_slug = $faq_place['slug'];
elseif (is_numeric($faq_place)) {
	$term = get_term((int) $faq_place);
	if ($term && !is_wp_error($term)) $faq_place_slug = $term->slug;
} elseif (is_string($faq_place)) {
	$faq_place_slug = $faq_place;
}

$installment_price = (float) get_field('ads_installment_price');
if ($installment_price <= 0) {
	$cheapest = get_posts(['post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 1, 'meta_key' => 'product_price', 'orderby' => 'meta_value_num', 'order' => 'ASC', 'fields' => 'ids']);
	if (!empty($cheapest)) $installment_price = (float) get_field('product_price', $cheapest[0]);
}

get_header();
?>

<main class="container space-y-8 lg:space-y-16 pb-8 lg:pb-16">

	<?php Templates::getPart('landing-ads/hero'); ?>

	<?php Templates::getPart('landing-ads/products'); ?>

	<?php Templates::getPart('landing-ads/cta-meterage'); ?>

	<?php Templates::getPart('landing-ads/features'); ?>

	<?php Templates::getPart('product/product-installment', [
		'price' => $installment_price,
		'wrap_container' => false,
		'include_payment' => true,
	]); ?>

	<?php Templates::getPart('landing-ads/visit'); ?>

	<?php Templates::getPart('home/3d-structure', ['wrap_container' => false]); ?>

	<?php Templates::getPart('faq', [
		'faq_place' => $faq_place_slug,
		'faq_title' => (string) get_field('ads_faq_title'),
		'faq_button' => get_field('ads_faq_button'),
	]); ?>

	<?php Templates::getPart('landing-ads/bottom-cta'); ?>

</main>

<?php get_footer(); ?>