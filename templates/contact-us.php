<?php /* Template Name: Contact Us */ ?>
<?php

use Cyan\Theme\Helpers\Icon;
use Cyan\Theme\Helpers\Templates;

$contact_title = get_field('contact_title');
$under_title = get_field('under_title');
$contact_img = get_field('contact_img');
$contact_box_title = get_field('contact_box_title');
$contact_box_description = get_field('contact_box_description');
$visit_title = get_field('visit_title');
$opening_hours_title = get_field('opening_hours_title');
$map_address_title = get_field('map_address_title');
$contact_info_title = get_field('contact_info_title');

$address_text = get_option('address_text');
$address_link = get_option('address_link');
$phone_number = get_option('phone_number');
$whatsapp_number = get_option('whatsapp_number');
$instagram_text = get_option('instagram_text');
$instagram_link = get_option('instagram_link');
$telegram_text = get_option('telegram_text');
$telegram_link = get_option('telegram_link');
$email_address = get_option('email_address');
$working_hours = get_option('working_hours');
$working_days = get_option('working_days');

$location = [];
for ($i = 1; $i <= 6; $i++) {
      $img  = get_option('location_image_' . $i);
      $link = get_option('location_link_' . $i);

      if (!empty($img) && !empty($link)) {
            $location[] = [
                  'img'  => $img,
                  'link' => $link,
            ];
      }
}

$social_items = array_filter([
      $phone_number ? [
            'href' => 'tel:' . $phone_number,
            'label' => $phone_number,
            'icon' => 'Phone,-Call-11',
      ] : null,
      $telegram_link ? [
            'href' => $telegram_link,
            'label' => $telegram_text ?: $telegram_link,
            'icon' => 'Telegram',
      ] : null,
      $instagram_link ? [
            'href' => $instagram_link,
            'label' => $instagram_text ?: $instagram_link,
            'icon' => 'Instagram',
      ] : null,
      $whatsapp_number ? [
            'href' => 'https://wa.me/' . preg_replace('/\D+/', '', $whatsapp_number),
            'label' => $whatsapp_number,
            'icon' => 'Whatsup',
      ] : null,
      $email_address ? [
            'href' => 'mailto:' . $email_address,
            'label' => $email_address,
            'icon' => 'Emails,-Letter,-Mail-1',
      ] : null,
]);

get_header();
?>

<?php Templates::getPart('breadcrumb'); ?>

<main class="container pb-10 md:pb-14">

      <section class="flex max-lg:flex-col-reverse gap-5 lg:gap-3">

            <div class="w-full lg:w-3/5 flex flex-col gap-4 justify-center">

                  <div class="flex flex-col gap-1">
                        <p class="text-2xl md:text-4xl font-bold text-cynTextPrimary">
                              <?php echo $contact_title ? $contact_title : __('ارتباط با نووا ویلا', 'novavilla'); ?>
                        </p>

                        <p class="text-cynTextPrimary text-base md:text-2xl font-medium">
                              <?php echo $under_title ? $under_title : __('برای شروع پروژه، با ما در ارتباط باشید', 'novavilla'); ?>
                        </p>
                  </div>

                  <form
                        id="contact_form"
                        action="<?php echo esc_url(rest_url('cyn/v1/contact_form')); ?>"
                        method="post"
                        hx-post="<?php echo esc_url(rest_url('cyn/v1/contact_form')); ?>"
                        hx-headers='{"X-WP-Nonce":"<?php echo esc_attr(wp_create_nonce('wp_rest')); ?>"}'
                        hx-swap="none"
                        hx-disabled-elt="find button[type=submit]"
                        hx-on::before-request="
                              const submitBtn = event.target.querySelector('button[type=submit]');
                              if (submitBtn) submitBtn.classList.add('is-loading');
                        "
                        hx-on::after-request="
                              const submitBtn = event.target.querySelector('button[type=submit]');
                              if (submitBtn) submitBtn.classList.remove('is-loading');
                              const resultEl = document.querySelector('#contact_form_result');
                              if (!resultEl) return;
                              const ok = event.detail.successful;
                              let msg = '';
                              try {
                                    const data = JSON.parse(event.detail.xhr.responseText);
                                    msg = data.message || data.error || '';
                              } catch (e) {
                                    msg = ok
                                          ? '<?php echo esc_js(__('فرم با موفقیت ارسال شد.', 'novavilla')); ?>'
                                          : '<?php echo esc_js(__('خطا در ارسال فرم.', 'novavilla')); ?>';
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
                        class="flex flex-col gap-4 w-5/6 max-lg:w-full [&_label]:w-full [&_input]:w-full [&_textarea]:w-full">

                        <label for="name" class="relative">
                              <div class="size-6 text-cynTextSecondaryHover absolute start-3 top-1/2 -translate-y-1/2 pointer-events-none z-10">
                                    <?php Icon::print('User,-Profile-7'); ?>
                              </div>
                              <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    required
                                    placeholder="<?php _e('نام و نام خانوادگی', 'novavilla'); ?>"
                                    class="focus:outline-none focus:border-cynBorderHover w-full font-medium text-base text-cynTextPrimary placeholder:text-cynTextPrimary/70 ps-10 pe-5 py-3 bg-cynBgItem backdrop-blur-xl rounded-xl border border-cynBorder transition-colors duration-300" />
                        </label>

                        <label for="phone" class="relative">
                              <div class="size-6 text-cynTextSecondaryHover absolute start-3 top-1/2 -translate-y-1/2 pointer-events-none z-10">
                                    <?php Icon::print('Phone,-Call-11'); ?>
                              </div>
                              <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    placeholder="<?php _e('شماره تماس', 'novavilla'); ?>"
                                    pattern="[0-9]{11}"
                                    inputmode="numeric"
                                    maxlength="11"
                                    required
                                    dir="rtl"
                                    class="focus:outline-none focus:border-cynBorderHover w-full font-medium text-base text-cynTextPrimary placeholder:text-cynTextPrimary/70 ps-10 pe-5 py-3 bg-cynBgItem backdrop-blur-xl rounded-xl border border-cynBorder transition-colors duration-300" />
                        </label>

                        <label for="email" class="relative">
                              <div class="size-6 text-cynTextSecondaryHover absolute start-3 top-1/2 -translate-y-1/2 pointer-events-none z-10">
                                    <?php Icon::print('email-mail-letter'); ?>
                              </div>
                              <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder="<?php _e('ایمیل (اختیاری)', 'novavilla'); ?>"
                                    class="focus:outline-none focus:border-cynBorderHover w-full font-medium text-base text-cynTextPrimary placeholder:text-cynTextPrimary/70 ps-10 pe-5 py-3 bg-cynBgItem backdrop-blur-xl rounded-xl border border-cynBorder transition-colors duration-300" />
                        </label>

                        <label for="message" class="relative">
                              <div class="size-6 text-cynTextSecondaryHover absolute start-3 top-3 pointer-events-none z-10">
                                    <?php Icon::print('Chat,-Messages-1'); ?>
                              </div>
                              <textarea name="message" id="message" rows="3" maxlength="65525" placeholder="<?php _e('پیام شما', 'novavilla'); ?>" required class="focus:outline-none focus:border-cynBorderHover w-full font-medium text-base text-cynTextPrimary placeholder:text-cynTextPrimary/70 resize-y m-0 align-bottom ps-10 pe-5 py-3 bg-cynBgItem backdrop-blur-xl rounded-xl border border-cynBorder transition-colors duration-300"></textarea>
                        </label>

                        <div class="flex justify-end">
                              <button type="submit" class="submit-contact-btn primary-button flex justify-center items-center gap-1">
                                    <span class="submit-contact-btn__idle flex items-center gap-1">
                                          <span class="size-5 stroke-[1.5] flex items-center justify-center">
                                                <?php Icon::print('Send') ?>
                                          </span>
                                          <span>
                                                <?php _e('ارسال پیام', 'novavilla'); ?>
                                          </span>
                                    </span>
                                    <span class="submit-contact-btn__loading items-center gap-1">
                                          <span class="size-5 stroke-[1.5] flex items-center justify-center animate-spin">
                                                <?php Icon::print('Rotate,-Refresh,-Loading') ?>
                                          </span>
                                          <span>
                                                <?php _e('در حال ارسال...', 'novavilla'); ?>
                                          </span>
                                    </span>
                              </button>
                        </div>

                  </form>

                  <div id="contact_form_result" class="text-cynWhite text-base font-semibold rounded-2xl p-3 shadow-item fixed top-4 right-4 z-50" style="display:none; opacity: 0;"></div>

            </div>

            <div class="flex justify-center items-center lg:w-2/5">
                  <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-contain sm:w-2/3 lg:w-full']) ?>
            </div>

      </section>

      <section class="bg-cynBgItem rounded-3xl border border-cynBorder mt-11 md:mt-16 p-4 hover:border-cynBorderHover transition-colors duration-300">

            <p class="text-2xl md:text-4xl font-bold text-cynTextPrimary">
                  <?php echo $contact_box_title ? $contact_box_title : __('مراجعه حضوری', 'novavilla'); ?>
            </p>

            <p class="text-cynTextPrimary text-base md:text-xl font-medium mt-2">
                  <?php echo $contact_box_description ? $contact_box_description : __('برای بازدید از نمونه محصولات، بررسی متریال‌ها و گفت‌وگوی حضوری با کارشناسان، می‌توانید با هماهنگی قبلی به دفتر یا مجموعه تولیدی ما مراجعه کنید.', 'novavilla'); ?>
            </p>

            <div class="flex flex-col gap-3 mt-6">

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                        <div class="flex flex-col rounded-2xl border border-cynBorder p-4 bg-cynBgItem backdrop-blur-xl hover:border-cynBorderHover transition-colors duration-300">

                              <p class="text-2xl font-normal text-cynTextPrimary">
                                    <?php echo $visit_title ? $visit_title : __('آدرس', 'novavilla'); ?>
                              </p>

                              <?php if ($address_text) : ?>
                                    <?php if ($address_link) : ?>
                                          <a href="<?php echo esc_url($address_link); ?>" target="_blank" rel="noopener noreferrer" class="font-normal text-base text-cynTextPrimary mt-2 hover:text-cynBorderHover transition-colors duration-300 w-fit">
                                                <?php echo esc_html($address_text); ?>
                                          </a>
                                    <?php else : ?>
                                          <p><?php echo esc_html($address_text); ?></p>
                                    <?php endif; ?>
                              <?php endif; ?>

                              <p class="text-2xl font-normal text-cynTextPrimary mt-5">
                                    <?php echo $map_address_title ? $map_address_title : __('آدرس روی نقشه', 'novavilla'); ?>
                              </p>

                              <?php if ($location) : ?>
                                    <div class="flex gap-2.5 items-center flex-wrap mt-1">
                                          <?php foreach ($location as $loc) : ?>
                                                <a href="<?php echo esc_url($loc['link']); ?>" rel="noopener noreferrer" target="_blank" class="size-8 flex items-center justify-center rounded-lg overflow-hidden shrink-0 w-fit">
                                                      <img src="<?php echo esc_url($loc['img']); ?>" alt="" class="size-full object-contain">
                                                </a>
                                          <?php endforeach; ?>
                                    </div>
                              <?php endif; ?>

                        </div>

                        <?php if ($working_days || $working_hours) : ?>
                              <div class="flex flex-col rounded-2xl border border-cynBorder p-4 bg-cynBgItem backdrop-blur-xl hover:border-cynBorderHover transition-colors duration-300">
                                    <p class="text-2xl font-normal text-cynTextPrimary">
                                          <?php echo $opening_hours_title ? $opening_hours_title : __('ساعات فعال', 'novavilla'); ?>
                                    </p>
                                    <?php if ($working_days) : ?>
                                          <p class="font-normal text-base text-cynTextPrimary mt-2">
                                                <?php echo esc_html($working_days); ?>
                                          </p>
                                    <?php endif; ?>
                                    <?php if ($working_hours) : ?>
                                          <p class="font-normal text-base text-cynTextPrimary mt-1">
                                                <?php echo esc_html($working_hours); ?>
                                          </p>
                                    <?php endif; ?>
                              </div>
                        <?php endif; ?>

                  </div>

                  <?php if ($social_items) : ?>
                        <div class="flex flex-col rounded-2xl border border-cynBorder p-4 bg-cynBgItem backdrop-blur-xl hover:border-cynBorderHover transition-colors duration-300">

                              <p class="text-2xl font-normal text-cynTextPrimary">
                                    <?php echo $contact_info_title ? $contact_info_title : __('شماره تماس و شبکه های اجتماعی', 'novavilla'); ?>
                              </p>

                              <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 max-w-2xl">
                                    <?php foreach ($social_items as $item) : ?>
                                          <a href="<?php echo esc_url($item['href']); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-sm font-medium text-cynTextPrimary/80 hover:text-cynBorderHover transition-colors duration-300 w-fit">
                                                <i class="bg-cynBorderHover text-cynWhite p-1.5 rounded-full flex justify-center items-center size-7 shrink-0 [&_svg]:size-3.5 stroke-[1.5]">
                                                      <?php Icon::print($item['icon']); ?>
                                                </i>
                                                <span class="truncate pt-0.5"><?php echo esc_html($item['label']); ?></span>
                                          </a>
                                    <?php endforeach; ?>
                              </div>

                        </div>
                  <?php endif; ?>

            </div>

      </section>

</main>

<?php get_footer(); ?>