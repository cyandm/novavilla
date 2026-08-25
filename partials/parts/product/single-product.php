<?php

use Cyan\Theme\Helpers\Aparat;
use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

defined('ABSPATH') || exit;

$post_id = get_the_ID();
$title = get_the_title($post_id);
$product_price = get_field('product_price', $post_id);
$product_price_num = is_numeric($product_price) ? (float) $product_price : 0;
$product_area = get_field('product_area', $post_id);
$product_dimensions = get_field('product_dimensions', $post_id);
$product_rooms = get_field('product_rooms', $post_id);
$product_structure_type = get_field('product_structure_type', $post_id);
$product_build_time = get_field('product_build_time', $post_id);
$product_status = get_field('product_status', $post_id);
$tags = get_the_terms($post_id, 'product_tag');
$consult_url = home_url('/contact-us/');
$play_icon = file_get_contents(THEME_DIR . '/assets/icon/play.svg');
$phone_number = get_option('phone_number');

$images = [];
for ($i = 1; $i <= 20; $i++) {
    $item = get_field("product_gallery_{$i}", $post_id);
    if (is_string($item) && $item !== '') {
        $images[] = $item;
        continue;
    }
    if (is_array($item) && !empty($item['url'])) $images[] = $item['url'];
}

if (empty($images) && has_post_thumbnail($post_id)) {
    $images[] = get_the_post_thumbnail_url($post_id, 'full');
}

$image_items = array_map(static fn($url) => ['type' => 'image', 'url' => $url], $images);
$video_items = [];
$featured_fallback = $images[0] ?? '';

for ($i = 1; $i <= 4; $i++) {
    $source = get_field("product_video_{$i}_source", $post_id) ?: 'aparat';
    $cover = get_field("product_video_{$i}_cover", $post_id);
    $cover_url = is_array($cover) ? ($cover['url'] ?? '') : (string) $cover;

    if ($source === 'wordpress') {
        $file = get_field("product_video_{$i}_file", $post_id);
        $file_url = is_array($file) ? ($file['url'] ?? '') : (string) $file;
        if ($file_url === '') continue;
        $video_items[] = ['type' => 'file', 'url' => $file_url, 'poster' => $cover_url ?: $featured_fallback];
        continue;
    }

    $aparat_raw = get_field("product_video_{$i}_aparat", $post_id);
    $aparat_data = is_string($aparat_raw) ? Aparat::parseEmbed($aparat_raw) : null;
    if (!$aparat_data) continue;
    $video_items[] = ['type' => 'aparat', 'hash' => $aparat_data['hash'], 'iframe_url' => $aparat_data['iframe_url'], 'poster' => Aparat::getPosterUrl($aparat_data['hash'], $cover_url ?: $featured_fallback)];
}

$gallery_items = get_field('product_videos_first', $post_id) ? array_merge($video_items, $image_items) : array_merge($image_items, $video_items);
$gallery_count = count($gallery_items);

if ($product_price !== null && $product_price !== '') {
    $product_price = (is_numeric($product_price) ? number_format_i18n((float) $product_price) : $product_price) . ' ' . __('تومان', 'novavilla');
}
if ($product_area !== null && $product_area !== '') {
    $product_area = is_numeric($product_area) ? number_format_i18n((float) $product_area) . ' ' . __('مترمربع', 'novavilla') : $product_area;
}
if ($product_rooms !== null && $product_rooms !== '') {
    $product_rooms = sprintf('%s %s', is_numeric($product_rooms) ? number_format_i18n((float) $product_rooms) : $product_rooms, __('اتاق خواب', 'novavilla'));
}

$spec_rows = [
    ['label' => __('متراژ', 'novavilla'), 'value' => $product_area, 'icon' => 'home-house-big'],
    ['label' => __('ابعاد', 'novavilla'), 'value' => $product_dimensions, 'icon' => 'Position,-Focus-1'],
    ['label' => __('تعداد اتاق', 'novavilla'), 'value' => $product_rooms, 'icon' => 'Double,-Bed-1'],
    ['label' => __('نوع سازه', 'novavilla'), 'value' => $product_structure_type, 'icon' => 'Grid,-Layout-13'],
    ['label' => __('زمان ساخت', 'novavilla'), 'value' => $product_build_time, 'icon' => 'clock-time'],
    ['label' => __('وضعیت محصول', 'novavilla'), 'value' => $product_status, 'icon' => 'home-house-2'],
];

$icon_class = 'size-4 lg:size-5 shrink-0 flex items-center justify-center text-cynTextPrimary [&_svg]:size-full [&_svg]:stroke-current [&_svg]:stroke-[1.5]';
$row_class = 'flex items-center justify-between gap-3 rounded-xl border border-cynBorder dark:border-transparent bg-cynBgElevated px-3 py-3';
$label_class = 'text-xs md:text-base font-medium text-cynTextPrimary leading-5';
$value_class = 'text-xs md:text-base font-medium text-cynTextPrimary leading-5';
$tag_class = 'inline-flex items-center rounded-md border border-cynBorder dark:border-cynBorderHover/20 bg-cynBgElevated px-3 py-1 text-xs md:text-base font-normal text-cynTextPrimary leading-5 transition-all duration-300 hover:border-cynBorderHover';
$action_btn_class = 'inline-flex items-center justify-center rounded-md border border-cynBorder dark:border-cynBorderHover/20 bg-cynWhite/8 px-3 py-2 text-cynTextPrimary transition-all duration-300 hover:border-cynBorderHover';

$description = get_field('product_description', $post_id);
$features_desc = get_field('product_features_desc', $post_id) ?: __('این مدل بر اساس نیاز شما قابل شخصی سازی است. امکانات موردنظر را انتخاب کنید', 'novavilla');
$features = [];
for ($i = 1; $i <= 9; $i++) {
    $feature_title = get_field("product_feature_title_{$i}", $post_id);
    $feature_desc = get_field("product_feature_desc_{$i}", $post_id);
    $feature_price = get_field("product_feature_price_{$i}", $post_id);
    if ($feature_title === '' || $feature_title === null) continue;
    $features[$i] = ['id' => (string) $i, 'title' => $feature_title, 'desc' => $feature_desc, 'price' => (float) preg_replace('/[^\d.]/', '', (string) $feature_price)];
}
ksort($features, SORT_NUMERIC);
$has_features = !empty($features);
$has_description = !empty($description);
$panel_class = 'w-full lg:w-1/2 flex flex-col gap-3 lg:gap-5 rounded-3xl border border-cynBorderHover/40 bg-cynBgItem backdrop-blur-md p-4 transition-all duration-300 hover:border-cynBorderHover';
$panel_box_class = 'w-full flex flex-col gap-3 lg:gap-5 rounded-3xl border border-cynBorderHover/40 bg-cynBgItem backdrop-blur-md p-4 transition-all duration-300 hover:border-cynBorderHover';
$feature_card_class = 'group flex flex-col rounded-xl lg:rounded-3xl border border-cynBorder dark:border-white/30 bg-cynBgItem backdrop-blur-md p-2 sm:p-3 lg:p-4 text-start transition-all duration-300 cursor-pointer hover:border-cynBorderHover [&.is-selected]:border-cynBorderHover/40';
$feature_title_class = 'text-xs sm:text-sm font-medium text-cynTextPrimary leading-4 md:leading-5 max-sm:whitespace-nowrap transition-all duration-300 group-[.is-selected]:text-cynBorderHover group-hover:text-cynBorderHover';
$feature_desc_class = 'text-xs font-light text-cynTextMuted leading-4 md:leading-6';
$feature_check_class = 'size-4 md:size-6 shrink-0 rounded-md border border-cynBorder dark:border-cynWhite flex items-center justify-center transition-all duration-300 group-[.is-selected]:border-cynBorderHover group-[.is-selected]:bg-cynBorderHover';
$chip_class = 'items-center gap-2.5 rounded-full border border-cynBorderHover/40 bg-cynWhite/8 px-2 py-1.5 text-xs md:text-base font-normal text-cynTextPrimary leading-4 md:leading-5 transition-all duration-300 hover:border-cynBorderHover group';
$format_toman = static fn($n) => number_format_i18n((float) $n) . ' ' . __('تومان', 'novavilla');
$price_row_class = 'flex items-center justify-between gap-2 rounded-3xl border border-cynBorderHover/40 bg-cynWhite/8 backdrop-blur-md px-4 py-4 transition-all duration-300 hover:border-cynBorderHover';
$price_label_class = 'text-base md:text-xl font-semibold text-cynTextPrimary leading-6';
$price_value_class = 'text-base md:text-xl font-medium text-cynTextPrimary leading-6';
$close_icon_class = 'size-7 rounded-full border border-cynBorder dark:border-white/40 hover:border-cynBorderHover dark:hover:border-cynWhite transition-all duration-300 bg-cynWhite/8 flex items-center justify-center text-cynTextPrimary dark:text-cynWhite [&_svg]:stroke-[1.5] cursor-pointer';
$chip_remove_icon_class = 'size-5 md:size-6 rounded-full border border-cynBorder dark:border-white/40 group-hover:border-cynBorderHover dark:group-hover:border-cynWhite transition-all duration-300 bg-cynWhite/8 flex items-center justify-center text-cynTextPrimary dark:text-cynWhite [&_svg]:stroke-[1.5]';
$title_underline_class = 'inline-flex items-center gap-2 border-b border-cynBorder dark:border-white/25 pb-2 w-fit';
?>


<section class="container flex flex-col lg:flex-row items-stretch gap-5 lg:gap-3">

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
                <swiper-container id="product-gallery-thumbs" dir="rtl" class="product-gallery-thumbs w-full h-[123px] lg:w-[140px] lg:h-[642px] xl:h-[592px]" slides-per-view="auto" space-between="8" direction="horizontal" css-mode="true" free-mode="true" nested="true" breakpoints='{"1024":{"direction":"vertical","slidesPerView":"auto","spaceBetween":8}}'>
                    <?php foreach ($gallery_items as $index => $item) : ?>
                        <swiper-slide class="group relative w-[120px] h-[123px] lg:w-[140px] lg:h-[192px] overflow-hidden rounded-xl lg:rounded-3xl border border-cynBorderHover/40 cursor-pointer transition-all duration-300 hover:border-cynBorderHover">
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

            <div class="relative w-full lg:flex-1 h-[399px] lg:h-[642px] xl:h-[592px] overflow-hidden rounded-xl lg:rounded-3xl border border-cynBorderHover/40 transition-all duration-300 hover:border-cynBorderHover">
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
                            <i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-current [&_svg]:stroke-[1.5]"><?php Icon::print('Arrow-19'); ?></i>
                        </button>
                        <button type="button" id="productGalleryNext" class="pointer-events-auto size-10 rounded-full bg-cynBorderHover flex items-center justify-center dark:text-cynBlack text-cynWhite transition-all duration-300" aria-label="<?php esc_attr_e('بعدی', 'novavilla'); ?>">
                            <i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-current [&_svg]:stroke-[1.5]"><?php Icon::print('Arrow-27'); ?></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    <?php endif; ?>

    <div class="w-full lg:w-[calc(50%-0.375rem)] flex flex-col justify-center gap-5 lg:gap-6 rounded-3xl border border-cynBorderHover/40 bg-cynBgItem backdrop-blur-md lg:backdrop-blur-md p-4 lg:px-4 lg:py-5 transition-all duration-300 hover:border-cynBorderHover">
        <div class="flex flex-col gap-2">
            <?php if (!empty($title)) : ?>
                <h1 class="text-2xl md:text-4xl font-bold text-cynTextPrimary leading-8 md:leading-14"><?php echo esc_html($title); ?></h1>
            <?php endif; ?>

            <?php if (!empty($tags) && !is_wp_error($tags)) : ?>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($tags as $tag) : ?>
                        <?php $tag_link = get_term_link($tag);
                        if (is_wp_error($tag_link)) continue; ?>
                        <a href="<?php echo esc_url($tag_link); ?>" class="<?php echo esc_attr($tag_class); ?>"><?php echo esc_html($tag->name); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex flex-col gap-2">
            <?php if (!empty($product_price)) : ?>
                <div class="flex items-center justify-between gap-3 rounded-xl border border-cynBorder dark:border-transparent bg-cynBgElevated px-3 py-3">
                    <span class="text-sm md:text-xl font-medium text-cynTextPrimary leading-6"><?php esc_html_e('قیمت', 'novavilla'); ?></span>
                    <span class="text-sm md:text-xl font-medium text-cynTextPrimary leading-6"><?php echo esc_html($product_price); ?></span>
                </div>
            <?php endif; ?>

            <?php foreach ($spec_rows as $row) : ?>
                <?php if (empty($row['value'])) continue; ?>
                <div class="<?php echo esc_attr($row_class); ?>">
                    <div class="flex items-center gap-1">
                        <i class="<?php echo esc_attr($icon_class); ?>"><?php Icon::print($row['icon']); ?></i>
                        <span class="<?php echo esc_attr($label_class); ?>"><?php echo esc_html($row['label']); ?></span>
                    </div>
                    <span class="<?php echo esc_attr($value_class); ?>"><?php echo esc_html($row['value']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="flex items-center justify-end gap-3 lg:gap-5">

            <div class="flex items-center gap-1 lg:gap-2">
                <button type="button" class="<?php echo esc_attr($action_btn_class); ?>" aria-label="<?php esc_attr_e('علاقه‌مندی', 'novavilla'); ?>">
                    <i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]"><?php Icon::print('Heart,-Favorite,-Love'); ?></i>
                </button>
                <button type="button" id="shareBtn" class="<?php echo esc_attr($action_btn_class); ?>" aria-label="<?php esc_attr_e('اشتراک‌گذاری', 'novavilla'); ?>">
                    <i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]"><?php Icon::print('Share-1'); ?></i>
                </button>
            </div>

            <button type="button" modal-opener data-modal-name="product-consult" class="primary-button btn-have-icon !py-2">
                <span class="text-xs md:text-sm font-semibold whitespace-nowrap">
                    <?php esc_html_e('درخواست مشاوره', 'novavilla'); ?>
                </span>
                <i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
                    <?php Icon::print('Arrow-27'); ?>
                </i>
            </button>

        </div>
    </div>

</section>

<section modal data-modal-name="product-consult" data-modal-layer="popup" data-active="false" class="fixed inset-x-3 md:inset-x-auto md:left-1/2 top-1/2 z-50 w-auto md:w-[min(800px,calc(100%-3rem))] md:-translate-x-1/2 max-h-[calc(100dvh-3rem)] -translate-y-1/2 overflow-y-auto scrollbar flex flex-col gap-5 opacity-0 pointer-events-none invisible data-[active='true']:opacity-100 data-[active='true']:pointer-events-auto data-[active='true']:visible transition-all duration-300">
    <div id="product-consult-success" class="empty:hidden"></div>
    <div class="rounded-3xl border border-cynBorderHover/40 bg-cynWhite/10 backdrop-blur-md p-4 flex flex-col gap-5 transition-all duration-300 hover:border-cynBorderHover">
        <div class="flex items-center justify-between gap-2">
            <span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6">
                <?php esc_html_e('درخواست مشاوره', 'novavilla'); ?>
            </span>
            <i class="<?php echo esc_attr($close_icon_class); ?>" modal-closer data-modal-name="product-consult">
                <?php Icon::print('Delete,-Disabled'); ?>
            </i>
        </div>
        <?php Templates::getPart('product/product-consult-form', ['source_page_id' => $post_id]); ?>
    </div>
</section>

<?php if ($has_features || $has_description) : ?>
    <section class="flex flex-col lg:flex-row gap-5 lg:gap-3 items-stretch lg:items-start container my-5 lg:my-6">

        <?php if ($has_description) : ?>
            <div class="<?php echo esc_attr($panel_class); ?> !gap-3">
                <div class="<?php echo esc_attr($title_underline_class); ?>">
                    <i class="size-6 text-cynBorderHover [&_svg]:stroke-[1.5]"><?php Icon::print('home-house-big'); ?></i>
                    <span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6"><?php esc_html_e('توضیحات محصول', 'novavilla'); ?></span>
                </div>
                <div class="text-xs md:text-sm font-light md:font-medium text-cynTextPrimary leading-6 md:leading-7 [&_ul]:list-disc [&_ul]:pr-6">
                    <?php echo wp_kses_post($description); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($has_features) : ?>
            <div class="contents" data-product-features data-product-title="<?php echo esc_attr($title); ?>" data-base-price="<?php echo esc_attr((string) $product_price_num); ?>">
                <div class="w-full lg:w-1/2">
                    <div class="<?php echo esc_attr($panel_box_class); ?> !gap-3">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <i class="size-6 text-cynBorderHover [&_svg]:stroke-[1.5]"><?php Icon::print('setting-11'); ?></i>
                                <span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6"><?php esc_html_e('امکانات قابل انتخاب', 'novavilla'); ?></span>
                            </div>
                            <?php if (!empty($features_desc)) : ?>
                                <p class="text-sm md:text-base font-light text-cynTextPrimary leading-6"><?php echo esc_html($features_desc); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="flex flex-col gap-3 md:gap-5">
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 items-stretch">
                                <?php foreach ($features as $feature) : ?>
                                    <button type="button" dir="rtl" data-feature-card data-feature-id="<?php echo esc_attr($feature['id']); ?>" data-feature-title="<?php echo esc_attr($feature['title']); ?>" data-feature-price="<?php echo esc_attr((string) $feature['price']); ?>" class="<?php echo esc_attr($feature_card_class); ?>" aria-pressed="false">
                                        <div class="flex items-start justify-between gap-2">
                                            <span class="<?php echo esc_attr($feature_check_class); ?>" aria-hidden="true">
                                                <i class="size-2.5 md:size-3.5 opacity-0 group-[.is-selected]:opacity-100 text-cynBlack flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-current"><?php Icon::print('check'); ?></i>
                                            </span>
                                            <div class="flex flex-col gap-2 min-w-0 flex-1">
                                                <span class="<?php echo esc_attr($feature_title_class); ?>"><?php echo esc_html($feature['title']); ?></span>
                                                <?php if (!empty($feature['desc'])) : ?>
                                                    <span class="<?php echo esc_attr($feature_desc_class); ?>"><?php echo esc_html($feature['desc']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </button>
                                <?php endforeach; ?>
                            </div>

                            <div class="flex flex-col gap-6 md:gap-5 rounded-3xl border border-cynBorderHover/40 bg-cynBgItem backdrop-blur-md p-4 items-end transition-all duration-300 hover:border-cynBorderHover">
                                <div class="flex flex-col gap-3 md:gap-5 w-full">
                                    <div class="flex items-center gap-1 text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6">
                                        <span class="text-cynTextPrimary">
                                            <?php esc_html_e('امکانات انتخاب شده', 'novavilla'); ?>
                                        </span>
                                        <span data-feature-count class="text-cynBorderHover">(0 <?php esc_html_e('مورد', 'novavilla'); ?>)</span>
                                    </div>
                                    <div data-feature-chips class="flex flex-wrap justify-start gap-1 md:gap-2">
                                        <?php foreach ($features as $feature) : ?>
                                            <button type="button" data-feature-chip data-feature-id="<?php echo esc_attr($feature['id']); ?>" class="<?php echo esc_attr($chip_class); ?> hidden" aria-label="<?php echo esc_attr(sprintf(__('حذف %s', 'novavilla'), $feature['title'])); ?>">
                                                <span><?php echo esc_html($feature['title']); ?></span>
                                                <i class="<?php echo esc_attr($chip_remove_icon_class); ?>">
                                                    <?php Icon::print('Delete,-Disabled'); ?>
                                                </i>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <button type="button" modal-opener data-modal-name="product-price-inquiry" class="primary-button btn-have-icon !py-2 w-fit">
                                    <span class="text-xs md:text-sm font-semibold whitespace-nowrap"><?php esc_html_e('استعلام قیمت با امکانات انتخابی', 'novavilla'); ?></span>
                                    <i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]"><?php Icon::print('Arrow-27'); ?></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div modal data-modal-name="product-price-inquiry" data-modal-layer="popup" data-active="false" class="fixed inset-x-3 md:inset-x-auto md:left-1/2 top-1/2 z-50 w-auto md:w-[min(1160px,calc(100%-3rem))] md:-translate-x-1/2 max-h-[calc(100dvh-3rem)] -translate-y-1/2 overflow-y-auto scrollbar rounded-3xl border border-cynBorderHover/40 bg-cynWhite/10 backdrop-blur-md p-4 flex flex-col gap-5 opacity-0 pointer-events-none invisible data-[active='true']:opacity-100 data-[active='true']:pointer-events-auto data-[active='true']:visible transition-all duration-300 hover:border-cynBorderHover items-end">
                    <div class="flex flex-col gap-3 md:gap-5 w-full">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-base md:text-xl font-medium md:font-semibold text-cynTextPrimary leading-6">
                                <?php esc_html_e('امکانات انتخابی', 'novavilla'); ?>
                            </span>
                            <i class="<?php echo esc_attr($close_icon_class); ?>" modal-closer data-modal-name="product-price-inquiry">
                                <?php Icon::print('Delete,-Disabled'); ?>
                            </i>
                        </div>

                        <div class="flex flex-wrap justify-start gap-1 md:gap-2">
                            <?php foreach ($features as $feature) : ?>
                                <button type="button" data-feature-chip data-feature-id="<?php echo esc_attr($feature['id']); ?>" class="<?php echo esc_attr($chip_class); ?> hidden" aria-label="<?php echo esc_attr(sprintf(__('حذف %s', 'novavilla'), $feature['title'])); ?>">
                                    <span><?php echo esc_html($feature['title']); ?></span>
                                    <i class="<?php echo esc_attr($chip_remove_icon_class); ?>">
                                        <?php Icon::print('Delete,-Disabled'); ?>
                                    </i>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 md:gap-3 w-full">
                        <div class="<?php echo esc_attr($price_row_class); ?>">
                            <span class="<?php echo esc_attr($price_label_class); ?>">
                                <?php esc_html_e('قیمت پایه', 'novavilla'); ?>
                            </span>
                            <span data-price-base class="<?php echo esc_attr($price_value_class); ?>">
                                <?php echo esc_html($format_toman($product_price_num)); ?>
                            </span>
                        </div>
                        <div class="<?php echo esc_attr($price_row_class); ?>">
                            <span class="<?php echo esc_attr($price_label_class); ?>"><?php esc_html_e('هزینه امکانات انتخابی', 'novavilla'); ?></span>
                            <span data-price-features class="<?php echo esc_attr($price_value_class); ?>"><?php echo esc_html($format_toman(0)); ?></span>
                        </div>
                        <div class="<?php echo esc_attr($price_row_class); ?>">
                            <span class="text-base md:text-xl font-semibold text-cynBorderHover leading-6"><?php esc_html_e('قیمت کل', 'novavilla'); ?></span>
                            <span data-price-total class="text-base md:text-xl font-medium text-cynBorderHover leading-6"><?php echo esc_html($format_toman($product_price_num)); ?></span>
                        </div>
                    </div>
                    <a href="<?php echo esc_url('tel:' . $phone_number); ?>" class="primary-button w-fit">
                        <i class="size-5 flex items-center justify-center [&_svg]:stroke-[1.5] [&_svg_g_path]:fill-cynTextSecondary group-hover:[&_svg_g_path]:fill-cynBlack">
                            <?php Icon::print('Phone,-Call-11'); ?>
                        </i>
                        <span class="text-xs md:text-sm font-semibold whitespace-nowrap">
                            <?php esc_html_e('تماس با مشاورین ما', 'novavilla'); ?>
                        </span>
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </section>
<?php endif; ?>



<?php Templates::getPart('product/product-installment'); ?>
<?php Templates::getPart('product/product-related'); ?>