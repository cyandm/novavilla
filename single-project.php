<?php

use Cyan\Theme\Helpers\Templates;

defined('ABSPATH') || exit;

get_header();
Templates::getPart('breadcrumb');
?>

<main class="single-project">
	<?php Templates::getPart('project/single-project'); ?>
</main>

<?php get_footer(); ?>
