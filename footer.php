<?php

/**
 * Footer for wordpress theme
 * its must include only footer tag
 * footer templates located in /partials/footer/
 * @package CyanTheme
 */

use Cyan\Theme\Helpers\Templates;

$render_template = $args['render_template'] ?? true;
?>

<?php if ($render_template) : ?>
	<footer class="mt-52 lg:mt-28 mb-8 md:mb-16">
		<?php Templates::getPart('footer'); ?>
	</footer>

	<?php Templates::getPart('theme-toggle'); ?>
	<?php Templates::getPart('backdrop'); ?>
<?php endif; ?>

<div id="wp-footer">
	<?php wp_footer(); ?>
</div>

</body>

</html>