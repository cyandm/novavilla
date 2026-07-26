import Plyr from "plyr";
import "plyr/dist/plyr.css";

export function VideoPlyr() {
  document.querySelectorAll(".video-plyr").forEach((el) => {
    const wrap = el.closest(".video-player");
    const hasCustomCover = Boolean(wrap?.querySelector(".video-cover"));

    const player = new Plyr(el, {
      controls: ["play", "progress", "current-time", "mute", "volume", "fullscreen"],
      hideControls: true,
      ratio: null,
    });

    if (hasCustomCover) {
      player.on("ready", () => {
        const overlaid = wrap.querySelector(".plyr__control--overlaid");
        if (overlaid) overlaid.style.display = "none";
      });
    }
  });
}
