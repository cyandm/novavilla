<?php

/**
 * Search box component
 * @package CyanThemeSetup
 */

use Cyan\Theme\Helpers\Icon;

$args = get_query_var('args', []);

$searchbox_id = $args['id'] ?? 'searchbox';
$form_class = $args['class'] ?? 'flex w-full max-w-[28rem]';
$search_type = $args['search_type'] ?? 'all';
$placeholder = $args['placeholder'] ?? __('دنبال چی میگردی؟', 'novavilla');
$button_text = $args['button_text'] ?? __('جستجو کن', 'novavilla');
$input_id = $searchbox_id . '-input';
?>

<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="<?php echo esc_attr($form_class); ?>">

    <div class="flex w-full min-w-0 items-center">

        <label for="<?php echo esc_attr($input_id); ?>" class="flex min-w-0 flex-1 cursor-text items-center gap-3 self-stretch  rounded-xl border border-[#30303033] bg-cynWhite p-1.5 pe-1.5 ps-4">
            <span class="shrink-0 text-cynBlack" aria-hidden="true">
                <i class="size-5 flex items-center justify-center [&_svg]:stroke-[1.5]">
                    <?php Icon::print('Search,-Loupe'); ?>
                </i>
            </span>

            <input id="<?php echo esc_attr($input_id); ?>" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" class="min-w-0 flex-1 border-0 bg-transparent text-sm font-medium text-cynBlack outline-none placeholder:text-cynBlack/50">
        </label>

        <button type="submit" class="shrink-0 rounded-lg bg-[#272727] px-2.5 py-2 text-sm font-semibold text-cynWhite transition-all duration-300 hover:bg-cynBgButtonHover hover:text-cynBlack">
            <?php echo esc_html($button_text); ?>
        </button>

    </div>

    <input type="hidden" name="search-type" value="<?php echo esc_attr($search_type); ?>">
</form>