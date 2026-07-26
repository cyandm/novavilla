export default function videoCover() {
  const videoCovers = document.querySelectorAll(".video-cover");
  if (!videoCovers.length) return;

  videoCovers.forEach((cover) => {
    cover.addEventListener("click", (event) => {
      event.preventDefault();

      const wrap = cover.closest(".video-player") || cover.parentElement;
      const videoElement = wrap?.querySelector("video");
      if (!videoElement) return;

      document.querySelectorAll(".video-player video, video.video").forEach((video) => {
        if (video === videoElement) return;
        if (video.plyr) video.plyr.pause();
        else video.pause();
      });

      const player = videoElement.plyr;
      const playPromise = player ? player.play() : videoElement.play();

      Promise.resolve(playPromise)
        .then(() => {
          cover.classList.replace("opacity-100", "opacity-0");
          cover.classList.replace("pointer-events-auto", "pointer-events-none");
          cover.setAttribute("aria-hidden", "true");
        })
        .catch((error) => {
          console.error("Error playing video:", error);
        });
    });
  });
}
