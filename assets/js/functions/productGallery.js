import { Fancybox } from "@fancyapps/ui";

const DRAG_THRESHOLD = 8;

export function openProductGalleryItem(delegateEl) {
  const group = delegateEl.dataset.fancyboxDelegate;
  const index = parseInt(delegateEl.dataset.fancyboxIndex || "0", 10);
  const anchors = document.querySelectorAll(`[data-fancybox="${group}"]`);
  const anchor = anchors[index];
  if (!anchor) return;

  Fancybox.fromTriggerEl(anchor, { startIndex: index, delegateEl });
}

export function ProductGallery() {
  const root = document.querySelector("[data-product-gallery]");
  if (!root) return;

  const thumbsEl = root.querySelector("#product-gallery-thumbs");
  const configureThumbs = (swiper) => {
    if (!swiper) return;
    swiper.params.freeMode = { enabled: true, sticky: false, momentum: true, momentumBounce: false };
    swiper.params.watchSlidesProgress = true;
    swiper.params.resistanceRatio = 0.55;
    swiper.params.mousewheel = { enabled: false };
    swiper.mousewheel?.disable?.();
    swiper.update();
  };
  const bindThumbs = () => {
    configureThumbs(thumbsEl.swiper);
    thumbsEl.swiper?.on("breakpoint", () => configureThumbs(thumbsEl.swiper));
  };
  if (thumbsEl) {
    if (thumbsEl.swiper) bindThumbs();
    else thumbsEl.addEventListener("swiperinit", bindThumbs, { once: true });

    let wheelTarget = null;
    let wheelRaf = 0;
    const clampTranslate = (swiper, value) => Math.min(swiper.minTranslate(), Math.max(swiper.maxTranslate(), value));
    const wheelStep = (event, swiper) => {
      let raw = swiper.isHorizontal()
        ? (Math.abs(event.deltaX) > Math.abs(event.deltaY) ? event.deltaX : event.deltaY)
        : event.deltaY;
      if (event.deltaMode === 1) raw *= 16;
      else if (event.deltaMode === 2) raw *= swiper.size;
      const slideSize = swiper.slidesSizesGrid?.[0] || (swiper.isHorizontal() ? 120 : 192);
      const cap = slideSize * 0.28;
      return Math.sign(raw) * Math.min(Math.abs(raw) * 0.25, cap);
    };
    const easeWheel = () => {
      const swiper = thumbsEl.swiper;
      if (!swiper || wheelTarget === null) {
        wheelRaf = 0;
        return;
      }
      const current = swiper.getTranslate();
      const next = current + (wheelTarget - current) * 0.12;
      swiper.setTransition(0);
      swiper.setTranslate(next);
      swiper.updateProgress();
      if (Math.abs(wheelTarget - next) > 0.5) {
        wheelRaf = requestAnimationFrame(easeWheel);
        return;
      }
      swiper.setTranslate(wheelTarget);
      wheelTarget = null;
      wheelRaf = 0;
    };

    thumbsEl.addEventListener("wheel", (event) => {
      const swiper = thumbsEl.swiper;
      if (!swiper) return;
      const step = wheelStep(event, swiper);
      if (!step) return;
      const from = wheelTarget === null ? swiper.getTranslate() : wheelTarget;
      const clamped = clampTranslate(swiper, from - step);
      if (clamped === from) return;
      event.preventDefault();
      wheelTarget = clamped;
      if (!wheelRaf) wheelRaf = requestAnimationFrame(easeWheel);
    }, { passive: false });
  }

  let pointerX = 0;
  let pointerY = 0;

  root.addEventListener("pointerdown", (event) => {
    pointerX = event.clientX;
    pointerY = event.clientY;
  });

  root.addEventListener("click", (event) => {
    const delegateEl = event.target.closest("[data-fancybox-delegate]");
    if (!delegateEl) return;
    if (Math.hypot(event.clientX - pointerX, event.clientY - pointerY) > DRAG_THRESHOLD) return;
    event.preventDefault();
    event.stopPropagation();
    openProductGalleryItem(delegateEl);
  });
}
