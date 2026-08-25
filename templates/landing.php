<?php /* Template Name: Landing */ ?>
<?php

use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

$landing_blocks = [];
for ($i = 1; $i <= 6; $i++) {
    $title = get_field("landing_block_title_{$i}");
    $desc = get_field("landing_block_desc_{$i}");
    $image = get_field("landing_block_image_{$i}");
    $image_url = is_array($image) ? ($image['url'] ?? '') : $image;
    if (empty($title) && empty($desc) && empty($image_url)) continue;
    $landing_blocks[] = ['title' => $title, 'desc' => $desc, 'image' => $image_url];
}

$landing_projects_enabled = (bool) get_field('landing_projects_enabled');
$landing_projects_title = get_field('landing_projects_title') ?: __('پروژه های اجرا شده', 'novavilla');
$landing_project_ids = [];
if ($landing_projects_enabled) {
    $landing_projects = get_field('landing_projects');
    if (!empty($landing_projects)) {
        foreach ((array) $landing_projects as $raw_id) {
            $id = (int) $raw_id;
            if ($id > 0 && get_post_type($id) === 'project' && get_post_status($id) === 'publish') $landing_project_ids[] = $id;
        }
        $landing_project_ids = array_slice(array_values(array_unique($landing_project_ids)), 0, 4);
    }
    if ($landing_project_ids === []) {
        $landing_project_ids = get_posts(['post_type' => 'project', 'post_status' => 'publish', 'posts_per_page' => 4, 'fields' => 'ids', 'orderby' => 'date', 'order' => 'DESC']);
    }
}

$landing_products_enabled = (bool) get_field('landing_products_enabled');
$landing_products_title = get_field('landing_products_title') ?: __('محصولات', 'novavilla');
$landing_product_ids = [];
if ($landing_products_enabled) {
    $landing_products = get_field('landing_products');
    if (!empty($landing_products)) {
        foreach ((array) $landing_products as $raw_id) {
            $id = (int) $raw_id;
            if ($id > 0 && get_post_type($id) === 'product' && get_post_status($id) === 'publish') $landing_product_ids[] = $id;
        }
        $landing_product_ids = array_slice(array_values(array_unique($landing_product_ids)), 0, 4);
    }
    if ($landing_product_ids === []) {
        $landing_product_ids = get_posts(['post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 4, 'fields' => 'ids', 'orderby' => 'date', 'order' => 'DESC']);
    }
}

$landing_faq_place = get_field('landing_faq_place');
if (is_object($landing_faq_place) && !empty($landing_faq_place->slug)) {
    $landing_faq_place_slug = $landing_faq_place->slug;
} elseif (is_numeric($landing_faq_place)) {
    $landing_faq_term = get_term((int) $landing_faq_place, 'faq_place');
    $landing_faq_place_slug = (!is_wp_error($landing_faq_term) && $landing_faq_term) ? $landing_faq_term->slug : 'landing';
} else {
    $landing_faq_place_slug = 'landing';
}

get_header();
?>

<?php Templates::getPart('breadcrumb'); ?>

<main class="landing-page container space-y-11 md:space-y-16">

    <?php if (!empty($landing_blocks)) : ?>
        <section class="flex flex-col gap-8">
            <?php foreach ($landing_blocks as $index => $block) :
                $is_odd = $index % 2 === 0;
            ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 lg:gap-20 lg:items-center">
                    <div class="w-full <?php echo $is_odd ? 'lg:col-start-2' : ''; ?>">
                        <?php if (!empty($block['image'])) : ?>
                            <img src="<?php echo esc_url($block['image']); ?>" alt="<?php echo esc_attr($block['title'] ?: ''); ?>" class="w-full h-64 sm:h-96 lg::h-80 object-cover rounded-3xl" loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>" decoding="async" />
                        <?php else : ?>
                            <div class="w-full h-64 md:h-72 lg:h-80 rounded-3xl bg-cynBgItem/40" aria-hidden="true"></div>
                        <?php endif; ?>
                    </div>
                    <div class="w-full flex flex-col gap-2 <?php echo $is_odd ? 'lg:col-start-1 lg:row-start-1' : ''; ?>">
                        <?php if (!empty($block['title'])) : ?>
                            <?php if ($index === 0) : ?>
                                <h1 class="text-2xl md:text-4xl font-bold text-cynTextPrimary leading-9 md:leading-13">
                                    <?php echo esc_html($block['title']); ?>
                                </h1>
                            <?php else : ?>
                                <h2 class="text-2xl md:text-4xl font-bold text-cynTextPrimary leading-9 md:leading-13">
                                    <?php echo esc_html($block['title']); ?>
                                </h2>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (!empty($block['desc'])) : ?>
                            <div class="text-sm md:text-base font-light text-cynTextPrimary leading-5 md:leading-7">
                                <?php echo $block['desc']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php if (get_the_content()) : ?>
        <section>
            <div class="text-cynTextPrimary [&_a]:text-cynBlue [&_a]:font-normal [&_h2]:text-2xl [&_h2]:my-4 [&_h3]:text-xl [&_h3]:my-4 [&_h4]:text-xl [&_h4]:my-4 [&_p]:text-base [&_p]:font-light [&_p]:leading-8 [&_p]:my-4 [&_img]:w-full [&_img]:rounded-3xl [&_img]:object-cover [&_blockquote]:bg-cynBgItem [&_blockquote]:px-2 [&_blockquote]:my-4 [&_blockquote]:text-base [&_blockquote]:font-medium">
                <?php the_content(); ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($landing_projects_enabled && !empty($landing_project_ids)) : ?>
        <section class="flex flex-col gap-3 md:gap-5">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12">
                    <?php echo esc_html($landing_projects_title); ?>
                </h2>
                <div class="flex items-center gap-2 lg:hidden">
                    <button type="button" id="landingProjectsPrev" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('قبلی', 'novavilla'); ?>">
                        <i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
                            <?php Icon::print('Arrow-19'); ?>
                        </i>
                    </button>
                    <button type="button" id="landingProjectsNext" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('بعدی', 'novavilla'); ?>">
                        <i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
                            <?php Icon::print('Arrow-27'); ?>
                        </i>
                    </button>
                </div>
            </div>

            <div class="hidden lg:grid lg:grid-cols-4 gap-2.5">
                <?php foreach ($landing_project_ids as $project_id) : ?>
                    <?php Templates::getCard('project', ['post-id' => $project_id]); ?>
                <?php endforeach; ?>
            </div>

            <div class="lg:hidden">
                <swiper-container class="w-full" slides-per-view="1" space-between="12" loop="true" pagination="false" navigation="true" navigation-next-el="#landingProjectsNext" navigation-prev-el="#landingProjectsPrev">
                    <?php foreach ($landing_project_ids as $project_id) : ?>
                        <swiper-slide>
                            <?php Templates::getCard('project', ['post-id' => $project_id]); ?>
                        </swiper-slide>
                    <?php endforeach; ?>
                </swiper-container>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($landing_products_enabled && !empty($landing_product_ids)) : ?>
        <section class="flex flex-col gap-3 md:gap-5">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12">
                    <?php echo esc_html($landing_products_title); ?>
                </h2>
                <div class="flex items-center gap-2 lg:hidden">
                    <button type="button" id="landingProductsPrev" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('قبلی', 'novavilla'); ?>">
                        <i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
                            <?php Icon::print('Arrow-19'); ?>
                        </i>
                    </button>
                    <button type="button" id="landingProductsNext" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('بعدی', 'novavilla'); ?>">
                        <i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
                            <?php Icon::print('Arrow-27'); ?>
                        </i>
                    </button>
                </div>
            </div>

            <div class="hidden lg:grid lg:grid-cols-4 gap-2.5">
                <?php foreach ($landing_product_ids as $product_id) : ?>
                    <?php Templates::getCard('product', ['post-id' => $product_id]); ?>
                <?php endforeach; ?>
            </div>

            <div class="lg:hidden">
                <swiper-container class="w-full" slides-per-view="1" space-between="12" loop="true" pagination="false" navigation="true" navigation-next-el="#landingProductsNext" navigation-prev-el="#landingProductsPrev" breakpoints='{"768": {"slidesPerView": 2}}'>
                    <?php foreach ($landing_product_ids as $product_id) : ?>
                        <swiper-slide>
                            <?php Templates::getCard('product', ['post-id' => $product_id]); ?>
                        </swiper-slide>
                    <?php endforeach; ?>
                </swiper-container>
            </div>
        </section>
    <?php endif; ?>

    <?php Templates::getPart('faq', ['faq_place' => $landing_faq_place_slug]); ?>

</main>

<?php get_footer(); ?>