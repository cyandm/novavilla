function matchesAdsAreaFilter(area, filter) {
	if (filter === "all") return true;
	if (!Number.isFinite(area) || area <= 0) return false;
	if (filter === "lte40") return area <= 40;
	if (filter === "41-80") return area >= 41 && area <= 80;
	if (filter === "gt80") return area > 80;
	return true;
}

function matchesAdsRoomsFilter(rooms, filter) {
	if (filter === "all") return true;
	if (filter === "rooms1") return rooms === 1;
	if (filter === "rooms2") return rooms === 2;
	if (filter === "rooms3plus") return Number.isFinite(rooms) && rooms >= 3;
	return true;
}

function toArray(list) {
	return Array.prototype.slice.call(list || []);
}

function closestEl(el, selector) {
	if (!el) return null;
	if (el.closest) return el.closest(selector);
	while (el && el.nodeType === 1) {
		if (el.matches && el.matches(selector)) return el;
		if (el.msMatchesSelector && el.msMatchesSelector(selector)) return el;
		if (el.webkitMatchesSelector && el.webkitMatchesSelector(selector)) return el;
		el = el.parentElement || el.parentNode;
	}
	return null;
}

export function LandingAds() {
	const root = document.querySelector("[data-ads-products]");
	if (!root) return;

	const modal = document.querySelector("[data-ads-filter-modal]");
	const scope = modal ? [root, modal] : [root];
	const moreBtn = root.querySelector("[data-ads-more]");
	const moreWrap = root.querySelector("[data-ads-more-wrap]");
	const emptyEl = root.querySelector("[data-ads-empty]");
	const grid = root.querySelector("[data-ads-grid]");
	const filterDot = root.querySelector("[data-ads-filter-dot]");
	const backdrop = root.querySelector("[data-ads-card-backdrop]");
	const perPage = Math.max(1, Number(root.getAttribute("data-ads-per-page")) || 8);
	const filters = { area: "all", rooms: "all" };
	let visibleLimit = perPage;
	let backdropCloseTimer = 0;
	let drawerOpenTimer = 0;
	const BACKDROP_CLOSE_DELAY = 320;
	const DRAWER_OPEN_DELAY = 40;

	const activeClass = ["is-active", "bg-cynBorderHover", "text-black"];
	const idleClass = ["bg-white/10", "text-cynTextPrimary"];

	const queryAll = function (selector) {
		var result = [];
		scope.forEach(function (el) {
			result = result.concat(toArray(el.querySelectorAll(selector)));
		});
		return result;
	};
	const getItems = function () {
		return toArray(root.querySelectorAll("[data-ads-item]"));
	};
	const cards = function () {
		return toArray(root.querySelectorAll("[data-ads-card]"));
	};

	const getCardData = function (item) {
		const card = item.querySelector("[data-area], [data-rooms]");
		return {
			area: Number(card && card.getAttribute("data-area")),
			rooms: Number(card && card.getAttribute("data-rooms")),
		};
	};

	const itemMatches = function (item) {
		const data = getCardData(item);
		return matchesAdsAreaFilter(data.area, filters.area) && matchesAdsRoomsFilter(data.rooms, filters.rooms);
	};

	const setGroupActive = function (groupEl, activeBtn) {
		toArray(groupEl.querySelectorAll("[data-ads-filter]")).forEach(function (btn) {
			const isActive = btn === activeBtn;
			btn.classList.toggle("is-active", isActive);
			activeClass.forEach(function (cls) {
				btn.classList.toggle(cls, isActive);
			});
			idleClass.forEach(function (cls) {
				btn.classList.toggle(cls, !isActive);
			});
			btn.classList.toggle("bg-cynBorderHover", isActive);
			btn.classList.toggle("text-black", isActive);
			btn.classList.toggle("bg-white/10", !isActive);
			btn.classList.toggle("text-cynTextPrimary", !isActive);
		});
	};

	const syncGroup = function (group, value) {
		filters[group] = value;
		queryAll('[data-ads-filter-group="' + group + '"]').forEach(function (groupEl) {
			var btn = null;
			toArray(groupEl.querySelectorAll("[data-ads-filter]")).forEach(function (item) {
				if (!btn && (item.getAttribute("data-ads-filter") || "all") === value) btn = item;
			});
			if (btn) setGroupActive(groupEl, btn);
		});
		if (filterDot) filterDot.classList.toggle("hidden", filters.rooms === "all");
	};

	const syncExpandHeight = function (card, open) {
		const drawer = card.querySelector("[data-ads-card-drawer]");
		const expand = card.querySelector("[data-ads-card-expand]");
		const frame = card.querySelector("[data-ads-card-frame]");
		const frost = card.querySelector("[data-ads-card-frost]");
		if (!drawer || !expand) {
			if (frame) frame.style.bottom = "0px";
			if (frost) frost.style.bottom = "0px";
			return;
		}

		if (open) {
			var openHeight = expand.scrollHeight;
			drawer.style.height = openHeight + "px";
			if (frame) frame.style.bottom = "-" + openHeight + "px";
			if (frost) frost.style.bottom = "-" + openHeight + "px";
			return;
		}

		var current = drawer.getBoundingClientRect().height;
		if (current > 0) drawer.style.height = current + "px";
		drawer.offsetHeight;
		drawer.style.height = "0px";
		if (frame) {
			frame.style.bottom = "-" + current + "px";
			frame.offsetHeight;
			frame.style.bottom = "0px";
		}
		if (frost) {
			frost.style.bottom = "-" + current + "px";
			frost.offsetHeight;
			frost.style.bottom = "0px";
		}
	};

	const clearElevated = function (card) {
		card.style.zIndex = "";
		card.removeAttribute("data-elevated");
		const wrap = closestEl(card, "[data-ads-item]");
		if (wrap) wrap.style.zIndex = "";
	};

	const keepElevated = function (card) {
		card.style.zIndex = "60";
		card.setAttribute("data-elevated", "true");
		const wrap = closestEl(card, "[data-ads-item]");
		if (wrap) wrap.style.zIndex = "60";
	};

	const closeAllCards = function () {
		window.clearTimeout(drawerOpenTimer);
		const closing = cards().filter(function (card) {
			return card.getAttribute("data-open") === "true" || card.getAttribute("data-elevated") === "true";
		});
		closing.forEach(function (card) {
			card.setAttribute("data-open", "false");
			syncExpandHeight(card, false);
			keepElevated(card);
		});
		window.clearTimeout(backdropCloseTimer);
		if (!closing.length) {
			if (backdrop) backdrop.setAttribute("data-active", "false");
			return;
		}
		backdropCloseTimer = window.setTimeout(function () {
			closing.forEach(clearElevated);
			if (backdrop) backdrop.setAttribute("data-active", "false");
		}, BACKDROP_CLOSE_DELAY);
	};

	const openCard = function (card) {
		window.clearTimeout(backdropCloseTimer);
		window.clearTimeout(drawerOpenTimer);

		cards().forEach(function (item) {
			if (item === card) return;
			item.setAttribute("data-open", "false");
			syncExpandHeight(item, false);
			clearElevated(item);
		});

		keepElevated(card);
		if (backdrop) backdrop.setAttribute("data-active", "true");

		drawerOpenTimer = window.setTimeout(function () {
			card.setAttribute("data-open", "true");
			var drawer = card.querySelector("[data-ads-card-drawer]");
			var frame = card.querySelector("[data-ads-card-frame]");
			var frost = card.querySelector("[data-ads-card-frost]");
			if (drawer) {
				drawer.style.height = "0px";
				if (frame) frame.style.bottom = "0px";
				if (frost) frost.style.bottom = "0px";
				drawer.offsetHeight;
			}
			syncExpandHeight(card, true);
		}, DRAWER_OPEN_DELAY);
	};

	const applyItemVisibility = function () {
		const items = getItems();
		const matching = items.filter(itemMatches);
		const hasResults = matching.length > 0;

		if (emptyEl) {
			if (hasResults) {
				emptyEl.classList.add("hidden", "opacity-0");
			} else {
				emptyEl.classList.remove("hidden");
				emptyEl.classList.add("opacity-0");
				window.requestAnimationFrame(function () {
					emptyEl.classList.remove("opacity-0");
				});
			}
		}
		if (grid) grid.classList.toggle("hidden", !hasResults);

		var shown = 0;
		items.forEach(function (item) {
			if (!itemMatches(item)) {
				item.classList.add("hidden");
				item.classList.remove("opacity-100", "translate-y-0");
				item.classList.add("opacity-0", "translate-y-3");
				return;
			}

			shown += 1;
			if (shown > visibleLimit) {
				item.classList.add("hidden");
				item.classList.remove("opacity-100", "translate-y-0");
				item.classList.add("opacity-0", "translate-y-3");
				return;
			}

			item.classList.remove("hidden", "opacity-0", "translate-y-3");
			item.classList.add("opacity-100", "translate-y-0");
		});

		if (moreWrap) moreWrap.classList.toggle("hidden", matching.length <= visibleLimit);
	};

	const updateView = function (mode) {
		const animateFilter = mode === "filter";
		const animateMore = mode === true || mode === "more";

		if (animateFilter && grid && !grid.classList.contains("hidden")) {
			grid.classList.add("opacity-0", "translate-y-2");
			window.setTimeout(function () {
				applyItemVisibility();
				window.requestAnimationFrame(function () {
					grid.classList.remove("opacity-0", "translate-y-2");
				});
			}, 180);
			return;
		}

		if (animateMore) {
			const items = getItems();
			var shown = 0;
			items.forEach(function (item) {
				if (!itemMatches(item)) {
					item.classList.add("hidden");
					item.classList.remove("opacity-100", "translate-y-0");
					item.classList.add("opacity-0", "translate-y-3");
					return;
				}
				shown += 1;
				if (shown > visibleLimit) {
					item.classList.add("hidden");
					return;
				}
				const wasHidden = item.classList.contains("hidden");
				item.classList.remove("hidden");
				if (wasHidden) {
					item.classList.add("opacity-0", "translate-y-3");
					item.classList.remove("opacity-100", "translate-y-0");
					const delay = Math.max(0, shown - (visibleLimit - perPage) - 1) * 50;
					window.setTimeout(function () {
						window.requestAnimationFrame(function () {
							item.classList.remove("opacity-0", "translate-y-3");
							item.classList.add("opacity-100", "translate-y-0");
						});
					}, delay);
				} else {
					item.classList.remove("opacity-0", "translate-y-3");
					item.classList.add("opacity-100", "translate-y-0");
				}
			});
			if (emptyEl) emptyEl.classList.add("hidden", "opacity-0");
			if (grid) grid.classList.remove("hidden");
			if (moreWrap) moreWrap.classList.toggle("hidden", items.filter(itemMatches).length <= visibleLimit);
			return;
		}

		applyItemVisibility();
	};

	queryAll("[data-ads-filter-group]").forEach(function (groupEl) {
		const group = groupEl.getAttribute("data-ads-filter-group");
		toArray(groupEl.querySelectorAll("[data-ads-filter]")).forEach(function (btn) {
			btn.addEventListener("click", function () {
				visibleLimit = perPage;
				syncGroup(group, btn.getAttribute("data-ads-filter") || "all");
				closeAllCards();
				updateView("filter");
			});
		});
	});

	if (moreBtn) {
		moreBtn.addEventListener("click", function () {
			visibleLimit += perPage;
			updateView(true);
		});
	}

	root.addEventListener("click", function (event) {
		const toggle = closestEl(event.target, "[data-ads-card-toggle]");
		if (!toggle || !root.contains(toggle)) return;
		event.preventDefault();
		event.stopPropagation();
		const card = closestEl(toggle, "[data-ads-card]");
		if (!card || !card.querySelector("[data-ads-card-expand]")) return;
		if (card.getAttribute("data-open") === "true" || card.getAttribute("data-elevated") === "true") closeAllCards();
		else openCard(card);
	});

	if (backdrop) {
		backdrop.addEventListener("click", closeAllCards);
		backdrop.addEventListener("wheel", function (event) {
			window.scrollBy(0, event.deltaY);
		}, { passive: true });
		var touchY = 0;
		backdrop.addEventListener("touchstart", function (event) {
			if (event.touches && event.touches[0]) touchY = event.touches[0].clientY;
		}, { passive: true });
		backdrop.addEventListener("touchmove", function (event) {
			if (!event.touches || !event.touches[0]) return;
			var y = event.touches[0].clientY;
			window.scrollBy(0, touchY - y);
			touchY = y;
		}, { passive: true });
	}

	document.addEventListener("keydown", function (event) {
		const key = event.key || event.code;
		const code = event.keyCode || event.which;
		if (key === "Escape" || key === "Esc" || code === 27) closeAllCards();
	});

	updateView();
}
