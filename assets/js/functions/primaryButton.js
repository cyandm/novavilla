const CLIP_SELECTOR = ".primary-button__clip";
const RIPPLE_SELECTOR = ".primary-button__ripple";

function getRippleDiameter(button, x, y) {
  const rect = button.getBoundingClientRect();
  const farthestCorner = Math.max(
    Math.hypot(x, y),
    Math.hypot(rect.width - x, y),
    Math.hypot(x, rect.height - y),
    Math.hypot(rect.width - x, rect.height - y),
  );

  return farthestCorner * 2 + 4;
}

function setRippleState(button, event) {
  const ripple = button.querySelector(RIPPLE_SELECTOR);

  if (!ripple) {
    return;
  }

  const rect = button.getBoundingClientRect();
  const x = event.clientX - rect.left;
  const y = event.clientY - rect.top;

  ripple.style.left = `${x}px`;
  ripple.style.top = `${y}px`;
  ripple.style.setProperty(
    "--ripple-size",
    `${getRippleDiameter(button, x, y)}px`,
  );
}

function ensureRipple(button) {
  if (button.querySelector(CLIP_SELECTOR)) {
    return;
  }

  button.querySelector(RIPPLE_SELECTOR)?.remove();

  const clip = document.createElement("span");
  clip.className = "primary-button__clip";
  clip.setAttribute("aria-hidden", "true");

  const ripple = document.createElement("span");
  ripple.className = "primary-button__ripple";
  clip.appendChild(ripple);
  button.prepend(clip);
}

export function PrimaryButton() {
  document.querySelectorAll(".primary-button").forEach((button) => {
    ensureRipple(button);

    button.addEventListener("mouseenter", (event) => {
      setRippleState(button, event);
    });

    button.addEventListener("mouseleave", (event) => {
      setRippleState(button, event);
    });
  });
}
