document.addEventListener("DOMContentLoaded", () => {
	const modal = document.getElementById("registerModal");
	const openBtn = document.getElementById("openRegisterModal");
	const closeBtn = document.getElementById("closeRegisterModal");
	const backdrop = document.getElementById("registerBackdrop");
	const toggleLoginBtn = document.getElementById("toggleLoginModal");

	toggleLoginBtn.addEventListener("click", () => {
		closeModal();
		document.getElementById("loginModal").classList.remove("hidden");
		document.getElementById("loginModal").classList.add("flex");
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
