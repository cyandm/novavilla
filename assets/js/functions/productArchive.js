function formatProductPriceLabel(value) {
  const amount = Number(value);
  if (!Number.isFinite(amount)) return "";
  if (amount >= 1e9) {
    return `${new Intl.NumberFormat("fa-IR").format(Math.round(amount / 1e9))} میلیارد`;
  }
  if (amount >= 1e6) {
    return `${new Intl.NumberFormat("fa-IR").format(Math.round(amount / 1e6))} میلیون`;
  }
  return new Intl.NumberFormat("fa-IR").format(amount);
}

function formatProductPriceInput(value) {
  const amount = Number(value);
  if (!Number.isFinite(amount)) return "";
  return `${new Intl.NumberFormat("fa-IR").format(amount)} تومان`;
}

function parseProductPriceInput(value) {
  const persianDigits = "۰۱۲۳۴۵۶۷۸۹";
  const normalized = String(value)
    .replace(/[۰-۹]/g, (ch) => String(persianDigits.indexOf(ch)))
    .replace(/[^\d]/g, "");
  return normalized ? Number(normalized) : NaN;
}

function initProductPriceRange() {
  const wrap = document.querySelector("[data-product-price-range]");
  if (!wrap) return;

  const form = document.getElementById("product-filter-form");
  const minInput = wrap.querySelector("[data-price-min-input]");
  const maxInput = wrap.querySelector("[data-price-max-input]");
  const minRange = wrap.querySelector("[data-price-min-range]");
  const maxRange = wrap.querySelector("[data-price-max-range]");
  const fill = wrap.querySelector("[data-price-range-fill]");
  const minLabel = wrap.querySelector("[data-price-min-label]");
  const maxLabel = wrap.querySelector("[data-price-max-label]");
  const floor = Number(wrap.dataset.priceFloor);
  const ceiling = Number(wrap.dataset.priceCeiling);

  if (!minInput || !maxInput || !minRange || !maxRange || !fill) return;

  const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

  const updateFill = () => {
    const min = Number(minRange.value);
    const max = Number(maxRange.value);
    const span = ceiling - floor;
    const left = span > 0 ? ((min - floor) / span) * 100 : 0;
    const right = span > 0 ? 100 - ((max - floor) / span) * 100 : 0;
    fill.style.left = `${left}%`;
    fill.style.right = `${right}%`;
    if (minLabel) minLabel.textContent = formatProductPriceLabel(min);
    if (maxLabel) maxLabel.textContent = formatProductPriceLabel(max);
  };

  const syncInputsFromRange = (force = false) => {
    const min = Number(minRange.value);
    const max = Number(maxRange.value);
    if (!force && min <= floor && max >= ceiling) {
      minInput.value = "";
      maxInput.value = "";
      return;
    }
    minInput.value = formatProductPriceInput(min);
    maxInput.value = formatProductPriceInput(max);
  };

  const isDesktop = () => window.matchMedia("(min-width: 768px)").matches;

  const applyPriceToInputs = () => {
    if (!form) return;
    const min = Number(minRange.value);
    const max = Number(maxRange.value);
    if (min <= floor && max >= ceiling) {
      minInput.removeAttribute("name");
      maxInput.removeAttribute("name");
      minInput.value = "";
      maxInput.value = "";
      return;
    }
    minInput.setAttribute("name", "price_min");
    maxInput.setAttribute("name", "price_max");
    minInput.value = String(min);
    maxInput.value = String(max);
  };

  const submitForm = () => {
    if (!form || !isDesktop()) return;
    applyPriceToInputs();
    form.submit();
  };

  form?.addEventListener("submit", applyPriceToInputs);

  minRange.addEventListener("input", () => {
    if (Number(minRange.value) > Number(maxRange.value)) {
      minRange.value = maxRange.value;
    }
    minRange.style.zIndex = "2";
    maxRange.style.zIndex = "1";
    syncInputsFromRange(true);
    updateFill();
  });

  maxRange.addEventListener("input", () => {
    if (Number(maxRange.value) < Number(minRange.value)) {
      maxRange.value = minRange.value;
    }
    maxRange.style.zIndex = "2";
    minRange.style.zIndex = "1";
    syncInputsFromRange(true);
    updateFill();
  });

  minRange.addEventListener("change", submitForm);
  maxRange.addEventListener("change", submitForm);

  minInput.addEventListener("change", () => {
    const value = clamp(
      parseProductPriceInput(minInput.value) || floor,
      floor,
      Number(maxRange.value),
    );
    minRange.value = String(value);
    minInput.value = formatProductPriceInput(value);
    updateFill();
    submitForm();
  });

  maxInput.addEventListener("change", () => {
    const value = clamp(
      parseProductPriceInput(maxInput.value) || ceiling,
      Number(minRange.value),
      ceiling,
    );
    maxRange.value = String(value);
    maxInput.value = formatProductPriceInput(value);
    updateFill();
    submitForm();
  });

  updateFill();
}

export function ProductArchive() {
  const form = document.getElementById("product-filter-form");
  const isDesktop = () => window.matchMedia("(min-width: 768px)").matches;

  if (form) {
    form.querySelectorAll('input[type="radio"]').forEach((radio) => {
      radio.addEventListener("change", () => {
        if (isDesktop()) form.submit();
      });
    });
  }

  initProductPriceRange();
}
