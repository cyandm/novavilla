import Plyr from "plyr";
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox/fancybox.css";
import "./fancybox-overrides.css";
import {
  bindFancyboxIframeResize,
  resizeFancyboxIframe,
  unbindFancyboxIframeResize,
} from "../functions/fancyboxIframe";

const plyrInstances = new WeakMap();

function handleIframeSlide(slide) {
  if (slide?.type !== "iframe") return;
  requestAnimationFrame(() => resizeFancyboxIframe(slide));
}

function initPlyrOnSlide(slide) {
  if (!slide || (slide.type !== "html5video" && slide.type !== "video")) return;
  const video = slide.el?.querySelector?.("video");
  if (!video || video.plyr) return;
  const player = new Plyr(video, {
    controls: ["play", "progress", "current-time", "mute", "volume", "fullscreen"],
    hideControls: true,
    ratio: null,
  });
  plyrInstances.set(slide.el, player);
}

function destroyPlyrOnSlide(slide) {
  const el = slide?.el;
  if (!el) return;
  const player = plyrInstances.get(el) || el.querySelector?.("video")?.plyr;
  if (player?.destroy) player.destroy();
  plyrInstances.delete(el);
}

export default function fancybox() {
  Fancybox.bind("[data-fancybox]", {
    Html: {
      iframeAttr: {
        allow: "autoplay; fullscreen",
        scrolling: "no",
      },
    },
    on: {
      ready: (fancyboxInstance) => {
        bindFancyboxIframeResize();
        handleIframeSlide(fancyboxInstance.getSlide());
      },
      reveal: (_fancyboxInstance, slide) => {
        handleIframeSlide(slide);
        initPlyrOnSlide(slide);
      },
      change: (_fancyboxInstance, slide) => {
        handleIframeSlide(slide);
      },
      contentReady: (_fancyboxInstance, slide) => {
        handleIframeSlide(slide);
        initPlyrOnSlide(slide);
      },
      close: (fancyboxInstance) => {
        const slide = fancyboxInstance.getSlide?.();
        destroyPlyrOnSlide(slide);
      },
      destroy: () => {
        unbindFancyboxIframeResize();
      },
    },
  });
}
