<?php /* Template Name: Home */ ?>

<?php

use Cyan\Theme\Helpers\Templates;

get_header(); ?>

<main class="relative">
    <?php Templates::getPart('home/hero'); ?>
    <?php Templates::getPart('home/category'); ?>
    <?php Templates::getPart('home/cta'); ?>
    <?php Templates::getPart('home/3d-structure'); ?>
    <?php Templates::getPart('home/about-us'); ?>
    <?php Templates::getPart('home/achievements'); ?>
</main>

<?php get_footer(); ?>