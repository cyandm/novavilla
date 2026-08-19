export function ProjectArchive() {
	const form = document.getElementById("project-filter-form");
	if (!form) return;
	const isDesktop = () => window.matchMedia("(min-width: 768px)").matches;
	form.querySelectorAll('input[type="radio"]').forEach((radio) => {
		radio.addEventListener("change", () => {
			if (isDesktop()) form.submit();
		});
	});
	const city = form.querySelector('input[name="city"]');
	city?.addEventListener("change", () => {
		if (isDesktop()) form.submit();
	});
}
