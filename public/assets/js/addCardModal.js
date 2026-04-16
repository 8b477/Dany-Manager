document.addEventListener("DOMContentLoaded", () => {
	const modal = document.getElementById("addCardModal");
	const closeBtn = document.getElementById("closeAddCardModal");
	const cancelBtn = document.getElementById("cancelAddCardModal");
	const backdrop = document.getElementById("addCardBackdrop");
	const categorySelect = document.getElementById("category_id");

	function openModal(categoryId = null) {
		if (categoryId && categorySelect) {
			categorySelect.value = categoryId;
		}
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

	document.addEventListener("click", (e) => {
		const btn = e.target.closest("[data-open-add-card]");
		if (btn) openModal(btn.dataset.categoryId ?? null);
	});

	closeBtn?.addEventListener("click", closeModal);
	cancelBtn?.addEventListener("click", closeModal);
	backdrop?.addEventListener("click", closeModal);

	document.addEventListener("keydown", (e) => {
		if (e.key === "Escape" && !modal.classList.contains("hidden")) {
			closeModal();
		}
	});
});
