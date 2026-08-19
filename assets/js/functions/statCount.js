const DURATION = 3200;
const START_DELAY = 1000;
const formatter = new Intl.NumberFormat("fa-IR");

function easeOutSine(t) {
	return Math.sin((t * Math.PI) / 2);
}

function animateStat(el) {
	const target = Number(el.dataset.statCount);
	if (!Number.isFinite(target) || el.dataset.statCounted === "true") return;
	el.dataset.statCounted = "true";
	const start = performance.now();
	function tick(now) {
		const progress = Math.min((now - start) / DURATION, 1);
		const value = Math.round(easeOutSine(progress) * target);
		el.textContent = `+${formatter.format(value)}`;
		if (progress < 1) requestAnimationFrame(tick);
	}
	requestAnimationFrame(tick);
}

function onPageLoad(callback) {
	if (document.readyState === "complete") {
		callback();
		return;
	}
	window.addEventListener("load", callback, { once: true });
}

export function StatCount() {
	const stats = document.querySelectorAll("[data-stat-count]");
	if (!stats.length) return;
	onPageLoad(() => {
		setTimeout(() => stats.forEach(animateStat), START_DELAY);
	});
}
