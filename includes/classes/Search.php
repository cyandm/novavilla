<?php

/**
 * Search: filter by search-type, and match product taxonomies + curated ACF fields.
 *
 * @package Cyan\Theme\Classes
 */

namespace Cyan\Theme\Classes;

class Search
{

	/** @var string[] Text ACF keys worth searching (skip gallery / relations / UI-only). */
	private const PRODUCT_SEARCH_META_KEYS = [
		'product_area',
		'product_dimensions',
		'product_rooms',
		'product_structure_type',
		'product_status',
		'product_feature_desc_1',
		'product_feature_desc_2',
		'product_feature_desc_3',
		'product_feature_desc_4',
		'product_feature_desc_5',
		'product_feature_desc_6',
		'product_feature_desc_7',
		'product_feature_desc_8',
		'product_feature_desc_9',
	];

	public static function init()
	{
		add_action('pre_get_posts', [__CLASS__, 'filterByType']);
		add_action('pre_get_posts', [__CLASS__, 'searchPerPage'], 20);
		add_filter('posts_search', [__CLASS__, 'expandProductSearch'], 10, 2);
	}

	/**
	 * Search results per page.
	 *
	 * @param \WP_Query $query
	 */
	public static function searchPerPage($query)
	{
		if (is_admin() || ! $query->is_main_query() || ! $query->is_search()) {
			return;
		}
		$query->set('posts_per_page', 8);
	}

	/**
	 * Filter search query by search-type (all / product / post).
	 *
	 * @param \WP_Query $query
	 */
	public static function filterByType($query)
	{
		if (is_admin() || ! $query->is_main_query() || ! $query->is_search()) {
			return;
		}
		$type = isset($_GET['search-type']) ? sanitize_text_field(wp_unslash($_GET['search-type'])) : '';
		if ($type === 'product') {
			$query->set('post_type', 'product');
		} elseif ($type === 'post') {
			$query->set('post_type', 'post');
		} else {
			$query->set('post_type', ['product', 'post']);
			$query->set('orderby', 'post_type');
			$query->set('order', 'DESC');
		}
	}

	/**
	 * Also match products via taxonomy names and curated ACF meta.
	 *
	 * @param string    $search
	 * @param \WP_Query $query
	 * @return string
	 */
	public static function expandProductSearch($search, $query)
	{
		if (is_admin() || ! $query->is_main_query() || ! $query->is_search() || $search === '') {
			return $search;
		}

		$type = isset($_GET['search-type']) ? sanitize_text_field(wp_unslash($_GET['search-type'])) : '';
		if ($type === 'post') {
			return $search;
		}

		$s = trim((string) $query->get('s'));
		if ($s === '') {
			return $search;
		}

		$product_ids = array_values(array_unique(array_merge(
			self::getProductIdsByTaxonomySearch($s),
			self::getProductIdsByAcfSearch($s)
		)));

		if (empty($product_ids)) {
			return $search;
		}

		global $wpdb;
		$ids_sql = implode(',', array_map('absint', $product_ids));
		$search_body = preg_replace('/^\s*AND\s+/i', '', $search);
		return " AND (({$wpdb->posts}.ID IN ({$ids_sql})) OR ({$search_body}))";
	}

	/**
	 * @param string $search
	 * @return int[]
	 */
	private static function getProductIdsByTaxonomySearch($search)
	{
		$needles = self::buildSearchNeedles($search);
		if (empty($needles)) {
			return [];
		}

		$term_ids = [];
		foreach ($needles as $needle) {
			$terms = get_terms([
				'taxonomy' => ['product_cat', 'product_tag'],
				'hide_empty' => true,
				'name__like' => $needle,
				'fields' => 'ids',
			]);
			if (! is_wp_error($terms) && ! empty($terms)) {
				$term_ids = array_merge($term_ids, $terms);
			}
		}

		$term_ids = array_values(array_unique(array_map('absint', $term_ids)));
		if (empty($term_ids)) {
			return [];
		}

		$object_ids = get_objects_in_term($term_ids, ['product_cat', 'product_tag']);
		if (is_wp_error($object_ids) || empty($object_ids)) {
			return [];
		}

		$products = get_posts([
			'post_type' => 'product',
			'post_status' => 'publish',
			'post__in' => array_map('absint', $object_ids),
			'posts_per_page' => 100,
			'fields' => 'ids',
			'no_found_rows' => true,
		]);

		return array_map('absint', $products);
	}

	/**
	 * One targeted postmeta query over a fixed key list (not all meta).
	 *
	 * @param string $search
	 * @return int[]
	 */
	private static function getProductIdsByAcfSearch($search)
	{
		$needles = self::buildSearchNeedles($search);
		if (empty($needles)) {
			return [];
		}

		global $wpdb;

		$key_placeholders = implode(',', array_fill(0, count(self::PRODUCT_SEARCH_META_KEYS), '%s'));
		$like_sql = [];
		$params = self::PRODUCT_SEARCH_META_KEYS;

		foreach ($needles as $needle) {
			$like_sql[] = 'pm.meta_value LIKE %s';
			$params[] = '%' . $wpdb->esc_like($needle) . '%';
		}

		$sql = "
			SELECT DISTINCT pm.post_id
			FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE p.post_type = 'product'
				AND p.post_status = 'publish'
				AND pm.meta_key IN ({$key_placeholders})
				AND (" . implode(' OR ', $like_sql) . ")
			LIMIT 100
		";

		$ids = $wpdb->get_col($wpdb->prepare($sql, $params));
		return array_map('absint', $ids ?: []);
	}

	/**
	 * @param string $search
	 * @return string[]
	 */
	private static function buildSearchNeedles($search)
	{
		$needles = [$search];
		$compact = preg_replace('/\s+/u', '', $search);
		if ($compact !== '' && $compact !== $search) {
			$needles[] = $compact;
		}

		$words = preg_split('/\s+/u', $search, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($words as $word) {
			if (mb_strlen($word) >= 2) {
				$needles[] = $word;
			}
		}

		return array_values(array_unique(array_filter($needles)));
	}
}
