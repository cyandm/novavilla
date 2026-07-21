const STORAGE_KEY = "novavilla-theme";
const TRANSITION_CLASS = "theme-transition";
const TRANSITION_DURATION = 300;

function getPreferredTheme() {
  return "dark";
}

function getTheme() {
  return document.documentElement.getAttribute("data-theme") || "dark";
}

function updateToggleState(theme) {
  const isDark = theme === "dark";

  document.querySelectorAll("[theme-toggle]").forEach((button) => {
    button.setAttribute("aria-pressed", String(isDark));
    button.setAttribute(
      "aria-label",
      isDark ? "فعال کردن تم روشن" : "فعال کردن تم تیره",
    );
  });
}

function setTransitionOrigin(event) {
  const clickX =
    typeof event?.clientX === "number" ? event.clientX : window.innerWidth / 2;
  const clickY =
    typeof event?.clientY === "number" ? event.clientY : window.innerHeight / 2;
  const endRadius = Math.hypot(
    Math.max(clickX, window.innerWidth - clickX),
    Math.max(clickY, window.innerHeight - clickY),
  );

  document.documentElement.style.setProperty("--theme-x", `${clickX}px`);
  document.documentElement.style.setProperty("--theme-y", `${clickY}px`);
  document.documentElement.style.setProperty("--theme-r", `${endRadius}px`);
}

function applyTheme(theme) {
  document.documentElement.setAttribute("data-theme", theme);
  localStorage.setItem(STORAGE_KEY, theme);
  updateToggleState(theme);
}

function fallbackTransition(theme) {
  document.documentElement.classList.add(TRANSITION_CLASS);
  applyTheme(theme);

  window.setTimeout(() => {
    document.documentElement.classList.remove(TRANSITION_CLASS);
  }, TRANSITION_DURATION);
}

function setTheme(theme, event) {
  setTransitionOrigin(event);

  if (
    document.startViewTransition &&
    !window.matchMedia("(prefers-reduced-motion: reduce)").matches
  ) {
    document.startViewTransition(() => applyTheme(theme));
    return;
  }

  fallbackTransition(theme);
}

function toggleTheme(event) {
  const nextTheme = getTheme() === "dark" ? "light" : "dark";
  setTheme(nextTheme, event);
}

export function ThemeToggle() {
  const initialTheme = localStorage.getItem(STORAGE_KEY) || getPreferredTheme();
  updateToggleState(initialTheme);

  document.addEventListener("click", (event) => {
    const button = event.target.closest("[theme-toggle]");

    if (!button) {
      return;
    }

    toggleTheme(event);
  });
}
