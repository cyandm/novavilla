<?php

use Cyan\Theme\Helpers\Icon;

$args = get_query_var('args', []);
$postId = $args['post-id'] ?? get_the_ID();

if ($postId === 0) {
    throw new ErrorException('post id is invalid', 0, E_WARNING);
}

?>

<div class="py-6 | faq-card first:pt-0 last:pb-0" id="<?php echo "faq-$postId" ?>">
    <div class="faq-toggle | flex justify-between gap-2 items-center cursor-pointer">
        <span class="faq-q | text-sm md:text-base font-medium text-cynTextPrimary/80 md:text-cynTextPrimary/60 flex gap-2">
            <i class="not-italic"><?php echo '•' ?></i>
            <?php echo get_the_title($postId) ?>
        </span>

        <i class="icon size-6 shrink-0 flex items-center justify-center text-cynTextPrimary rotate-45 transition-transform duration-300 [&_svg]:size-full [&_svg]:stroke-[1.5]">
            <?php Icon::print('Delete,-Disabled'); ?>
        </i>
    </div>

    <div class="faq-expert | grid grid-rows-[0fr] transition-all duration-300">
        <div class="overflow-hidden">
            <div class="pt-4 [&_a]:text-cynBlue [&_a]:underline text-cynTextPrimary dark:text-cynTextPrimary/80 text-xs md:text-sm font-light leading-6 md:leading-7">
                <?php echo get_the_content(null, false, $postId) ?>
            </div>
        </div>
    </div>
</div>