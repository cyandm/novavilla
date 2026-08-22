<?php

use Cyan\Theme\Helpers\Icon;

$args = get_query_var('args', []);
$post_id = $args['post-id'] ?? get_the_ID();

if (empty($post_id)) {
	return;
}

$title = get_the_title($post_id);
$issuer = get_field('certificate_issuer', $post_id);
$number = get_field('certificate_number', $post_id);
$issue_date = get_field('certificate_issue_date', $post_id);
$expiry_date = get_field('certificate_expiry_date', $post_id);
$file_url = get_field('certificate_file', $post_id);
$file_url = is_array($file_url) ? ($file_url['url'] ?? '') : $file_url;
$view_url = get_field('certificate_view_url', $post_id);
if (empty($view_url) && !empty($file_url)) {
	$view_url = $file_url;
}

$meta_lines = [];
if (!empty($issuer)) $meta_lines[] = __('مرجع صادرکننده:', 'novavilla') . ' ' . $issuer;
if (!empty($number)) $meta_lines[] = __('شماره گواهینامه:', 'novavilla') . ' ' . $number;
if (!empty($issue_date)) $meta_lines[] = __('تاریخ صدور:', 'novavilla') . ' ' . $issue_date;
if (!empty($expiry_date)) $meta_lines[] = __('تاریخ اعتبار:', 'novavilla') . ' ' . $expiry_date;
?>

<article class="flex flex-col gap-3 rounded-3xl border border-cynBorder hover:border-cynBorderHover transition-all duration-300 bg-cynBgItem backdrop-blur-xl h-full pt-6 md:pt-5 lg:pt-3 xl:pt-7 pb-4 px-4 sm:px-5">
	<div class="h-[95px] sm:h-[171px] overflow-hidden">
		<?php if (has_post_thumbnail($post_id)) : ?>
			<?php echo get_the_post_thumbnail($post_id, 'medium', ['class' => 'w-full h-[95px] sm:h-[171px] object-contain', 'alt' => esc_attr($title), 'loading' => 'lazy', 'decoding' => 'async']); ?>
		<?php else : ?>
			<div class="w-full h-[95px] lg:h-[171px] bg-cynBgSocial/40" aria-hidden="true"></div>
		<?php endif; ?>
	</div>

	<div class="flex flex-col gap-2 lg:gap-3 flex-1 sm:px-3">
		<div class="flex flex-col gap-1.5">
			<?php if (!empty($title)) : ?>
				<h3 class="text-xs sm:text-base font-medium text-cynTextPrimary leading-5 sm:leading-6"><?php echo esc_html($title); ?></h3>
			<?php endif; ?>

			<?php if (!empty($meta_lines)) : ?>
				<p class="text-[10px] sm:text-xs font-light text-cynTextPrimary leading-4 whitespace-pre-line"><?php echo esc_html(implode("\n", $meta_lines)); ?></p>
			<?php endif; ?>
		</div>

		<?php if (!empty($file_url) || !empty($view_url)) : ?>
			<div class="flex w-full items-stretch gap-2 mt-auto">
				<?php $cert_btn_class = 'flex flex-1 min-w-0 basis-0 items-center justify-center gap-2 rounded-3xl border border-cynBorder bg-cynBgItem text-cynTextPrimary text-xs font-medium hover:border-cynBorderHover transition-all duration-300 py-1 px-3 lg:px-6'; ?>

				<?php if (!empty($view_url)) : ?>
					<a href="<?php echo esc_url($view_url); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr($cert_btn_class); ?>" aria-label="<?php esc_attr_e('مشاهده', 'novavilla'); ?>">
						<i class="size-6 flex items-center justify-center [&_svg]:text-cynBorderHover"><?php Icon::print('eye-2'); ?></i>
						<span class="hidden lg:inline"><?php esc_html_e('مشاهده', 'novavilla'); ?></span>
					</a>
				<?php endif; ?>

				<?php if (!empty($file_url)) : ?>
					<a href="<?php echo esc_url($file_url); ?>" download class="<?php echo esc_attr($cert_btn_class); ?>" aria-label="<?php esc_attr_e('دانلود', 'novavilla'); ?>">
						<i class="size-6 flex items-center justify-center [&_svg]:text-cynBorderHover"><?php Icon::print('Download,-Save,-Upload-2'); ?></i>
						<span class="hidden lg:inline"><?php esc_html_e('دانلود', 'novavilla'); ?></span>
					</a>
				<?php endif; ?>

			</div>
		<?php endif; ?>
	</div>
</article>