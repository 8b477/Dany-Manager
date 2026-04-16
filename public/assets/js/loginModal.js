document.addEventListener("DOMContentLoaded", () => {
	const modal = document.getElementById("loginModal");
	const openBtn = document.getElementById("openLoginModal");
	const closeBtn = document.getElementById("closeLoginModal");
	const backdrop = document.getElementById("loginBackdrop");
	const toggleRegisterBtn = document.getElementById("toggleRegisterModal");

	toggleRegisterBtn.addEventListener("click", () => {
		closeModal();
		document.getElementById("registerModal").classList.remove("hidden");
		document.getElementById("registerModal").classList.add("flex");
		document.body.style.overflow = "hidden";
	});

	function openModal() {
		modal.classList.remove("hidden");
		modal.classList.add("flex");
		document.body.style.overflow = "hidden";
	}

	function closeModal() {
		modal.classList.add("hidden");
		modal.classList.remove("flex");
		document.body.style.overflow = "";
	}

	if (modal?.dataset.open === "1") {
		openModal();
	}

	openBtn.addEventListener("click", openModal);
	closeBtn.addEventListener("click", closeModal);
	backdrop.addEventListener("click", closeModal);

	// Fermer avec Escape
	document.addEventListener("keydown", (e) => {
		if (e.key === "Escape" && !modal.classList.contains("hidden")) {
			closeModal();
		}
	});
});
