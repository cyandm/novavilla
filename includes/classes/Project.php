<?php

/**
 * Project helpers: related projects for the single project page.
 *
 * @package Cyan\Theme\Classes
 */

namespace Cyan\Theme\Classes;

class Project
{
	public static function getRelatedProjectIds(int $project_id, int $limit = 4): array
	{
		$exclude = [$project_id];
		$acf_ids = array_values(array_filter(self::getAcfProjectIds($project_id, 'project_similar'), static fn(int $id): bool => ! in_array($id, $exclude, true)));
		if (count($acf_ids) >= $limit) return array_slice($acf_ids, 0, $limit);

		$ids = $acf_ids;
		$not_in = array_values(array_unique(array_merge($exclude, $ids))) ?: [0];
		$categories = wp_get_post_terms($project_id, 'project_cat', ['fields' => 'ids']);
		if (count($ids) < $limit && ! empty($categories) && ! is_wp_error($categories)) {
			$ids = self::mergeIds($ids, self::queryProjectIds(['tax_query' => [['taxonomy' => 'project_cat', 'field' => 'term_id', 'terms' => $categories]]], $limit - count($ids), $not_in));
			$not_in = array_values(array_unique(array_merge($exclude, $ids))) ?: [0];
		}

		if (count($ids) < $limit) {
			$ids = self::mergeIds($ids, self::queryProjectIds([], $limit - count($ids), $not_in));
		}

		return array_slice($ids, 0, $limit);
	}

	private static function getAcfProjectIds(int $project_id, string $field): array
	{
		if (! function_exists('get_field')) return [];
		$selected = get_field($field, $project_id);
		if (empty($selected)) return [];
		$ids = [];
		foreach (is_array($selected) ? $selected : [(int) $selected] as $raw_id) {
			$id = (int) $raw_id;
			if ($id <= 0 || $id === $project_id) continue;
			if (get_post_type($id) !== 'project' || get_post_status($id) !== 'publish') continue;
			if (! in_array($id, $ids, true)) $ids[] = $id;
		}
		return $ids;
	}

	private static function queryProjectIds(array $args, int $needed, array $not_in): array
	{
		if ($needed <= 0) return [];
		$query = new \WP_Query(array_merge(['post_type' => 'project', 'post_status' => 'publish', 'posts_per_page' => $needed, 'post__not_in' => $not_in !== [] ? $not_in : [0], 'fields' => 'ids', 'no_found_rows' => true, 'ignore_sticky_posts' => true], $args));
		return array_map('intval', $query->posts);
	}

	private static function mergeIds(array $existing, array $new): array
	{
		foreach ($new as $raw_id) {
			$id = (int) $raw_id;
			if ($id > 0 && ! in_array($id, $existing, true)) $existing[] = $id;
		}
		return $existing;
	}
}
