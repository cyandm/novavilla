<?php

use Cyan\Theme\Helpers\Icon;

defined('ABSPATH') || exit;

$home_id = get_the_ID();
$cards = [];

for ($i = 1; $i <= 4; $i++) {
    $title = get_field("home_cat_title_{$i}", $home_id);
    $subtitle = get_field("home_cat_subtitle_{$i}", $home_id);
    $image = get_field("home_cat_image_{$i}", $home_id);
    $btn = get_field("home_cat_btn_{$i}", $home_id);

    $btn_url = is_array($btn) ? ($btn['url'] ?? '') : '';
    $btn_title = is_array($btn) ? ($btn['title'] ?? '') : '';
    $has_btn = !empty($btn_url) || !empty($btn_title);

    if (empty($title) && empty($subtitle) && empty($image) && ! $has_btn) continue;

    $cards[] = [
        'title' => $title,
        'subtitle' => $subtitle,
        'image' => is_array($image) ? ($image['url'] ?? '') : $image,
        'btn' => is_array($btn) ? $btn : [],
        'tall' => $i <= 2,
    ];
}

if (empty($cards)) return;
?>

<section class="container mt-5 md:mt-6">
    <div class="grid grid-cols-2 gap-3 items-stretch">
        <?php foreach ($cards as $card) :
            $btn = $card['btn'];
            $btn_url = $btn['url'] ?? '';
            $btn_title = $btn['title'] ?? '';
            $btn_title = is_string($btn_title) ? $btn_title : '';
            $btn_has_text = !empty(trim($btn_title));
            $btn_title_fallback = __('مشاهده محصولات', 'novavilla');
            $btn_target = !empty($btn['target']) ? 'target="' . esc_attr($btn['target']) . '"' : '';
            $is_tall = $card['tall'];
        ?>
            <div class="<?php echo $is_tall ? 'col-span-2 lg:col-span-1' : 'col-span-1'; ?> h-full">
                <div class="group flex h-full rounded-3xl border border-cynBorderHover/40 bg-cynBgItem backdrop-blur-xl p-3 lg:px-4 lg:py-6 transition-all duration-300 hover:border-cynBorderHover <?php echo $is_tall ? 'flex-col gap-7' : 'flex-col lg:flex-row lg:items-center gap-3'; ?>">

                    <?php if (!empty($card['image'])) : ?>
                        <div class="<?php echo $is_tall ? 'h-52 lg:h-80' : 'lg:w-1/2 h-20 lg:h-44'; ?> w-full flex items-center justify-center shrink-0">
                            <img src="<?php echo esc_url($card['image']); ?>" alt="<?php echo esc_attr($card['title']); ?>" class="max-h-full w-auto object-contain drop-shadow-[-10px_6px_26px_rgba(0,0,0,0.2)]" loading="lazy" decoding="async" />
                        </div>
                    <?php endif; ?>

                    <div class="<?php echo $is_tall ? 'flex items-end justify-between gap-2' : 'w-full lg:w-1/2 flex flex-col gap-0 lg:gap-5'; ?>">
                        <div class="flex flex-col <?php echo $is_tall ? 'gap-2' : 'gap-0 lg:gap-2'; ?>">
                            <?php if (!empty($card['title'])) : ?>
                                <h3 class="text-2xl font-black text-cynTextPrimary leading-8">
                                    <?php echo esc_html($card['title']); ?>
                                </h3>
                            <?php endif; ?>
                            <?php if (!empty($card['subtitle'])) : ?>
                                <p class="text-sm lg:text-base font-medium text-cynTextPrimary leading-6">
                                    <?php echo esc_html($card['subtitle']); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($btn_url)) : ?>
                            <div class="flex justify-end">
                                <?php if ($is_tall) : ?>
                                    <?php if ($btn_has_text) : ?>
                                        <a href="<?php echo esc_url($btn_url); ?>" <?php echo $btn_target; ?> class="primary-button btn-have-icon !py-2 w-fit">
                                            <span class="text-xs font-semibold whitespace-nowrap">
                                                <?php echo esc_html($btn_title); ?>
                                            </span>
                                            <i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
                                                <?php Icon::print('Arrow-27'); ?>
                                            </i>
                                        </a>
                                    <?php else : ?>
                                        <a href="<?php echo esc_url($btn_url); ?>" <?php echo $btn_target; ?> class="primary-button !p-1 lg:!p-2 size-8 lg:size-10 justify-center w-fit" aria-label="<?php echo esc_attr($btn_title_fallback); ?>">
                                            <i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
                                                <?php Icon::print('Arrow-27'); ?>
                                            </i>
                                        </a>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <?php if ($btn_has_text) : ?>
                                        <a href="<?php echo esc_url($btn_url); ?>" <?php echo $btn_target; ?> class="primary-button btn-have-icon !py-2 w-fit">
                                            <span class="text-xs font-semibold whitespace-nowrap">
                                                <?php echo esc_html($btn_title); ?>
                                            </span>
                                            <i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
                                                <?php Icon::print('Arrow-27'); ?>
                                            </i>
                                        </a>
                                    <?php else : ?>
                                        <a href="<?php echo esc_url($btn_url); ?>" <?php echo $btn_target; ?> class="primary-button !p-1 lg:!p-2 size-8 lg:size-10 justify-center shrink-0" aria-label="<?php echo esc_attr($btn_title_fallback); ?>">
                                            <i class="size-6 flex items-center justify-center [&_svg]:size-full [&_svg]:stroke-[1.5]">
                                                <?php Icon::print('Arrow-27'); ?>
                                            </i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>