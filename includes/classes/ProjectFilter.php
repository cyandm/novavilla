<?php

/**
 * Project archive filters: category, status, city via GET.
 *
 * @package Cyan\Theme\Classes
 */

namespace Cyan\Theme\Classes;

class ProjectFilter
{
	public static function init()
	{
		add_action('pre_get_posts', [__CLASS__, 'apply']);
	}

	public static function apply($query)
	{
		if (is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive('project')) {
			return;
		}

		$tax_query = self::buildTaxQueryFromRequest();
		if (! empty($tax_query)) {
			$query->set('tax_query', $tax_query);
		}

		$meta_query = self::buildMetaQueryFromRequest();
		if (! empty($meta_query)) {
			$query->set('meta_query', $meta_query);
		}
	}

	public static function buildTaxQueryFromRequest()
	{
		$tax_query = ['relation' => 'AND'];

		$pcat = isset($_GET['pcat']) ? sanitize_title(wp_unslash($_GET['pcat'])) : '';
		if ($pcat && $pcat !== 'all') {
			$tax_query[] = ['taxonomy' => 'project_cat', 'field' => 'slug', 'terms' => $pcat];
		}

		$pstate = isset($_GET['pstate']) ? sanitize_title(wp_unslash($_GET['pstate'])) : '';
		if ($pstate && $pstate !== 'all') {
			$tax_query[] = ['taxonomy' => 'project_state', 'field' => 'slug', 'terms' => $pstate];
		}

		return count($tax_query) > 1 ? $tax_query : [];
	}

	public static function buildMetaQueryFromRequest()
	{
		$city = isset($_GET['city']) ? sanitize_text_field(wp_unslash($_GET['city'])) : '';
		if ($city === '') {
			return [];
		}

		return ['relation' => 'AND', ['key' => 'project_location', 'value' => $city, 'compare' => 'LIKE']];
	}

	public static function currentQueryArgs()
	{
		$args = [];
		$pcat = isset($_GET['pcat']) ? sanitize_title(wp_unslash($_GET['pcat'])) : '';
		$pstate = isset($_GET['pstate']) ? sanitize_title(wp_unslash($_GET['pstate'])) : '';
		$city = isset($_GET['city']) ? sanitize_text_field(wp_unslash($_GET['city'])) : '';
		if ($pcat && $pcat !== 'all') $args['pcat'] = $pcat;
		if ($pstate && $pstate !== 'all') $args['pstate'] = $pstate;
		if ($city !== '') $args['city'] = $city;
		return $args;
	}
}
