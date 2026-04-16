document.addEventListener("DOMContentLoaded", () => {
	const modal = document.getElementById("editCardModal");
	const backdrop = document.getElementById("editCardBackdrop");
	const closeBtn = document.getElementById("closeEditCardModal");
	const cancelBtn = document.getElementById("cancelEditCardModal");

	const cardIdInput = document.getElementById("editCardId");
	const userIdInput = document.getElementById("editUserId");
	const positionInput = document.getElementById("editCardPosition");
	const nameInput = document.getElementById("editName");
	const descriptionInput = document.getElementById("editDescription");
	const categorySelect = document.getElementById("editCategoryId");

	function openModal(data) {
		cardIdInput.value = data.editId;
		positionInput.value = data.editPosition;
		nameInput.value = data.editName;
		descriptionInput.value = data.editDescription;

		// Sélectionner la bonne catégorie dans le <select>
		categorySelect.value = data.editCategoryId;

		modal.classList.remove("hidden");
		modal.classList.add("flex");
		document.body.style.overflow = "hidden";
	}

	function closeModal() {
		modal.classList.add("hidden");
		modal.classList.remove("flex");
		document.body.style.overflow = "";
	}

	// Délégation — écoute tous les boutons avec data-edit-id
	document.addEventListener("click", (e) => {
		const btn = e.target.closest("[data-edit-id]");
		if (!btn) return;
		e.stopPropagation();
		openModal(btn.dataset);
	});

	closeBtn.addEventListener("click", closeModal);
	cancelBtn.addEventListener("click", closeModal);
	backdrop.addEventListener("click", closeModal);
	document.addEventListener("keydown", (e) => {
		if (e.key === "Escape") closeModal();
	});
});
