<?php

use Cyan\Theme\Classes\Project;
use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

defined('ABSPATH') || exit;

$post_id = (int) get_the_ID();
$related_ids = Project::getRelatedProjectIds($post_id);
if ($related_ids === []) return;
?>

<section class="flex flex-col gap-3 md:gap-5">
	<div class="flex items-center justify-between gap-3">
		<h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12">
			<?php esc_html_e('پروژه های مشابه', 'novavilla'); ?>
		</h2>
		<div class="flex items-center gap-2 lg:hidden">
			<button type="button" id="similarProjectsPrev" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('قبلی', 'novavilla'); ?>">
				<i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
					<?php Icon::print('Arrow-19'); ?>
				</i>
			</button>
			<button type="button" id="similarProjectsNext" class="size-10 md:size-11 flex items-center justify-center rounded-full bg-cynBorderHover text-cynTextSecondary cursor-pointer shrink-0" aria-label="<?php esc_attr_e('بعدی', 'novavilla'); ?>">
				<i class="size-7 flex items-center justify-center [&_svg]:fill-current [&_svg]:stroke-[1.5]">
					<?php Icon::print('Arrow-27'); ?>
				</i>
			</button>
		</div>
	</div>

	<div class="hidden lg:grid lg:grid-cols-4 gap-2.5">
		<?php foreach ($related_ids as $related_id) : ?>
			<?php Templates::getCard('project', ['post-id' => $related_id]); ?>
		<?php endforeach; ?>
	</div>

	<div class="lg:hidden">
		<swiper-container class="w-full" slides-per-view="1" space-between="12" loop="true" pagination="false" navigation="true" navigation-next-el="#similarProjectsNext" navigation-prev-el="#similarProjectsPrev">
			<?php foreach ($related_ids as $related_id) : ?>
				<swiper-slide>
					<?php Templates::getCard('project', ['post-id' => $related_id]); ?>
				</swiper-slide>
			<?php endforeach; ?>
		</swiper-container>
	</div>
</section>
