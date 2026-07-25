<?php
/*
Template Name: 404
Description: A template for displaying a 404 error page.
More information at https://developer.wordpress.org/themes/templates/template-hierarchy/#404-not-found-hierarchy
*/
?>

<?php get_header(); ?>

<main class="container mx-auto px-4">
	<section class="mt-14 text-center">


		<div class="flex justify-center">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/image/404.webp" class="w-full md:w-3/4">
		</div>


		<p class="text-2xl md:text-4xl font-bold text-cynTextPrimary">
			<?php _e('متاسفیم!', 'taghechian'); ?>
		</p>

		<p class="text-base md:text-2xl font-normal text-cynTextPrimary mt-2">
			<?php _e('صفحه موردنظر یافت نشد', 'taghechian'); ?>
		</p>

		<div class="mt-3 flex justify-center">
			<a href="/" class="primary-button">
				<p><?php _e('بازگشت به صفحه اصلی', 'taghechian'); ?></p>
			</a>
		</div>

	</section>
</main>

<?php get_footer(); ?>