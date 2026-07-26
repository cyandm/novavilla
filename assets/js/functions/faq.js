export function FaqTabs() {
  const handlers = document.querySelectorAll(".faq-handler");
  const panels = document.querySelectorAll(".faq-panel");

  if (!panels || !handlers) return;

  function activateTab(element) {
    if (!element) return;

    element.classList.replace("bg-cynBgItem", "bg-cynTextPrimaryHover");
    element.classList.replace("text-cynTextPrimary", "text-cynBlack");
  }

  function deActivateTab(element) {
    if (!element) return;

    element.classList.replace("bg-cynTextPrimaryHover", "bg-cynBgItem");
    element.classList.replace("text-cynBlack", "text-cynTextPrimary");
  }

  function activatePanel(element) {
    if (!element) return;

    element.classList.replace("grid-rows-[0fr]", "grid-rows-[1fr]");
  }

  function deActivatePanel(element) {
    if (!element) return;

    element.classList.replace("grid-rows-[1fr]", "grid-rows-[0fr]");
  }

  activateTab(handlers[0]);
  activatePanel(panels[0]);

  handlers.forEach((handler) => {
    handler.addEventListener("click", () => {
      handlers.forEach((innerHandler) => deActivateTab(innerHandler));
      activateTab(handler);

      panels.forEach((panel) => {
        const isRelatedPanel =
          panel.getAttribute("controlled-by") === handler.id;

        if (isRelatedPanel) {
          activatePanel(panel);
        } else {
          deActivatePanel(panel);
        }
      });
    });
  });
}

export function FaqCard() {
  const faqCards = document.querySelectorAll(".faq-card");
  if (!faqCards) return;

  function activateFaq(faq, expert) {
    expert.classList.replace("grid-rows-[0fr]", "grid-rows-[1fr]");
    const svg = faq.querySelector("svg");
    const q = faq.querySelector(".faq-q");
    const icon = faq.querySelector(".icon");
    q?.classList.remove("text-cynTextPrimary/80", "md:text-cynTextPrimary/60");
    q?.classList.add("text-cynTextPrimary");
    icon?.classList.replace("text-cynTextPrimary", "text-cynTextPrimaryHover");
    if (svg) svg.classList.add("rotate-45");
  }

  function deActivateFaq(faq, expert) {
    expert.classList.replace("grid-rows-[1fr]", "grid-rows-[0fr]");
    const svg = faq.querySelector("svg");
    const q = faq.querySelector(".faq-q");
    const icon = faq.querySelector(".icon");
    q?.classList.remove("text-cynTextPrimary");
    q?.classList.add("text-cynTextPrimary/80", "md:text-cynTextPrimary/60");
    icon?.classList.replace("text-cynTextPrimaryHover", "text-cynTextPrimary");
    if (svg) svg.classList.remove("rotate-45");
  }

  faqCards.forEach((faq) => {
    const faqToggle = faq.querySelector(".faq-toggle");
    const expert = faq.querySelector(".faq-expert");
    if (!expert) return;

    faqToggle?.addEventListener("click", () => {
      const faqIsActive = expert.classList.contains("grid-rows-[1fr]");

      if (faqIsActive) {
        deActivateFaq(faq, expert);
      } else {
        activateFaq(faq, expert);
      }
    });
  });
}
