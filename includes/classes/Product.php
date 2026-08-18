<?php

/**
 * Product helpers: related products for the single product page.
 *
 * @package Cyan\Theme\Classes
 */

namespace Cyan\Theme\Classes;

class Product
{
	/**
	 * Related product IDs: ACF picks first, then similar ACF meta, category, tag.
	 *
	 * @param list<int> $exclude_ids
	 * @return list<int>
	 */
	public static function getRelatedProductIds(int $product_id, int $limit = 4, array $exclude_ids = []): array
	{
		$exclude = array_values(array_unique(array_merge([$product_id], array_map('intval', $exclude_ids))));
		$acf_ids = array_values(array_filter(self::getAcfProductIds($product_id, 'product_similar'), static fn(int $id): bool => ! in_array($id, $exclude, true)));

		if (count($acf_ids) >= $limit) {
			return array_slice($acf_ids, 0, $limit);
		}

		$ids = $acf_ids;
		$not_in = static function () use ($exclude, &$ids): array {
			return array_values(array_unique(array_merge($exclude, $ids)));
		};

		$rooms = get_field('product_rooms', $product_id);
		if (count($ids) < $limit && $rooms !== null && $rooms !== '' && is_numeric($rooms)) {
			$ids = self::mergeRelatedProductIds($ids, self::queryProductIds(['meta_query' => [['key' => 'product_rooms', 'value' => (int) $rooms, 'type' => 'NUMERIC', 'compare' => '=']]], $limit - count($ids), $not_in()));
		}

		$area = get_field('product_area', $product_id);
		if (count($ids) < $limit && $area !== null && $area !== '' && is_numeric($area) && (float) $area > 0) {
			$area_val = (float) $area;
			$delta = max(10, $area_val * 0.2);
			$ids = self::mergeRelatedProductIds($ids, self::queryProductIds(['meta_query' => [['key' => 'product_area', 'value' => [$area_val - $delta, $area_val + $delta], 'type' => 'NUMERIC', 'compare' => 'BETWEEN']]], $limit - count($ids), $not_in()));
		}

		$structure_type = get_field('product_structure_type', $product_id);
		if (count($ids) < $limit && is_string($structure_type) && $structure_type !== '') {
			$ids = self::mergeRelatedProductIds($ids, self::queryProductIds(['meta_query' => [['key' => 'product_structure_type', 'value' => $structure_type, 'compare' => '=']]], $limit - count($ids), $not_in()));
		}

		$price = get_field('product_price', $product_id);
		if (count($ids) < $limit && $price !== null && $price !== '' && is_numeric($price) && (float) $price > 0) {
			$price_val = (float) $price;
			$delta = $price_val * 0.2;
			$ids = self::mergeRelatedProductIds($ids, self::queryProductIds(['meta_query' => [['key' => 'product_price', 'value' => [$price_val - $delta, $price_val + $delta], 'type' => 'NUMERIC', 'compare' => 'BETWEEN']]], $limit - count($ids), $not_in()));
		}

		$categories = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'ids']);
		if (count($ids) < $limit && ! empty($categories) && ! is_wp_error($categories)) {
			$ids = self::mergeRelatedProductIds($ids, self::queryProductIds(['tax_query' => [['taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $categories]]], $limit - count($ids), $not_in()));
		}

		$tags = wp_get_post_terms($product_id, 'product_tag', ['fields' => 'ids']);
		if (count($ids) < $limit && ! empty($tags) && ! is_wp_error($tags)) {
			$ids = self::mergeRelatedProductIds($ids, self::queryProductIds(['tax_query' => [['taxonomy' => 'product_tag', 'field' => 'term_id', 'terms' => $tags]]], $limit - count($ids), $not_in()));
		}

		return array_slice($ids, 0, $limit);
	}

	/**
	 * Suggested product IDs: ACF picks first, then category, then tag.
	 *
	 * @param list<int> $exclude_ids
	 * @return list<int>
	 */
	public static function getSuggestedProductIds(int $product_id, int $limit = 4, array $exclude_ids = []): array
	{
		$exclude = array_values(array_unique(array_merge([$product_id], array_map('intval', $exclude_ids))));
		$acf_ids = array_values(array_filter(self::getAcfProductIds($product_id, 'product_suggested'), static fn(int $id): bool => ! in_array($id, $exclude, true)));

		if (count($acf_ids) >= $limit) {
			return array_slice($acf_ids, 0, $limit);
		}

		$ids = $acf_ids;
		$not_in = static function () use ($exclude, &$ids): array {
			return array_values(array_unique(array_merge($exclude, $ids)));
		};

		$categories = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'ids']);
		if (count($ids) < $limit && ! empty($categories) && ! is_wp_error($categories)) {
			$ids = self::mergeRelatedProductIds($ids, self::queryProductIds(['tax_query' => [['taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $categories]]], $limit - count($ids), $not_in()));
		}

		$tags = wp_get_post_terms($product_id, 'product_tag', ['fields' => 'ids']);
		if (count($ids) < $limit && ! empty($tags) && ! is_wp_error($tags)) {
			$ids = self::mergeRelatedProductIds($ids, self::queryProductIds(['tax_query' => [['taxonomy' => 'product_tag', 'field' => 'term_id', 'terms' => $tags]]], $limit - count($ids), $not_in()));
		}

		return array_slice($ids, 0, $limit);
	}

	/**
	 * @return list<int>
	 */
	private static function getAcfProductIds(int $product_id, string $field): array
	{
		if (! function_exists('get_field')) return [];

		$selected = get_field($field, $product_id);
		if (empty($selected)) return [];

		$ids = [];
		foreach (is_array($selected) ? $selected : [(int) $selected] as $raw_id) {
			$id = (int) $raw_id;
			if ($id <= 0 || $id === $product_id) continue;
			if (get_post_type($id) !== 'product' || get_post_status($id) !== 'publish') continue;
			if (! in_array($id, $ids, true)) $ids[] = $id;
		}

		return $ids;
	}

	/**
	 * @param array<string, mixed> $args
	 * @param list<int> $not_in
	 * @return list<int>
	 */
	private static function queryProductIds(array $args, int $needed, array $not_in): array
	{
		if ($needed <= 0) return [];

		$query = new \WP_Query(array_merge([
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => $needed,
			'post__not_in' => $not_in !== [] ? $not_in : [0],
			'fields' => 'ids',
			'no_found_rows' => true,
			'ignore_sticky_posts' => true,
		], $args));

		return array_map('intval', $query->posts);
	}

	/**
	 * @param list<int> $existing
	 * @param list<int|string> $new
	 * @return list<int>
	 */
	private static function mergeRelatedProductIds(array $existing, array $new): array
	{
		foreach ($new as $raw_id) {
			$id = (int) $raw_id;
			if ($id > 0 && ! in_array($id, $existing, true)) $existing[] = $id;
		}

		return $existing;
	}
}
