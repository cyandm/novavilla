const STORAGE_KEY = "novavilla-theme";
const TRANSITION_CLASS = "theme-transition";
const TRANSITION_DURATION = 300;
const HOVER_MEDIA = "(hover: hover) and (pointer: fine)";

let pointerInsideDock = false;
let isThemeTransitioning = false;
let pendingPointerLeave = false;

function getPreferredTheme() {
  return "dark";
}

function getTheme() {
  return document.documentElement.getAttribute("data-theme") || "dark";
}

function getDock() {
  return document.querySelector("[theme-toggle-dock]");
}

function getToggleButton() {
  return document.querySelector("[theme-toggle]");
}

function hasHoverPointer() {
  return window.matchMedia(HOVER_MEDIA).matches;
}

function isExpanded() {
  return getDock()?.dataset.expanded === "true";
}

function setExpanded(expanded) {
  if (!expanded && isThemeTransitioning && pointerInsideDock) {
    return;
  }

  const dock = getDock();
  const button = getToggleButton();

  if (!dock || !button) {
    return;
  }

  dock.dataset.expanded = String(expanded);
  button.dataset.expanded = String(expanded);
  button.setAttribute("aria-expanded", String(expanded));
}

function collapseDock() {
  pointerInsideDock = false;
  setExpanded(false);
}

function syncDesktopExpandedFromHover() {
  const dock = getDock();

  if (!dock || !hasHoverPointer()) {
    return;
  }

  const hovered = dock.matches(":hover");

  pointerInsideDock = hovered;
  setExpanded(hovered);
}

function finishThemeTransition() {
  isThemeTransitioning = false;

  if (!hasHoverPointer()) {
    return;
  }

  if (pendingPointerLeave) {
    pendingPointerLeave = false;
    collapseDock();
    return;
  }

  requestAnimationFrame(syncDesktopExpandedFromHover);
}

function updateToggleState(theme) {
  const isDark = theme === "dark";
  const button = getToggleButton();

  if (!button) {
    return;
  }

  button.setAttribute("aria-pressed", String(isDark));
  button.setAttribute(
    "aria-label",
    isDark ? "فعال کردن تم روشن" : "فعال کردن تم تیره",
  );
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

  return new Promise((resolve) => {
    window.setTimeout(() => {
      document.documentElement.classList.remove(TRANSITION_CLASS);
      resolve();
    }, TRANSITION_DURATION);
  });
}

function setTheme(theme, event) {
  setTransitionOrigin(event);

  if (
    document.startViewTransition &&
    !window.matchMedia("(prefers-reduced-motion: reduce)").matches
  ) {
    return document.startViewTransition(() => applyTheme(theme)).finished;
  }

  return fallbackTransition(theme);
}

function toggleTheme(event) {
  const nextTheme = getTheme() === "dark" ? "light" : "dark";
  return setTheme(nextTheme, event);
}

function handleScroll() {
  const dock = getDock();

  if (!dock) {
    return;
  }

  if (hasHoverPointer()) {
    syncDesktopExpandedFromHover();
    return;
  }

  if (isExpanded()) {
    setExpanded(false);
  }
}

function handleTouchScroll(event) {
  if (hasHoverPointer() || !isExpanded()) {
    return;
  }

  if (event.target.closest("[theme-toggle-dock]")) {
    return;
  }

  setExpanded(false);
}

export function ThemeToggle() {
  const dock = getDock();
  const button = getToggleButton();
  const initialTheme = localStorage.getItem(STORAGE_KEY) || getPreferredTheme();

  updateToggleState(initialTheme);
  setExpanded(false);

  if (!dock || !button) {
    return;
  }

  dock.addEventListener("pointerenter", () => {
    if (!hasHoverPointer()) {
      return;
    }

    pendingPointerLeave = false;
    pointerInsideDock = true;
    setExpanded(true);
  });

  dock.addEventListener("pointerleave", () => {
    if (!hasHoverPointer()) {
      return;
    }

    if (isThemeTransitioning) {
      pendingPointerLeave = true;
      return;
    }

    collapseDock();
  });

  button.addEventListener("pointerdown", (event) => {
    if (!hasHoverPointer() || event.button !== 0) {
      return;
    }

    pointerInsideDock = true;
    isThemeTransitioning = true;
    setExpanded(true);
  });

  document.addEventListener("click", (event) => {
    const clickedButton = event.target.closest("[theme-toggle]");

    if (clickedButton) {
      if (hasHoverPointer()) {
        toggleTheme(event).finally(finishThemeTransition);
        return;
      }

      if (!isExpanded()) {
        setExpanded(true);
        return;
      }

      toggleTheme(event);
      return;
    }

    if (!event.target.closest("[theme-toggle-dock]")) {
      setExpanded(false);
    }
  });

  window.addEventListener("scroll", handleScroll, { passive: true });
  document.addEventListener("touchmove", handleTouchScroll, { passive: true });
}
