<?php

use Cyan\Theme\Helpers\Templates;

$per_page = 9;
$base_url = get_permalink();
$blog_tab = isset($_GET['blog_tab']) ? sanitize_title(wp_unslash($_GET['blog_tab'])) : 'all';
$blog_paged = max(1, (int) ($_GET['blog_paged'] ?? 1));

$query_posts = function (string $category_slug = '', int $paged = 1) use ($per_page): array {
	$args = [
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $per_page,
		'paged' => $paged,
		'orderby' => 'date',
		'order' => 'DESC',
	];

	if ($category_slug !== '' && $category_slug !== 'all') {
		$args['category_name'] = $category_slug;
	}

	$query = new WP_Query($args);
	$result = [
		'post_ids' => array_map('intval', wp_list_pluck($query->posts, 'ID')),
		'total' => (int) $query->max_num_pages,
		'current' => max(1, min($paged, max(1, (int) $query->max_num_pages))),
	];
	wp_reset_postdata();

	return $result;
};

$render_blog_posts = function (array $post_ids): void {
	if (empty($post_ids)) {
		echo '<p class="text-sm md:text-base text-cynTextPrimary/70 py-8 text-center">' . esc_html__('مقاله‌ای در این دسته‌بندی یافت نشد.', 'novavilla') . '</p>';
		return;
	}
?>
	<div class="hidden md:grid grid-cols-3 gap-3">
		<?php foreach ($post_ids as $blog_id) : ?>
			<?php Templates::getCard('blog', ['post-id' => $blog_id]); ?>
		<?php endforeach; ?>
	</div>

	<div class="flex flex-col gap-3 md:hidden">
		<?php foreach ($post_ids as $blog_id) : ?>
			<?php Templates::getCard('blog', ['post-id' => $blog_id, 'variant' => 'list']); ?>
		<?php endforeach; ?>
	</div>
<?php
};

$render_tab_panel = function (string $tab_slug, array $tab_data, bool $is_active) use ($render_blog_posts, $base_url): void {
?>
	<div id="blog-content-<?php echo esc_attr($tab_slug); ?>" role="tabpanel" data-content="<?php echo esc_attr($tab_slug); ?>" aria-hidden="<?php echo $is_active ? 'false' : 'true'; ?>" class="blog-archive-content<?php echo $is_active ? ' is-active' : ''; ?>">
		<?php $render_blog_posts($tab_data['post_ids']); ?>

		<?php Templates::getPart('pagination', [
			'total' => $tab_data['total'],
			'current' => $tab_data['current'],
			'base_url' => $base_url,
			'page_param' => 'blog_paged',
			'query_args' => ['blog_tab' => $tab_slug],
			'hash' => 'blog-archive',
			'class' => '',
			'aria_label' => __('صفحه‌بندی مقالات', 'novavilla'),
		]); ?>
	</div>
<?php
};

$blog_categories = get_categories([
	'taxonomy' => 'category',
	'hide_empty' => true,
	'orderby' => 'name',
	'order' => 'ASC',
]);

if (is_wp_error($blog_categories)) {
	$blog_categories = [];
}

$valid_tabs = ['all'];
$blog_category_tabs = [];
$uncategorized_id = (int) get_option('default_category');

foreach ($blog_categories as $category) {
	if ((int) $category->term_id === $uncategorized_id || $category->slug === 'uncategorized') {
		continue;
	}

	$valid_tabs[] = $category->slug;
	$blog_category_tabs[] = $category;
}

if (!in_array($blog_tab, $valid_tabs, true)) {
	$blog_tab = 'all';
}

$tab_panels = [
	'all' => $query_posts('all', $blog_tab === 'all' ? $blog_paged : 1),
];

foreach ($blog_category_tabs as $category) {
	$tab_panels[$category->slug] = $query_posts($category->slug, $blog_tab === $category->slug ? $blog_paged : 1);
}
?>

<section id="blog-archive" class="blog-archive flex flex-col gap-5 md:gap-6">
	<div class="flex items-center gap-2 md:gap-3 border-b border-white/25 pb-3 overflow-x-auto scrollbar" role="tablist">
		<button type="button" role="tab" data-tab="all" aria-selected="<?php echo $blog_tab === 'all' ? 'true' : 'false'; ?>" aria-controls="blog-content-all" class="blog-archive-tab<?php echo $blog_tab === 'all' ? ' is-active' : ''; ?>"><span><?php esc_html_e('همه مقالات', 'novavilla'); ?></span></button>

		<?php foreach ($blog_category_tabs as $category) : ?>
			<button type="button" role="tab" data-tab="<?php echo esc_attr($category->slug); ?>" aria-selected="<?php echo $blog_tab === $category->slug ? 'true' : 'false'; ?>" aria-controls="blog-content-<?php echo esc_attr($category->slug); ?>" class="blog-archive-tab<?php echo $blog_tab === $category->slug ? ' is-active' : ''; ?>"><span><?php echo esc_html($category->name); ?></span></button>
		<?php endforeach; ?>
	</div>

	<div class="blog-archive-contents">
		<?php $render_tab_panel('all', $tab_panels['all'], $blog_tab === 'all'); ?>

		<?php foreach ($blog_category_tabs as $category) : ?>
			<?php $render_tab_panel($category->slug, $tab_panels[$category->slug], $blog_tab === $category->slug); ?>
		<?php endforeach; ?>
	</div>
</section>