<?php

use Cyan\Theme\Helpers\Aparat;
use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

defined('ABSPATH') || exit;

$post_id = get_the_ID();
$title = get_the_title($post_id);
$project_desc = get_field('project_desc', $post_id);
$project_duration = get_field('project_duration', $post_id);
$project_area = get_field('project_area', $post_id);
$project_location = get_field('project_location', $post_id);
$project_description = get_field('project_description', $post_id);
$project_design_solution = get_field('project_design_solution', $post_id);
$project_client_need = get_field('project_client_need', $post_id);
$project_image_before = get_field('project_image_before', $post_id);
$project_image_after = get_field('project_image_after', $post_id);
$project_review_text = get_field('project_review_text', $post_id);
$project_review_rating = get_field('project_review_rating', $post_id);
$project_review_name = get_field('project_review_name', $post_id);
$project_review_role = get_field('project_review_role', $post_id) ?: __('کارفرما', 'novavilla');
$project_review_image = get_field('project_review_image', $post_id);
$play_icon = file_get_contents(THEME_DIR . '/assets/icon/play.svg');

$project_image_before = is_array($project_image_before) ? ($project_image_before['url'] ?? '') : (string) $project_image_before;
$project_image_after = is_array($project_image_after) ? ($project_image_after['url'] ?? '') : (string) $project_image_after;
$project_review_image = is_array($project_review_image) ? ($project_review_image['url'] ?? '') : (string) $project_review_image;

$images = [];
for ($i = 1; $i <= 20; $i++) {
	$item = get_field("project_gallery_{$i}", $post_id);
	if (is_string($item) && $item !== '') {
		$images[] = $item;
		continue;
	}
	if (is_array($item) && !empty($item['url'])) $images[] = $item['url'];
}
if (empty($images) && has_post_thumbnail($post_id)) $images[] = get_the_post_thumbnail_url($post_id, 'full');

$image_items = array_map(static fn($url) => ['type' => 'image', 'url' => $url], $images);
$video_items = [];
$featured_fallback = $images[0] ?? '';
for ($i = 1; $i <= 4; $i++) {
	$source = get_field("project_video_{$i}_source", $post_id) ?: 'aparat';
	$cover = get_field("project_video_{$i}_cover", $post_id);
	$cover_url = is_array($cover) ? ($cover['url'] ?? '') : (string) $cover;
	if ($source === 'wordpress') {
		$file = get_field("project_video_{$i}_file", $post_id);
		$file_url = is_array($file) ? ($file['url'] ?? '') : (string) $file;
		if ($file_url === '') continue;
		$video_items[] = ['type' => 'file', 'url' => $file_url, 'poster' => $cover_url ?: $featured_fallback];
		continue;
	}
	$aparat_raw = get_field("project_video_{$i}_aparat", $post_id);
	$aparat_data = is_string($aparat_raw) ? Aparat::parseEmbed($aparat_raw) : null;
	if (!$aparat_data) continue;
	$video_items[] = ['type' => 'aparat', 'hash' => $aparat_data['hash'], 'iframe_url' => $aparat_data['iframe_url'], 'poster' => Aparat::getPosterUrl($aparat_data['hash'], $cover_url ?: $featured_fallback)];
}
$gallery_items = get_field('project_videos_first', $post_id) ? array_merge($video_items, $image_items) : array_merge($image_items, $video_items);
$gallery_count = count($gallery_items);

$meta_items = array_filter([
	['value' => $project_duration, 'icon' => 'clock-time'],
	['value' => $project_area, 'icon' => 'Position,-Focus-1'],
	['value' => $project_location, 'icon' => 'Pin,-Location'],
], static fn($item) => $item['value'] !== null && $item['value'] !== '');

$has_description = !empty($project_description);
$has_design = !empty($project_design_solution);
$has_need = !empty($project_client_need);
$has_content = $has_description || $has_design || $has_need;
$has_compare = $project_image_before !== '' && $project_image_after !== '';
$has_review = !empty($project_review_text) || !empty($project_review_name) || $project_review_image !== '';
$review_rating = is_numeric($project_review_rating) ? (float) $project_review_rating : 0;

$action_btn_class = 'inline-flex items-center justify-center rounded-md border border-cynBorderHover/20 bg-cynWhite/8 px-3 py-2 text-cynTextPrimary transition-all duration-300 hover:border-cynBorderHover';
$panel_class = 'w-full flex flex-col gap-3 rounded-3xl border border-cynBorderHover/40 bg-cynWhite/8 backdrop-blur-xl p-4';
$inner_panel_class = 'w-full flex flex-col gap-3 rounded-3xl border border-cynBorderHover/40 bg-cynWhite/8 backdrop-blur-xl p-4';
$title_row_class = 'inline-flex items-center gap-2 border-b border-white/25 pb-2 w-fit';
$body_class = 'text-xs md:text-sm font-light md:font-medium text-cynTextPrimary leading-6 md:leading-7 [&_ul]:list-disc [&_ul]:pr-6 [&_p]:mb-0';
?>

<div class="container flex flex-col gap-11 lg:gap-16">
	<div class="flex flex-col gap-3 lg:gap-6">
		<section class="flex flex-col lg:flex-row items-stretch gap-3 lg:gap-3">
			<?php if (!empty($gallery_items)) : ?>
				<div class="sr-only" aria-hidden="true">
					<?php foreach ($gallery_items as $item) : ?>
						<?php if ($item['type'] === 'image') : ?>
							<a href="<?php echo esc_url($item['url']); ?>" data-fancybox="product-gallery"></a>
						<?php elseif ($item['type'] === 'aparat') : ?>
							<a href="<?php echo esc_url($item['iframe_url']); ?>" data-fancybox="product-gallery" data-type="iframe"></a>
						<?php elseif ($item['type'] === 'file') : ?>
							<a href="<?php echo esc_url($item['url']); ?>" data-fancybox="product-gallery" data-type="html5video"></a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

				<div class="w-full lg:w-[calc(50%-0.375rem)] flex flex-col-reverse lg:flex-row gap-2" data-product-gallery>
					<?php if ($gallery_count > 1) : ?>
						<swiper-container id="product-gallery-thumbs" dir="rtl" class="product-gallery-thumbs w-full h-[123px] lg:w-[140px] lg:h-[452px]" slides-per-view="auto" space-between="8" direction="horizontal" css-mode="true" free-mode="true" nested="true" breakpoints='{"1024":{"direction":"vertical","slidesPerView":"auto","spaceBetween":8}}'>
							<?php foreach ($gallery_items as $index => $item) : ?>
								<swiper-slide class="group relative w-[120px] h-[123px] lg:w-[140px] lg:h-[145px] overflow-hidden rounded-xl lg:rounded-3xl border border-cynBorderHover/40 cursor-pointer">
									<div class="relative w-full h-full" data-fancybox-delegate="product-gallery" data-fancybox-index="<?php echo (int) $index; ?>">
										<?php $thumb_src = $item['type'] === 'image' ? $item['url'] : ($item['poster'] ?? ''); ?>
										<?php if ($thumb_src) : ?>
											<img src="<?php echo esc_url($thumb_src); ?>" alt="" class="w-full h-full object-cover" loading="lazy" decoding="async">
										<?php else : ?>
											<div class="w-full h-full bg-cynBlack"></div>
										<?php endif; ?>
										<span class="thumb-dim absolute inset-0 bg-black/60 transition-opacity duration-300 group-hover:opacity-0 group-[.swiper-slide-thumb-active]:opacity-0" aria-hidden="true"></span>
										<?php if ($item['type'] !== 'image') : ?>
											<i class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10 size-8 rounded-full overflow-hidden text-cynWhite backdrop-blur-xl pointer-events-none [&_svg]:size-full"><?php echo $play_icon; ?></i>
										<?php endif; ?>
									</div>
								</swiper-slide>
							<?php endforeach; ?>
						</swiper-container>
					<?php endif; ?>

					<div class="relative w-full lg:flex-1 h-[399px] lg:h-[452px] overflow-hidden rounded-xl lg:rounded-3xl border border-cynBorderHover/40">
						<swiper-container id="product-gallery-main" class="product-gallery-main w-full h-full overflow-hidden" slides-per-view="1" space-between="0" <?php echo $gallery_count > 1 ? 'loop="true"' : ''; ?> <?php echo $gallery_count > 1 ? 'thumbs-swiper="#product-gallery-thumbs"' : ''; ?> navigation="true" navigation-next-el="#productGalleryNext" navigation-prev-el="#productGalleryPrev">
							<?php foreach ($gallery_items as $index => $item) : ?>
								<swiper-slide class="!h-full">
									<?php if ($item['type'] === 'image') : ?>
										<div class="w-full h-full cursor-zoom-in" data-fancybox-delegate="product-gallery" data-fancybox-index="<?php echo (int) $index; ?>">
											<img src="<?php echo esc_url($item['url']); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-full object-cover" loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>" decoding="async">
										</div>
									<?php else : ?>
										<div class="product-gallery-video relative w-full h-full overflow-hidden cursor-pointer" data-fancybox-delegate="product-gallery" data-fancybox-index="<?php echo (int) $index; ?>">
											<?php if (!empty($item['poster'])) : ?>
												<img src="<?php echo esc_url($item['poster']); ?>" alt="" class="w-full h-full object-cover">
											<?php else : ?>
												<div class="w-full h-full bg-cynBlack"></div>
											<?php endif; ?>
											<span class="absolute inset-0 bg-black/40 pointer-events-none" aria-hidden="true"></span>
											<i class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10 size-11 lg:size-16 rounded-full overflow-hidden text-cynWhite backdrop-blur-xl pointer-events-none [&_svg]:size-full"><?php echo $play_icon; ?></i>
										</div>
									<?php endif; ?>
								</swiper-slide>
							<?php endforeach; ?>
						</swiper-container>

						<?php if ($gallery_count > 1) : ?>
							<div class="absolute inset-x-2 lg:inset-x-3 top-1/2 -translate-y-1/2 z-10 flex items-center justify-between pointer-events-none">
								<button type="button" id="productGalleryPrev" class="pointer-events-auto size-10 rounded-full bg-cynBorderHover flex items-center justify-center dark:text-cynBlack text-cynWhite transition-all duration-300" aria-label="<?php esc_attr_e('بعدی', 'novavilla'); ?>">
									<i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-current [&_svg]:stroke-[1.5]">
										<?php Icon::print('Arrow-19'); ?>
									</i>
								</button>
								<button type="button" id="productGalleryNext" class="pointer-events-auto size-10 rounded-full bg-cynBorderHover flex items-center justify-center dark:text-cynBlack text-cynWhite transition-all duration-300" aria-label="<?php esc_attr_e('بعدی', 'novavilla'); ?>">
									<i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-current [&_svg]:stroke-[1.5]">
										<?php Icon::print('Arrow-27'); ?>
									</i>
								</button>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="w-full <?php echo !empty($gallery_items) ? 'lg:w-[calc(50%-0.375rem)]' : ''; ?> flex flex-col justify-between gap-5 lg:gap-6 rounded-3xl border border-cynBorderHover/40 bg-cynBgItem backdrop-blur-md p-4 lg:px-6 lg:py-6">
				<?php if (!empty($title)) : ?>
					<h1 class="text-2xl md:text-4xl font-bold text-cynTextPrimary leading-8 md:leading-14">
						<?php echo esc_html($title); ?>
					</h1>
				<?php endif; ?>

				<div class="flex flex-col gap-2 lg:gap-3">
					<?php if (!empty($project_desc)) : ?>
						<div class="border-b border-cynTextPrimary/25 pb-2 <?php echo esc_attr($body_class); ?>">
							<?php echo wp_kses_post($project_desc); ?>
						</div>
					<?php endif; ?>

					<?php if (!empty($meta_items)) : ?>
						<div class="flex items-center justify-between gap-4 flex-wrap">
							<?php foreach ($meta_items as $meta_item) : ?>
								<div class="flex items-center gap-1">
									<i class="size-5 shrink-0 flex items-center justify-center text-cynBorderHover [&_svg]:size-full [&_svg]:stroke-current [&_svg]:stroke-[1.5]">
										<?php Icon::print($meta_item['icon']); ?>
									</i>
									<span class="text-xs md:text-sm font-light text-cynTextPrimary/80 leading-5">
										<?php echo esc_html($meta_item['value']); ?>
									</span>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="flex items-center justify-end gap-3">
					<div class="flex items-center gap-2">
						<button type="button" id="shareBtn" class="<?php echo esc_attr($action_btn_class); ?>" aria-label="<?php esc_attr_e('اشتراک‌گذاری', 'novavilla'); ?>">
							<i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
								<?php Icon::print('Share-1'); ?>
							</i>
						</button>
						<button type="button" class="<?php echo esc_attr($action_btn_class); ?>" aria-label="<?php esc_attr_e('علاقه‌مندی', 'novavilla'); ?>">
							<i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
								<?php Icon::print('Heart,-Favorite,-Love'); ?>
							</i>
						</button>
					</div>
					<button type="button" modal-opener data-modal-name="product-consult" class="primary-button btn-have-icon !py-2">
						<span class="text-xs md:text-sm font-semibold whitespace-nowrap">
							<?php esc_html_e('درخواست پروژه مشابه', 'novavilla'); ?>
						</span>
						<i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
							<?php Icon::print('Arrow-27'); ?>
						</i>
					</button>
				</div>
			</div>
		</section>

		<?php if ($has_content) : ?>
			<section class="<?php echo esc_attr($panel_class); ?>">
				<button type="button" class="accordion-button flex w-full items-center justify-between gap-2 cursor-pointer bg-transparent border-none p-0 text-start lg:pointer-events-none" data-accordion-target="project-description" data-accordion-icon-rotate="180" aria-expanded="true" aria-controls="project-description">
					<div class="<?php echo esc_attr($title_row_class); ?>">
						<i class="size-5 md:size-6 text-cynBorderHover [&_svg]:stroke-[1.5]">
							<?php Icon::print('Notes'); ?>
						</i>
						<span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6">
							<?php esc_html_e('توضیحات پروژه', 'novavilla'); ?>
						</span>
					</div>
					<i class="accordion-icon size-6 shrink-0 text-cynBorderHover transition-transform duration-300 lg:hidden [&_svg]:stroke-[1.5]" style="transform: rotate(180deg);">
						<?php Icon::print('Arrow-28'); ?>
					</i>
				</button>
				<div id="project-description" class="grid transition-[grid-template-rows] duration-300 ease-out !mt-0" data-accordion-content="project-description" style="grid-template-rows: 1fr;">
					<div class="min-h-0 overflow-hidden">
						<div class="flex flex-col gap-3 pt-3">
							<?php if ($has_description) : ?>
								<div class="<?php echo esc_attr($body_class); ?>">
									<?php echo wp_kses_post($project_description); ?>
								</div>
							<?php endif; ?>
							<?php if ($has_need || $has_design) : ?>
								<div class="flex flex-col lg:flex-row gap-3">
									<?php if ($has_need) : ?>
										<div class="<?php echo esc_attr($inner_panel_class); ?>">
											<div class="<?php echo esc_attr($title_row_class); ?>">
												<span class="text-base font-medium text-cynTextPrimary leading-6">
													<?php esc_html_e('نیاز کارفرما', 'novavilla'); ?>
												</span>
											</div>
											<div class="<?php echo esc_attr($body_class); ?>">
												<?php echo wp_kses_post($project_client_need); ?>
											</div>
										</div>
									<?php endif; ?>
									<?php if ($has_design) : ?>
										<div class="<?php echo esc_attr($inner_panel_class); ?>">
											<div class="<?php echo esc_attr($title_row_class); ?>">
												<span class="text-base font-medium text-cynTextPrimary leading-6">
													<?php esc_html_e('راهکار طراحی', 'novavilla'); ?>
												</span>
											</div>
											<div class="<?php echo esc_attr($body_class); ?>">
												<?php echo wp_kses_post($project_design_solution); ?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>
	</div>

	<?php if (!empty($images)) : ?>
		<section class="flex flex-col gap-3 md:gap-5">
			<div class="flex items-center justify-between gap-3">
				<h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12">
					<?php esc_html_e('گالری تصاویر پروژه', 'novavilla'); ?>
				</h2>
				<div class="flex items-center gap-2 lg:hidden">
					<button type="button" id="projectImagesPrev" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('قبلی', 'novavilla'); ?>">
						<i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
							<?php Icon::print('Arrow-19'); ?>
						</i>
					</button>
					<button type="button" id="projectImagesNext" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('بعدی', 'novavilla'); ?>">
						<i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
							<?php Icon::print('Arrow-27'); ?>
						</i>
					</button>
				</div>
			</div>
			<div class="hidden lg:grid lg:grid-cols-4 gap-2.5">
				<?php foreach ($images as $image_url) : ?>
					<a href="<?php echo esc_url($image_url); ?>" data-fancybox="project-images" class="block w-full h-[298px] overflow-hidden rounded-3xl">
						<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-full object-cover" loading="lazy" decoding="async">
					</a>
				<?php endforeach; ?>
			</div>
			<div class="lg:hidden">
				<swiper-container class="w-full" slides-per-view="2" space-between="12" loop="<?php echo count($images) > 2 ? 'true' : 'false'; ?>" pagination="false" navigation="true" navigation-next-el="#projectImagesNext" navigation-prev-el="#projectImagesPrev">
					<?php foreach ($images as $image_url) : ?>
						<swiper-slide>
							<a href="<?php echo esc_url($image_url); ?>" data-fancybox="project-images" class="block w-full h-[162px] overflow-hidden rounded-3xl">
								<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-full object-cover" loading="lazy" decoding="async">
							</a>
						</swiper-slide>
					<?php endforeach; ?>
				</swiper-container>
			</div>
		</section>
	<?php endif; ?>

	<?php if ($has_compare) : ?>
		<section class="flex flex-col gap-3 md:gap-5">
			<h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12">
				<?php esc_html_e('قبل و بعد پروژه', 'novavilla'); ?>
			</h2>
			<div dir="ltr" data-hero-compare class="relative w-full h-52 lg:h-[655px] select-none touch-none cursor-ew-resize overflow-hidden rounded-3xl">
				<img src="<?php echo esc_url($project_image_after); ?>" alt="<?php echo esc_attr($title); ?>" class="absolute inset-0 size-full object-cover pointer-events-none" loading="lazy" decoding="async" />
				<div data-hero-compare-before class="absolute inset-0" style="clip-path: inset(0 50% 0 0);">
					<img src="<?php echo esc_url($project_image_before); ?>" alt="" class="absolute inset-0 size-full object-cover pointer-events-none" loading="lazy" decoding="async">
				</div>
				<?php Templates::getPart('hero-compare-handle'); ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ($has_review) : ?>
		<section class="flex flex-col gap-3 md:gap-5">
			<h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12">
				<?php esc_html_e('نظر کارفرما', 'novavilla'); ?>
			</h2>
			<div class="flex flex-col lg:flex-row gap-3 rounded-3xl border border-cynBorderHover/40 bg-cynWhite/8 backdrop-blur-xl p-4">
				<div class="flex flex-col items-center gap-3 shrink-0 lg:w-[134px]">
					<?php if ($project_review_image !== '') : ?>
						<div class="relative size-[134px] shrink-0">
							<span class="pointer-events-none absolute inset-7 rounded-full bg-[#FBB963] blur-[50px]" aria-hidden="true"></span>
							<img src="<?php echo esc_url($project_review_image); ?>" alt="<?php echo esc_attr($project_review_name); ?>" class="relative size-full rounded-full object-cover ring-2 ring-[#FFC291]" loading="lazy" decoding="async">
						</div>
					<?php endif; ?>
					<div class="flex flex-col items-center gap-1 text-center">
						<?php if (!empty($project_review_name)) : ?>
							<span class="text-base font-medium text-cynTextPrimary leading-6">
								<?php echo esc_html($project_review_name); ?>
							</span>
						<?php endif; ?>
						<?php if (!empty($project_review_role)) : ?>
							<span class="text-xs font-light text-cynTextPrimary leading-4">
								<?php echo esc_html($project_review_role); ?>
							</span>
						<?php endif; ?>
					</div>
				</div>
				<div class="flex flex-col gap-3 flex-1">
					<?php if (!empty($project_review_text)) : ?>
						<div class="text-xs md:text-sm font-light text-cynTextPrimary/80 leading-6 md:leading-7">
							<?php echo esc_html($project_review_text); ?>
						</div>
					<?php endif; ?>
					<?php if ($review_rating > 0) : ?>
						<div class="flex items-center justify-end gap-2 mt-auto">
							<div class="flex items-center text-cynBorderHover">
								<?php for ($star = 1; $star <= 5; $star++) : ?>
									<i class="size-7 lg:size-8 flex items-center justify-center [&_svg]:size-full [&_g]:fill-current [&_path]:fill-current <?php echo $star <= round($review_rating) ? 'text-cynBorderHover' : 'text-cynWhite/20'; ?>">
										<?php Icon::print('Star'); ?>
									</i>
								<?php endfor; ?>
							</div>
							<span class="text-base font-medium text-cynTextPrimary leading-6">
								<?php echo esc_html(number_format($review_rating, 1)); ?>
							</span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php Templates::getPart('project/project-related'); ?>
</div>

<section modal data-modal-name="product-consult" data-modal-layer="popup" data-active="false" class="fixed inset-x-3 md:inset-x-auto md:left-1/2 top-1/2 z-50 w-auto md:w-[min(800px,calc(100%-3rem))] md:-translate-x-1/2 max-h-[calc(100dvh-3rem)] -translate-y-1/2 overflow-y-auto scrollbar flex flex-col gap-5 opacity-0 pointer-events-none invisible data-[active='true']:opacity-100 data-[active='true']:pointer-events-auto data-[active='true']:visible transition-all duration-300">
	<div id="product-consult-success" class="empty:hidden"></div>
	<div class="rounded-3xl border border-cynBorderHover/40 bg-cynWhite/10 backdrop-blur-md p-4 flex flex-col gap-5">
		<div class="flex items-center justify-between gap-2">
			<span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6">
				<?php esc_html_e('درخواست پروژه مشابه', 'novavilla'); ?>
			</span>
			<i class="size-7 rounded-full border border-white/40 group-hover:border-cynWhite transition-all duration-300 bg-cynWhite/8 flex items-center justify-center text-cynWhite [&_svg]:stroke-[1.5] cursor-pointer" modal-closer data-modal-name="product-consult">
				<?php Icon::print('Delete,-Disabled'); ?>
			</i>
		</div>
		<?php Templates::getPart('product/product-consult-form', ['source_page_id' => $post_id]); ?>
	</div>
</section>