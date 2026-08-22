export function ProductSingle() {
  initFeatures();
  initInstallment();
}

function formatToman(value) {
  return `${Number(value || 0).toLocaleString("fa-IR")} تومان`;
}

function formatPercent(value) {
  const n = Number(value || 0);
  const trimmed = String(n).replace(/\.0+$/, "").replace(/(\.\d*?)0+$/, "$1");
  return `${trimmed} %`;
}

function initInstallment() {
  const root = document.querySelector("[data-product-installment]");
  if (!root) return;

  const price = Number(root.dataset.price || 0);
  const rate = Number(root.dataset.rate || 0);
  const prepayBtns = [...root.querySelectorAll("[data-installment-prepay]")];
  const periodBtns = [...root.querySelectorAll("[data-installment-period]")];
  const totalEl = root.querySelector("[data-installment-total-price]");
  const prepayEl = root.querySelector("[data-installment-prepay-percent]");
  const remainingEl = root.querySelector("[data-installment-remaining]");
  const monthsEl = root.querySelector("[data-installment-months]");
  const sumEl = root.querySelector("[data-installment-sum]");
  const monthlyEl = root.querySelector("[data-installment-monthly]");

  let percent =
    Number(
      prepayBtns.find((btn) => btn.classList.contains("is-selected"))?.dataset
        .percent,
    ) || Number(prepayBtns[0]?.dataset.percent || 0);
  let months =
    Number(
      periodBtns.find((btn) => btn.classList.contains("is-selected"))?.dataset
        .months,
    ) || Number(periodBtns[0]?.dataset.months || 0);

  const setSelected = (btns, active) => {
    btns.forEach((btn) => {
      const on = btn === active;
      btn.classList.toggle("is-selected", on);
      btn.setAttribute("aria-pressed", on ? "true" : "false");
    });
  };

  const setTomanAmount = (el, value) => {
    if (!el) return;
    const amountEl = el.querySelector("[data-installment-amount]");
    if (amountEl) amountEl.textContent = Number(value || 0).toLocaleString("fa-IR");
  };

  const sync = () => {
    if (price <= 0 || months <= 0) return;

    const remaining = price * (1 - percent / 100);
    const sum = remaining * (1 + (months * rate) / 100);
    const monthly = sum / months;

    setTomanAmount(totalEl, Math.round(price));
    if (prepayEl) prepayEl.textContent = formatPercent(percent);
    setTomanAmount(remainingEl, Math.round(remaining));
    if (monthsEl) monthsEl.textContent = `${months.toLocaleString("fa-IR")} ماه`;
    setTomanAmount(sumEl, Math.round(sum));
    setTomanAmount(monthlyEl, Math.round(monthly));
  };

  prepayBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      percent = Number(btn.dataset.percent || 0);
      setSelected(prepayBtns, btn);
      sync();
    });
  });

  periodBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      months = Number(btn.dataset.months || 0);
      setSelected(periodBtns, btn);
      sync();
    });
  });

  sync();
}

function initFeatures() {
  const root = document.querySelector("[data-product-features]");
  const modal = document.querySelector(
    '[modal][data-modal-name="product-price-inquiry"]',
  );
  if (!root) return;

  const scopes = [root, modal].filter(Boolean);
  const qAll = (sel) => [
    ...new Set(scopes.flatMap((el) => [...el.querySelectorAll(sel)])),
  ];
  const qOne = (sel) =>
    scopes.reduce((found, el) => found || el.querySelector(sel), null);

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
