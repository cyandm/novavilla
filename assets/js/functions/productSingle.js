export function ProductSingle() {
  initFeatures();
}

function formatToman(value) {
  return `${Number(value || 0).toLocaleString("fa-IR")} تومان`;
}

function initFeatures() {
  const root = document.querySelector("[data-product-features]");
  const modal = document.querySelector('[modal][data-modal-name="product-price-inquiry"]');
  if (!root) return;

  const scopes = [root, modal].filter(Boolean);
  const qAll = (sel) => [...new Set(scopes.flatMap((el) => [...el.querySelectorAll(sel)]))];
  const qOne = (sel) => scopes.reduce((found, el) => found || el.querySelector(sel), null);

  const cards = qAll("[data-feature-card]");
  const chips = qAll("[data-feature-chip]");
  const countEl = qOne("[data-feature-count]");
  const inquiryBtn = qOne("[data-feature-inquiry]");
  const baseEl = qOne("[data-price-base]");
  const featuresEl = qOne("[data-price-features]");
  const totalEl = qOne("[data-price-total]");
  const productTitle = root.dataset.productTitle || "";
  const inquiryBase = inquiryBtn?.getAttribute("href") || "";
  const basePrice = Number(root.dataset.basePrice || 0);
  const selected = new Set();

  const sync = () => {
    let featuresPrice = 0;

    cards.forEach((card) => {
      const on = selected.has(card.dataset.featureId);
      card.classList.toggle("is-selected", on);
      card.setAttribute("aria-pressed", on ? "true" : "false");
      if (on) featuresPrice += Number(card.dataset.featurePrice || 0);
    });

    chips.forEach((chip) => {
      const on = selected.has(chip.dataset.featureId);
      chip.classList.toggle("hidden", !on);
      chip.classList.toggle("inline-flex", on);
    });

    if (countEl) countEl.textContent = `(${selected.size} مورد)`;
    if (baseEl) baseEl.textContent = formatToman(basePrice);
    if (featuresEl) featuresEl.textContent = formatToman(featuresPrice);
    if (totalEl) totalEl.textContent = formatToman(basePrice + featuresPrice);

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
