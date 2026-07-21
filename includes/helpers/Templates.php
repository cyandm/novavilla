<?php

/**
 * Templates helper
 * for every folder on partial you must create a function
 * @package CyanTheme
 */

namespace Cyan\Theme\Helpers;

class Templates
{

	public static function getPart($partial, $args = [])
	{
		if (! empty($args) && is_array($args)) {
			set_query_var('args', $args);
		}
		get_template_part('partials/parts/' . $partial);
	}

	public static function getCard($partial, $args = [])
	{
		if (! empty($args) && is_array($args)) {
			set_query_var('args', $args);
		}
		get_template_part('partials/cards/' . $partial);
	}
}
