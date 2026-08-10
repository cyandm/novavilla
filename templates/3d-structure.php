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
$structure_cta_image_light = get_field('structure_cta_image_light');
$structure_cta_image_light_url = is_array($structure_cta_image_light) ? ($structure_cta_image_light['url'] ?? '') : $structure_cta_image_light;
$structure_cta_button_text = get_field('structure_cta_button_text');
if (empty($structure_cta_button_text)) {
	$structure_cta_booking_legacy = get_field('structure_cta_booking_btn');
	$structure_cta_button_text = is_array($structure_cta_booking_legacy) ? ($structure_cta_booking_legacy['title'] ?? '') : '';
}
$structure_cta_button_text = $structure_cta_button_text ?: __('درخواست جلسه حضوری', 'novavilla');
$structure_cta_input_placeholder = get_field('structure_cta_input_placeholder') ?: __('ایمیل یا شماره همراه خود را وارد کنید', 'novavilla');

get_header();
?>

<?php Templates::getPart('breadcrumb'); ?>

<main class="structure-3d-page container space-y-11 md:space-y-16">

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
				<h1 class="text-2xl md:text-4xl font-bold text-cynTextPrimary leading-9 md:leading-14">
					<span class="block"><?php echo esc_html($structure_hero_title); ?></span>
					<span class="block text-cynBorderHover"><?php echo esc_html($structure_hero_subtitle); ?></span>
				</h1>
				<div class="text-sm md:text-base font-light text-cynTextPrimary leading-5 md:leading-7"><?php echo $structure_hero_description; ?></div>
			</div>

			<div class="flex flex-wrap items-center gap-2">
				<?php if (!empty($structure_consultation_btn['url'])) : ?>
					<a href="<?php echo esc_url($structure_consultation_btn['url']); ?>" <?php echo !empty($structure_consultation_btn['target']) ? 'target="' . esc_attr($structure_consultation_btn['target']) . '"' : ''; ?> class="primary-button">
						<span class="whitespace-nowrap text-xs md:text-sm font-semibold"><?php echo esc_html($structure_consultation_btn['title'] ?: __('دریافت مشاوره', 'novavilla')); ?></span>
					</a>
				<?php endif; ?>
				<?php if (!empty($structure_booking_btn['url'])) : ?>
					<a href="<?php echo esc_url($structure_booking_btn['url']); ?>" <?php echo !empty($structure_booking_btn['target']) ? 'target="' . esc_attr($structure_booking_btn['target']) . '"' : ''; ?> class="primary-button btn-have-icon">
						<span class="flex items-center gap-1 whitespace-nowrap text-xs md:text-sm font-semibold">
							<?php echo esc_html($structure_booking_btn['title'] ?: __('رزرو جلسه طراحی حضوری', 'novavilla')); ?>
							<i class="size-5 flex items-center justify-center [&_svg]:stroke-[2]">
								<?php Icon::print('Arrow-27'); ?>
							</i>
						</span>
					</a>
				<?php endif; ?>

			</div>
		</div>

	</section>

	<?php if (!empty($structure_session_steps)) : ?>
		<section class="flex flex-col gap-3">
			<h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12"><?php echo esc_html($structure_session_title); ?></h2>

			<div class="structure-session-steps flex items-start justify-between gap-2 max-[376px]:grid max-[376px]:grid-cols-5 max-[376px]:gap-1 min-[376px]:max-md:gap-0.5 md:gap-3 md:flex w-full min-w-0">
				<?php foreach ($structure_session_steps as $step) : ?>
					<div class="flex flex-col items-center gap-2 max-[376px]:gap-1 md:gap-3 w-[66px] max-[376px]:w-auto max-[376px]:min-w-0 md:w-auto md:flex-1 shrink-0 max-[376px]:shrink text-center group">
						<div class="relative z-[1] size-[66px] min-w-[66px] max-w-[66px] max-[376px]:size-12 max-[376px]:min-w-0 max-[376px]:max-w-none max-[376px]:mx-auto md:size-32 md:min-w-0 md:max-w-none rounded-full border-2 border-cynBorder bg-cynBgItem backdrop-blur-xl p-3.5 max-[376px]:p-2.5 md:p-8 group-hover:border-cynBorderHover transition-all duration-300">
							<?php if (!empty($step['image'])) : ?>
								<img src="<?php echo esc_url($step['image']); ?>" alt="<?php echo esc_attr($step['title']); ?>" class="size-full object-contain" loading="lazy" decoding="async" />
							<?php endif; ?>
							<span class="absolute bottom-0 start-0 flex items-center justify-center size-5 max-[376px]:size-4 md:size-[30px] rounded-full bg-cynBorderHover border border-white/35 text-[10px] max-[376px]:text-[8px] md:text-xs font-semibold text-cynWhite group-hover:scale-120 transition-all duration-300"><?php echo esc_html($step['number']); ?></span>
						</div>
						<div class="flex flex-col gap-1 max-w-40 max-[376px]:max-w-none min-w-0 w-full">
							<h3 class="text-[10px] max-[376px]:text-[9px] max-[376px]:leading-3 max-[376px]:break-words lg:text-sm font-medium text-cynTextPrimary leading-4 lg:leading-6"><?php echo esc_html($step['title']); ?></h3>
							<?php if (!empty($step['desc'])) : ?><p class="hidden lg:block text-[10px] font-normal text-cynTextPrimary leading-4"><?php echo esc_html($step['desc']); ?></p><?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if (!empty($structure_review_items)) : ?>
		<section class="flex flex-col gap-3">
			<h2 class="text-xl font-medium md:text-4xl md:font-bold text-cynTextPrimary md:leading-12"><?php echo esc_html($structure_review_title); ?></h2>

			<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
				<?php foreach ($structure_review_items as $item) : ?>
					<div class="flex flex-col gap-3 rounded-[20px] border border-cynBorderHover/40 hover:border-cynBorderHover transition-all duration-300 bg-cynBgItem/80 backdrop-blur-xl p-4 h-full">
						<?php if (!empty($item['image'])) : ?>
							<img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="w-full h-28 object-cover rounded-xl" loading="lazy" decoding="async" />
						<?php else : ?>
							<div class="w-full h-28 rounded-xl bg-cynBgSocial/40" aria-hidden="true"></div>
						<?php endif; ?>
						<div class="flex flex-col gap-1">
							<?php if (!empty($item['title'])) : ?><h3 class="text-sm font-medium text-cynTextPrimary"><?php echo esc_html($item['title']); ?></h3><?php endif; ?>
							<?php if (!empty($item['desc'])) : ?><p class="text-[10px] font-normal text-cynTextPrimary leading-4 md:leading-5"><?php echo esc_html($item['desc']); ?></p><?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="relative overflow-hidden rounded-[20px] border border-cynBorderHover/40 dark:bg-[#151516] min-h-[480px] lg:min-h-[353px]">
		<?php if (!empty($structure_cta_image_url) || !empty($structure_cta_image_light_url)) : ?>
			<div class="absolute end-0 -top-[6px] lg:-top-[19px] w-full h-[217px] lg:h-[calc(100%+19px)] lg:w-[60%] lg:max-w-[694px]">
				<?php if (!empty($structure_cta_image_light_url)) : ?>
					<img src="<?php echo esc_url($structure_cta_image_light_url); ?>" alt="<?php echo esc_attr($structure_cta_title); ?>" class="size-full object-cover<?php echo !empty($structure_cta_image_url) ? ' dark:hidden' : ''; ?>" loading="lazy" decoding="async" />
				<?php endif; ?>
				<?php if (!empty($structure_cta_image_url)) : ?>
					<img src="<?php echo esc_url($structure_cta_image_url); ?>" alt="<?php echo esc_attr($structure_cta_title); ?>" class="size-full object-cover<?php echo !empty($structure_cta_image_light_url) ? ' hidden dark:block' : ''; ?>" loading="lazy" decoding="async" />
				<?php endif; ?>
				<div class="absolute inset-x-0 bottom-0 h-1/4 bg-gradient-to-t from-[#fbfbfb] to-transparent dark:from-[#151516] dark:to-transparent lg:inset-x-auto lg:start-0 lg:bottom-0 lg:top-0 lg:h-full lg:w-1/3 lg:bg-gradient-to-l lg:from-[#fbfbfb] lg:to-transparent dark:lg:from-[#151516] dark:lg:to-transparent pointer-events-none" aria-hidden="true"></div>
			</div>
		<?php endif; ?>

		<div class="relative z-[1] flex flex-col lg:items-start lg:justify-center p-4 lg:p-10 min-h-[480px] lg:min-h-[353px]">
			<div class="flex flex-col gap-5 lg:gap-[23px] w-full lg:max-w-[546px] mt-auto lg:mt-0 pt-[180px] lg:pt-0">
				<div class="flex flex-col gap-2">
					<h2 class="text-xl font-bold text-cynTextPrimary leading-tight lg:text-4xl lg:leading-[56px]"><?php echo esc_html($structure_cta_title); ?></h2>
					<p class="text-xs font-light text-cynTextPrimary leading-4 md:text-sm lg:text-base lg:leading-6"><?php echo esc_html($structure_cta_description); ?></p>
				</div>

				<form
					id="structure_session_form"
					action="<?php echo esc_url(rest_url('cyn/v1/session_request')); ?>"
					method="post"
					hx-post="<?php echo esc_url(rest_url('cyn/v1/session_request')); ?>"
					hx-headers='{"X-WP-Nonce":"<?php echo esc_attr(wp_create_nonce('wp_rest')); ?>"}'
					hx-swap="none"
					hx-disabled-elt="find button[type=submit]"
					hx-on::before-request="const btn = event.target.querySelector('button[type=submit]'); if (btn) btn.classList.add('is-loading');"
					hx-on::after-request="
						const btn = event.target.querySelector('button[type=submit]');
						if (btn) btn.classList.remove('is-loading');
						const resultEl = document.querySelector('#structure_session_form_result');
						if (!resultEl) return;
						const ok = event.detail.successful;
						let msg = '';
						try {
							const data = JSON.parse(event.detail.xhr.responseText);
							msg = data.message || data.error || '';
						} catch (e) {
							msg = ok ? '<?php echo esc_js(__('درخواست شما با موفقیت ثبت شد.', 'novavilla')); ?>' : '<?php echo esc_js(__('خطا در ارسال فرم.', 'novavilla')); ?>';
						}
						resultEl.textContent = msg;
						resultEl.classList.toggle('bg-green-500', ok);
						resultEl.classList.toggle('bg-cynRed', !ok);
						resultEl.style.display = 'block';
						resultEl.style.opacity = '1';
						resultEl.style.transition = 'opacity 0.5s ease-out';
						if (ok) event.target.reset();
						clearTimeout(resultEl._hideTimer);
						resultEl._hideTimer = setTimeout(() => {
							resultEl.style.opacity = '0';
							setTimeout(() => { resultEl.style.display = 'none'; }, 500);
						}, 5000);
					"
					class="flex flex-col lg:flex-row items-stretch lg:items-center gap-2 w-3/4 sm:w-2/4 lg:w-full">
					<input type="hidden" name="request_type" value="session" />
					<input type="hidden" name="source_page_id" value="<?php echo (int) get_the_ID(); ?>" />
					<label class="relative flex-1 min-w-0">
						<i class="absolute start-3 top-1/2 -translate-y-1/2 size-7 shrink-0 flex items-center justify-center text-cynBorderHover pointer-events-none [&_svg]:stroke-[1.5]"><?php Icon::print('email-mail-letter'); ?></i>
						<input type="text" name="contact" required placeholder="<?php echo esc_attr($structure_cta_input_placeholder); ?>" class="w-full h-11 rounded-xl border border-cynBorderHover/40 bg-white/[0.08] px-4 ps-11 text-xs md:text-sm text-cynTextPrimary placeholder:text-cynTextPrimary/50 focus:outline-none focus:border-cynBorderHover transition-colors" />
					</label>
					<button type="submit" class="submit-contact-btn primary-button btn-have-icon flex justify-center items-start gap-1 w-fit">
						<span class="submit-contact-btn__idle flex items-center gap-1 whitespace-nowrap text-xs md:text-sm font-semibold">
							<?php echo esc_html($structure_cta_button_text); ?>
							<i class="size-5 flex items-center justify-center [&_svg]:stroke-[2]"><?php Icon::print('Arrow-27'); ?></i>
						</span>
						<span class="submit-contact-btn__loading items-center gap-1 whitespace-nowrap text-xs md:text-sm font-semibold">
							<span class="size-5 stroke-[1.5] flex items-center justify-center animate-spin"><?php Icon::print('Rotate,-Refresh,-Loading'); ?></span>
							<span><?php _e('در حال ارسال...', 'novavilla'); ?></span>
						</span>
					</button>
				</form>
			</div>
		</div>

		<div id="structure_session_form_result" class="text-cynWhite text-base font-semibold rounded-2xl p-3 shadow-item fixed top-4 right-4 z-50" style="display:none; opacity: 0;" role="status" aria-live="polite"></div>

	</section>

</main>

<?php get_footer(); ?>