<?php

use Cyan\Theme\Helpers\Templates;

defined('ABSPATH') || exit;

get_header();
Templates::getPart('breadcrumb');
?>

<main class="single-product">
	<?php Templates::getPart('product/single-product'); ?>
</main>

<?php get_footer(); ?>
