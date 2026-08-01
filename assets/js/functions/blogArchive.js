import { initPrimaryButton, removePrimaryButton } from "./primaryButton";

const SWITCH_DURATION = 380;

const updateBlogTabUrl = (name) => {
  const url = new URL(window.location.href);
  url.searchParams.set("blog_tab", name);
  url.searchParams.delete("blog_paged");
  window.history.replaceState(null, "", url);
};

export function BlogArchive() {
  const section = document.querySelector(".blog-archive");
  if (!section) return;

  const tabs = section.querySelectorAll("[data-tab]");
  const contentsWrapper = section.querySelector(".blog-archive-contents");
  if (!tabs.length || !contentsWrapper) return;

  const panels = [...contentsWrapper.querySelectorAll("[data-content]")];
  let isAnimating = false;

  const getContent = (name) => panels.find((panel) => panel.dataset.content === name) || null;

  const setContainerHeight = (panel) => {
    if (!panel) return;
    contentsWrapper.style.minHeight = `${panel.scrollHeight}px`;
  };

  function updateTabButtons(name) {
    tabs.forEach((tab) => {
      const isActive = tab.dataset.tab === name;
      tab.classList.toggle("is-active", isActive);
      tab.setAttribute("aria-selected", isActive ? "true" : "false");

      if (isActive) initPrimaryButton(tab);
      else removePrimaryButton(tab);
    });
  }

  function switchTab(name, { updateUrl = true } = {}) {
    const current = contentsWrapper.querySelector(".blog-archive-content.is-active");
    const next = getContent(name);

    if (!next || current === next || isAnimating) return;

    isAnimating = true;
    updateTabButtons(name);

    const finishSwitch = () => {
      panels.forEach((panel) => {
        panel.classList.remove("is-leaving", "is-entering");

        if (panel !== next) {
          panel.classList.remove("is-active");
          panel.setAttribute("aria-hidden", "true");
        }
      });

      next.classList.add("is-active");
      next.setAttribute("aria-hidden", "false");
      setContainerHeight(next);
      if (updateUrl) updateBlogTabUrl(name);
      isAnimating = false;
    };

    if (!current) {
      finishSwitch();
      return;
    }

    current.classList.add("is-leaving");
    current.classList.remove("is-active");
    next.classList.add("is-entering");
    next.setAttribute("aria-hidden", "false");
    setContainerHeight(next);

    requestAnimationFrame(() => {
      next.classList.add("is-active");
    });

    window.setTimeout(() => {
      current.classList.remove("is-leaving");
      next.classList.remove("is-entering");
      finishSwitch();
    }, SWITCH_DURATION);
  }

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const name = tab.dataset.tab;
      if (!name || tab.getAttribute("aria-selected") === "true") return;
      switchTab(name);
    });
  });

  const urlTab = new URL(window.location.href).searchParams.get("blog_tab");
  const tabByUrl = urlTab ? [...tabs].find((tab) => tab.dataset.tab === urlTab) : null;
  const initialTab =
    (tabByUrl && getContent(urlTab) && tabByUrl) ||
    [...tabs].find((tab) => tab.classList.contains("is-active")) ||
    [...tabs].find((tab) => tab.dataset.tab === "all") ||
    tabs[0];

  if (initialTab) {
    const name = initialTab.dataset.tab;
    updateTabButtons(name);
    panels.forEach((panel) => {
      const isActive = panel.dataset.content === name;
      panel.classList.toggle("is-active", isActive);
      panel.setAttribute("aria-hidden", isActive ? "false" : "true");
    });
  }

  setContainerHeight(contentsWrapper.querySelector(".blog-archive-content.is-active"));

  window.addEventListener("resize", () => {
    setContainerHeight(contentsWrapper.querySelector(".blog-archive-content.is-active"));
  });
}
