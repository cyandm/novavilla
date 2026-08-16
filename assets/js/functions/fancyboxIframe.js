import { Fancybox } from "@fancyapps/ui";

const APARAT_ASPECT = 16 / 9;
const APARAT_VIEWPORT_PADDING = 88;
const APARAT_VIEWPORT_PADDING_MOBILE = 72;
const MOBILE_MAX_WIDTH = 767;

export function resizeFancyboxIframe(slide) {
  if (!slide || slide.type !== "iframe") return;

  const htmlEl = slide.htmlEl || slide.el?.querySelector?.(".f-html") || null;
  if (!htmlEl) return;

  const isMobile = window.innerWidth <= MOBILE_MAX_WIDTH;
  const viewportPadding = isMobile ? APARAT_VIEWPORT_PADDING_MOBILE : APARAT_VIEWPORT_PADDING;
  const widthRatio = isMobile ? 0.96 : 0.92;
  const viewportH = window.visualViewport?.height ?? window.innerHeight ?? 0;
  const viewportW = window.visualViewport?.width ?? window.innerWidth ?? 0;
  const maxW = viewportW * widthRatio;
  const maxH = viewportH * 0.92 - viewportPadding;

  let width = maxW;
  let height = width / APARAT_ASPECT;
  if (height > maxH) {
    height = Math.max(maxH, 0);
    width = height * APARAT_ASPECT;
  }

  htmlEl.style.setProperty("width", `${width}px`, "important");
  htmlEl.style.setProperty("height", `${height}px`, "important");
  htmlEl.style.setProperty("max-width", `${widthRatio * 100}vw`, "important");
  htmlEl.style.setProperty("max-height", `${maxH}px`, "important");
  htmlEl.style.setProperty("padding", "0", "important");
  htmlEl.style.overflow = "hidden";
  htmlEl.style.position = "relative";

  const iframe = htmlEl.querySelector(".f-iframe");
  if (iframe) {
    iframe.style.position = "absolute";
    iframe.style.inset = "0";
    iframe.style.width = "100%";
    iframe.style.height = "100%";
  }
}

let resizeTimer;
let resizeListenerAttached = false;

function handleFancyboxResize() {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(() => {
    const instance = Fancybox.getInstance?.();
    if (!instance) return;
    resizeFancyboxIframe(instance.getSlide());
  }, 100);
}

export function bindFancyboxIframeResize() {
  if (resizeListenerAttached) return;
  window.addEventListener("resize", handleFancyboxResize);
  window.visualViewport?.addEventListener("resize", handleFancyboxResize);
  resizeListenerAttached = true;
}

export function unbindFancyboxIframeResize() {
  if (!resizeListenerAttached) return;
  window.removeEventListener("resize", handleFancyboxResize);
  window.visualViewport?.removeEventListener("resize", handleFancyboxResize);
  resizeListenerAttached = false;
}
