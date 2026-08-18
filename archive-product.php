<?php

use Cyan\Theme\Helpers\Templates;

defined('ABSPATH') || exit;

get_header();

Templates::getPart('breadcrumb');
?>

<main class="container space-y-11 md:space-y-16">
	<?php Templates::getPart('product/product-archive'); ?>
</main>

<?php get_footer(); ?>
