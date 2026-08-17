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
  const mainEl = root.querySelector("#product-gallery-main");
  const bindThumbsSync = () => {
    const mainSwiper = mainEl?.swiper;
    if (!mainSwiper?.thumbs?.update || mainSwiper.__thumbsSyncBound) return;
    mainSwiper.__thumbsSyncBound = true;
    const origUpdate = mainSwiper.thumbs.update;
    mainSwiper.thumbs.update = (initial, p2 = {}) => origUpdate(initial, { ...p2, autoScroll: false });
    const scrollActiveThumb = () => {
      const thumbsSwiper = thumbsEl.swiper;
      const slide = thumbsSwiper?.slides?.[mainSwiper.realIndex];
      if (!slide) return;
      slide.scrollIntoView({ block: "nearest", inline: "nearest", behavior: "smooth" });
    };
    mainSwiper.on("slideChange", scrollActiveThumb);
  };
  const configureThumbs = (swiper) => {
    if (!swiper) return;
    swiper.params.cssMode = true;
    swiper.params.freeMode = { enabled: true, sticky: false, momentum: false };
    swiper.params.watchSlidesProgress = false;
    if (!swiper.__lockSlideClasses) {
      swiper.__lockSlideClasses = true;
      swiper.updateSlidesClasses = () => {};
      swiper.updateActiveIndex = () => {};
    }
    swiper.update();
  };
  const bindThumbs = () => {
    configureThumbs(thumbsEl.swiper);
    thumbsEl.swiper?.on("breakpoint", () => configureThumbs(thumbsEl.swiper));
    bindThumbsSync();
  };
  if (thumbsEl) {
    if (thumbsEl.swiper) bindThumbs();
    else thumbsEl.addEventListener("swiperinit", bindThumbs, { once: true });
    if (mainEl && !mainEl.swiper) mainEl.addEventListener("swiperinit", bindThumbsSync, { once: true });
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
    if (
      Math.hypot(event.clientX - pointerX, event.clientY - pointerY) >
      DRAG_THRESHOLD
    )
      return;
    event.preventDefault();
    event.stopPropagation();
    openProductGalleryItem(delegateEl);
  });
}
