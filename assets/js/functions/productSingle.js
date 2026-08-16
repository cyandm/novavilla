export function ProductSingle() {
  initFeatures();
}

function initFeatures() {
  const root = document.querySelector("[data-product-features]");
  if (!root) return;

  const cards = [...root.querySelectorAll("button[data-feature-id]:not([data-feature-chip])")];
  const chips = [...root.querySelectorAll("[data-feature-chip]")];
  const countEl = root.querySelector("[data-feature-count]");
  const inquiryBtn = root.querySelector("[data-feature-inquiry]");
  const productTitle = root.dataset.productTitle || "";
  const inquiryBase = inquiryBtn?.href || "";
  const selected = new Set();

  const sync = () => {
    cards.forEach((card) => {
      const on = selected.has(card.dataset.featureId);
      card.classList.toggle("is-selected", on);
      card.setAttribute("aria-pressed", on ? "true" : "false");
    });

    chips.forEach((chip) => {
      chip.classList.toggle("hidden", !selected.has(chip.dataset.featureId));
    });

    if (countEl) {
      countEl.textContent = `(${selected.size} مورد)`;
    }

    if (inquiryBtn && inquiryBase) {
      const url = new URL(inquiryBase, window.location.origin);
      if (productTitle) url.searchParams.set("product", productTitle);
      const titles = cards
        .filter((card) => selected.has(card.dataset.featureId))
        .map((card) => card.dataset.featureTitle)
        .filter(Boolean);
      if (titles.length) url.searchParams.set("features", titles.join(", "));
      else url.searchParams.delete("features");
      inquiryBtn.href = url.toString();
    }
  };

  const toggle = (id) => {
    if (!id) return;
    if (selected.has(id)) selected.delete(id);
    else selected.add(id);
    sync();
  };

  cards.forEach((card) => {
    card.addEventListener("click", () => toggle(card.dataset.featureId));
  });

  chips.forEach((chip) => {
    chip.addEventListener("click", (event) => {
      event.preventDefault();
      toggle(chip.dataset.featureId);
    });
  });

  sync();
}
