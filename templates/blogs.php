<?php
/* Template Name: Blogs */

use Cyan\Theme\Helpers\Templates;

get_header();
?>

<?php Templates::getPart('breadcrumb'); ?>

<?php Templates::getPart('blogs'); ?>

<?php get_footer(); ?>
