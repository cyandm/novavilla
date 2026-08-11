<?php

/**
 * Product archive filters: area, rooms, price via GET + meta_query.
 *
 * @package Cyan\Theme\Classes
 */

namespace Cyan\Theme\Classes;

class ProductFilter
{

	public static function init()
	{
		add_action('pre_get_posts', [__CLASS__, 'apply']);
	}

	/**
	 * Apply filter meta_query on product archive / product tax archives.
	 *
	 * @param \WP_Query $query
	 */
	public static function apply($query)
	{
		if (is_admin() || ! $query->is_main_query()) {
			return;
		}
		if (! $query->is_post_type_archive('product') && ! $query->is_tax(['product_cat', 'product_tag'])) {
			return;
		}

		$query->set('posts_per_page', 15);

		$meta_query = self::buildMetaQueryFromRequest();
		if (empty($meta_query)) {
			return;
		}

		$existing = $query->get('meta_query');
		if (! empty($existing) && is_array($existing)) {
			$merged = ['relation' => 'AND', $existing];
			foreach ($meta_query as $key => $clause) {
				if ($key === 'relation' || ! is_array($clause)) {
					continue;
				}
				$merged[] = $clause;
			}
			$query->set('meta_query', $merged);
			return;
		}

		$query->set('meta_query', $meta_query);
	}

	/**
	 * Min/max product_price for filter slider (optionally scoped to a taxonomy term).
	 *
	 * @param array{term_id?: int, taxonomy?: string} $args
	 * @return array{min: int, max: int}|null
	 */
	public static function getPriceBounds(array $args = [])
	{
		global $wpdb;

		$join = '';
		$tax_sql = '';
		if (! empty($args['term_id']) && ! empty($args['taxonomy'])) {
			$join = " INNER JOIN {$wpdb->term_relationships} tr ON tr.object_id = p.ID INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
			$tax_sql = $wpdb->prepare(' AND tt.taxonomy = %s AND tt.term_id = %d', $args['taxonomy'], (int) $args['term_id']);
		}

		$row = $wpdb->get_row(
			"SELECT MIN(CAST(pm.meta_value AS UNSIGNED)) AS min_price, MAX(CAST(pm.meta_value AS UNSIGNED)) AS max_price
			FROM {$wpdb->postmeta} pm
			INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id{$join}
			WHERE pm.meta_key = 'product_price'
			AND p.post_type = 'product'
			AND p.post_status = 'publish'
			AND pm.meta_value REGEXP '^[0-9]+$'{$tax_sql}"
		);

		if (! $row || $row->min_price === null || $row->max_price === null) {
			return null;
		}

		return ['min' => (int) $row->min_price, 'max' => (int) $row->max_price];
	}

	/**
	 * Slider step from price span.
	 *
	 * @param int $floor
	 * @param int $ceiling
	 * @return int
	 */
	public static function getPriceStep(int $floor, int $ceiling)
	{
		$span = max(0, $ceiling - $floor);
		if ($span >= 10000000) {
			return 1000000;
		}
		if ($span >= 1000000) {
			return 100000;
		}

		return max(1, (int) floor($span / 50));
	}

	/**
	 * Build meta_query from GET params (reusable for search later).
	 *
	 * @return array
	 */
	public static function buildMetaQueryFromRequest()
	{
		$meta_query = ['relation' => 'AND'];

		$area = isset($_GET['area']) ? sanitize_key(wp_unslash($_GET['area'])) : '';
		$area_clause = self::areaClause($area);
		if ($area_clause) {
			$meta_query[] = $area_clause;
		}

		$rooms = isset($_GET['rooms']) ? sanitize_key(wp_unslash($_GET['rooms'])) : '';
		$rooms_clause = self::roomsClause($rooms);
		if ($rooms_clause) {
			$meta_query[] = $rooms_clause;
		}

		$price_min = isset($_GET['price_min']) ? (int) wp_unslash($_GET['price_min']) : 0;
		$price_max = isset($_GET['price_max']) ? (int) wp_unslash($_GET['price_max']) : 0;
		$price_clause = self::priceClause($price_min, $price_max);
		if ($price_clause) {
			$meta_query[] = $price_clause;
		}

		return count($meta_query) > 1 ? $meta_query : [];
	}

	/**
	 * @param string $area
	 * @return array|null
	 */
	private static function areaClause($area)
	{
		$map = [
			'under_80' => ['key' => 'product_area', 'value' => 80, 'type' => 'NUMERIC', 'compare' => '<'],
			'80_150' => ['key' => 'product_area', 'value' => [80, 150], 'type' => 'NUMERIC', 'compare' => 'BETWEEN'],
			'150_200' => ['key' => 'product_area', 'value' => [150, 200], 'type' => 'NUMERIC', 'compare' => 'BETWEEN'],
			'above_200' => ['key' => 'product_area', 'value' => 200, 'type' => 'NUMERIC', 'compare' => '>'],
		];

		return $map[$area] ?? null;
	}

	/**
	 * @param string $rooms
	 * @return array|null
	 */
	private static function roomsClause($rooms)
	{
		if (in_array($rooms, ['1', '2', '3'], true)) {
			return ['key' => 'product_rooms', 'value' => (int) $rooms, 'type' => 'NUMERIC', 'compare' => '='];
		}
		if ($rooms === '4plus') {
			return ['key' => 'product_rooms', 'value' => 4, 'type' => 'NUMERIC', 'compare' => '>='];
		}

		return null;
	}

	/**
	 * @param int $min
	 * @param int $max
	 * @return array|null
	 */
	private static function priceClause($min, $max)
	{
		$min = max(0, $min);
		$max = max(0, $max);

		if ($min > 0 && $max > 0) {
			if ($min > $max) {
				[$min, $max] = [$max, $min];
			}
			return ['key' => 'product_price', 'value' => [$min, $max], 'type' => 'NUMERIC', 'compare' => 'BETWEEN'];
		}
		if ($min > 0) {
			return ['key' => 'product_price', 'value' => $min, 'type' => 'NUMERIC', 'compare' => '>='];
		}
		if ($max > 0) {
			return ['key' => 'product_price', 'value' => $max, 'type' => 'NUMERIC', 'compare' => '<='];
		}

		return null;
	}
}
