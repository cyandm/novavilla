<?php
/* Template Name: 3D Structure */

use Cyan\Theme\Helpers\Templates;
use Cyan\Theme\Helpers\Icon;

$structure_hero_title = get_field('structure_hero_title') ?: __('قبل از ساخت،', 'novavilla');
$structure_hero_subtitle = get_field('structure_hero_subtitle') ?: __('سازه‌تان را ببینید', 'novavilla');
$structure_hero_description = get_field('structure_hero_description') ?: __('یکی از خدمات ویژه مجموعه، طراحی سه‌بعدی سازه هم‌زمان با جلسه حضوری فروش است. در این جلسه، کارشناسان ما نیازها، سلیقه، متراژ و نوع کاربری پروژه شما را بررسی کرده و طرح اولیه سازه را در همان لحظه آماده می‌کنند. مدل سه‌بعدی از طریق ویدئوپروژکتور روی پرده نمایش داده می‌شود تا بتوانید تغییرات طرح را واضح‌تر مشاهده کنید، درباره جزئیات نظر بدهید و نتیجه هر تصمیم را پیش از شروع تولید ببینید.', 'novavilla');
$structure_hero_image = get_field('structure_hero_image');
$structure_hero_image_url = is_array($structure_hero_image) ? ($structure_hero_image['url'] ?? '') : $structure_hero_image;
if (empty($structure_hero_image_url) && has_post_thumbnail()) {
	$structure_hero_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
}
$structure_booking_btn = get_field('structure_booking_btn');
$structure_consultation_btn = get_field('structure_consultation_btn');

$structure_session_title = get_field('structure_session_title') ?: __('این جلسه چگونه برگزار می‌شود؟', 'novavilla');
$structure_session_steps = [];
for ($i = 1; $i <= 5; $i++) {
	$title = get_field("structure_step_title_{$i}");
	$desc = get_field("structure_step_desc_{$i}");
	if (empty($title) && empty($desc)) {
		continue;
	}
	$image = get_field("structure_step_image_{$i}");
	$structure_session_steps[] = [
		'number' => get_field("structure_step_number_{$i}") ?: (string) $i,
		'title' => $title,
		'desc' => $desc,
		'image' => is_array($image) ? ($image['url'] ?? '') : $image,
	];
}

$structure_review_title = get_field('structure_review_title') ?: __('چه مواردی در جلسه قابل بررسی هست؟', 'novavilla');
$structure_review_items = [];
for ($i = 1; $i <= 6; $i++) {
	$title = get_field("structure_review_title_{$i}");
	$desc = get_field("structure_review_desc_{$i}");
	if (empty($title) && empty($desc)) {
		continue;
	}
	$image = get_field("structure_review_image_{$i}");
	$structure_review_items[] = [
		'title' => $title,
		'desc' => $desc,
		'image' => is_array($image) ? ($image['url'] ?? '') : $image,
	];
}

$structure_cta_title = get_field('structure_cta_title') ?: __('سازه‌ای که در ذهن دارید با هم به تصویر بکشیم', 'novavilla');
$structure_cta_description = get_field('structure_cta_description') ?: __('جلسات طراحی و مشاوره در فضای اختصاصی مجموعه برگزار می‌شوند. نمایش طرح روی پرده ویدئوپروژکتور این امکان را فراهم می‌کند که تمام افراد حاضر در جلسه، جزئیات پروژه را هم‌زمان مشاهده کرده و درباره تغییرات تصمیم‌گیری کنند.', 'novavilla');
$structure_cta_image = get_field('structure_cta_image');
$structure_cta_image_url = is_array($structure_cta_image) ? ($structure_cta_image['url'] ?? '') : $structure_cta_image;
$structure_cta_booking_btn = get_field('structure_cta_booking_btn');
$structure_cta_contact_btn = get_field('structure_cta_contact_btn');
$structure_cta_input_placeholder = get_field('structure_cta_input_placeholder') ?: __('ایمیل یا شماره همراه خود را وارد کنید', 'novavilla');
$structure_cta_contact_url = !empty($structure_cta_contact_btn['url']) ? $structure_cta_contact_btn['url'] : home_url('/contact-us/');

get_header();
?>

<?php Templates::getPart('breadcrumb'); ?>

<main class="structure-3d-page container space-y-11 lg:space-y-16">

	<section class="flex flex-col lg:flex-row lg:items-center gap-3">

		<div class="w-full lg:flex-1">
			<?php if (!empty($structure_hero_image_url)) : ?>
				<img src="<?php echo esc_url($structure_hero_image_url); ?>" alt="<?php echo esc_attr(trim($structure_hero_title . ' ' . $structure_hero_subtitle)); ?>" class="w-full h-[252px] md:h-[360px] lg:h-[430px] object-cover rounded-[20px]" loading="eager" decoding="async" />
			<?php else : ?>
				<div class="w-full h-[252px] md:h-[360px] lg:h-[430px] rounded-[20px] bg-cynBgItem/40" aria-hidden="true"></div>
			<?php endif; ?>
		</div>

		<div class="w-full lg:flex-1 flex flex-col gap-3">
			<div class="flex flex-col gap-2 lg:gap-3">
				<h1 class="text-2xl md:text-[32px] lg:text-4xl font-bold text-cynTextPrimary leading-tight lg:leading-[64px]">
					<span class="block"><?php echo esc_html($structure_hero_title); ?></span>
					<span class="block text-cynBorderHover"><?php echo esc_html($structure_hero_subtitle); ?></span>
				</h1>
				<p class="text-xs md:text-sm lg:text-base font-light text-cynTextPrimary leading-5 md:leading-7"><?php echo esc_html($structure_hero_description); ?></p>
			</div>

			<div class="flex flex-wrap items-center gap-2">
				<?php if (!empty($structure_booking_btn['url'])) : ?>
					<a href="<?php echo esc_url($structure_booking_btn['url']); ?>" <?php echo !empty($structure_booking_btn['target']) ? 'target="' . esc_attr($structure_booking_btn['target']) . '"' : ''; ?> class="primary-button btn-have-icon">
						<span class="flex items-center gap-1 whitespace-nowrap text-xs md:text-sm font-semibold">
							<i class="size-5 flex items-center justify-center [&_svg]:stroke-[1.5]"><?php Icon::print('calendar-schedule-1-1'); ?></i>
							<?php echo esc_html($structure_booking_btn['title'] ?: __('رزرو جلسه طراحی حضوری', 'novavilla')); ?>
						</span>
					</a>
				<?php endif; ?>

				<?php if (!empty($structure_consultation_btn['url'])) : ?>
					<a href="<?php echo esc_url($structure_consultation_btn['url']); ?>" <?php echo !empty($structure_consultation_btn['target']) ? 'target="' . esc_attr($structure_consultation_btn['target']) . '"' : ''; ?> class="primary-button">
						<span class="whitespace-nowrap text-xs md:text-sm font-semibold"><?php echo esc_html($structure_consultation_btn['title'] ?: __('دریافت مشاوره', 'novavilla')); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>

	</section>

	<?php if (!empty($structure_session_steps)) : ?>
		<section class="flex flex-col gap-3">
			<h2 class="text-base md:text-2xl lg:text-4xl font-bold text-cynTextPrimary"><?php echo esc_html($structure_session_title); ?></h2>

			<div class="lg:hidden overflow-x-auto scrollbar -mx-3 px-3" dir="rtl">
				<div class="flex gap-3 w-max min-w-full pb-1">
					<?php foreach ($structure_session_steps as $step) : ?>
						<article class="flex flex-col items-center gap-2 w-[66px] shrink-0 text-center">
							<div class="relative size-[66px] rounded-full overflow-hidden border border-black/80 bg-cynBgItem backdrop-blur-xl">
								<?php if (!empty($step['image'])) : ?>
									<img src="<?php echo esc_url($step['image']); ?>" alt="<?php echo esc_attr($step['title']); ?>" class="size-full object-cover mix-blend-screen" loading="lazy" decoding="async" />
								<?php endif; ?>
								<span class="absolute bottom-0 start-0 flex items-center justify-center size-5 rounded-full bg-cynBorderHover border border-white/40 text-[10px] font-semibold text-cynTextPrimary"><?php echo esc_html($step['number']); ?></span>
							</div>
							<h3 class="text-[10px] font-medium text-cynTextPrimary leading-4"><?php echo esc_html($step['title']); ?></h3>
						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="hidden lg:grid lg:grid-cols-5 lg:gap-3 structure-session-steps">
				<?php foreach ($structure_session_steps as $step) : ?>
					<article class="flex flex-col items-center gap-3 text-center">
						<div class="relative size-[120px] rounded-full overflow-hidden border border-black bg-cynBgItem backdrop-blur-xl">
							<?php if (!empty($step['image'])) : ?>
								<img src="<?php echo esc_url($step['image']); ?>" alt="<?php echo esc_attr($step['title']); ?>" class="size-full object-cover mix-blend-screen" loading="lazy" decoding="async" />
							<?php endif; ?>
							<span class="absolute bottom-0 start-0 flex items-center justify-center size-[30px] rounded-full bg-cynBorderHover border border-white/40 text-xs font-semibold text-cynTextPrimary"><?php echo esc_html($step['number']); ?></span>
						</div>
						<div class="flex flex-col gap-1">
							<h3 class="text-sm md:text-base font-medium text-cynTextPrimary"><?php echo esc_html($step['title']); ?></h3>
							<?php if (!empty($step['desc'])) : ?><p class="text-xs font-light text-cynTextPrimary leading-5"><?php echo esc_html($step['desc']); ?></p><?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if (!empty($structure_review_items)) : ?>
		<section class="flex flex-col gap-3">
			<h2 class="text-base md:text-2xl lg:text-4xl font-bold text-cynTextPrimary"><?php echo esc_html($structure_review_title); ?></h2>

			<div class="grid grid-cols-2 lg:grid-cols-6 gap-3">
				<?php foreach ($structure_review_items as $item) : ?>
					<article class="flex flex-col gap-3 rounded-[20px] border border-cynBorderHover/40 bg-cynBgItem/80 backdrop-blur-xl p-4 h-full">
						<?php if (!empty($item['image'])) : ?>
							<img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="w-full h-[84px] object-cover rounded-xl" loading="lazy" decoding="async" />
						<?php else : ?>
							<div class="w-full h-[84px] rounded-xl bg-cynBgSocial/40" aria-hidden="true"></div>
						<?php endif; ?>
						<div class="flex flex-col gap-1">
							<?php if (!empty($item['title'])) : ?><h3 class="text-sm font-medium text-cynTextPrimary"><?php echo esc_html($item['title']); ?></h3><?php endif; ?>
							<?php if (!empty($item['desc'])) : ?><p class="text-[10px] md:text-xs font-light text-cynTextPrimary leading-4 md:leading-5"><?php echo esc_html($item['desc']); ?></p><?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="relative overflow-hidden rounded-[20px] border border-cynBorderHover/40 bg-[#151516]">
		<?php if (!empty($structure_cta_image_url)) : ?>
			<img src="<?php echo esc_url($structure_cta_image_url); ?>" alt="<?php echo esc_attr($structure_cta_title); ?>" class="absolute top-0 left-0 w-full h-[217px] lg:h-full object-cover lg:w-[60%] lg:max-w-[694px]" loading="lazy" decoding="async" />
		<?php endif; ?>
		<div class="absolute inset-x-0 bottom-0 h-[60%] lg:hidden bg-gradient-to-t from-[#151514] via-[#151514]/95 to-transparent pointer-events-none" aria-hidden="true"></div>

		<div class="relative z-[1] flex flex-col lg:flex-row lg:items-center gap-5 lg:gap-8 p-4 md:p-6 lg:p-10 lg:min-h-[353px]">
			<div class="flex flex-col gap-3 lg:max-w-[546px] mt-auto lg:mt-0 pt-[180px] lg:pt-0">
				<div class="flex flex-col gap-2">
					<h2 class="text-xl md:text-2xl lg:text-4xl font-bold text-cynTextPrimary leading-tight lg:leading-[56px]"><?php echo esc_html($structure_cta_title); ?></h2>
					<p class="text-xs md:text-sm lg:text-base font-light text-cynTextPrimary leading-5 lg:leading-7"><?php echo esc_html($structure_cta_description); ?></p>
				</div>

				<div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
					<?php if (!empty($structure_cta_booking_btn['url'])) : ?>
						<a href="<?php echo esc_url($structure_cta_booking_btn['url']); ?>" <?php echo !empty($structure_cta_booking_btn['target']) ? 'target="' . esc_attr($structure_cta_booking_btn['target']) . '"' : ''; ?> class="primary-button btn-have-icon shrink-0">
							<span class="flex items-center justify-center gap-1 whitespace-nowrap text-xs md:text-sm font-semibold">
								<i class="size-5 flex items-center justify-center [&_svg]:stroke-[1.5]"><?php Icon::print('calendar-schedule-1-1'); ?></i>
								<?php echo esc_html($structure_cta_booking_btn['title'] ?: __('درخواست جلسه حضوری', 'novavilla')); ?>
							</span>
						</a>
					<?php endif; ?>

					<a href="<?php echo esc_url($structure_cta_contact_url); ?>" <?php echo !empty($structure_cta_contact_btn['target']) ? 'target="' . esc_attr($structure_cta_contact_btn['target']) . '"' : ''; ?> class="flex items-center justify-between gap-2 rounded-xl border border-cynBorderHover/40 bg-cynBgItem/80 px-4 py-2.5 min-h-10 text-xs md:text-sm text-cynTextPrimary/50 hover:border-cynBorderHover transition-colors no-underline">
						<span class="truncate"><?php echo esc_html($structure_cta_input_placeholder); ?></span>
						<i class="size-7 shrink-0 flex items-center justify-center text-cynBorderHover [&_svg]:stroke-[1.5]"><?php Icon::print('email-mail-letter'); ?></i>
					</a>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>