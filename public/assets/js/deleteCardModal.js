document.addEventListener("DOMContentLoaded", () => {
	const modal = document.getElementById("deleteCardModal");
	const backdrop = document.getElementById("deleteCardBackdrop");
	const cancelBtn = document.getElementById("deleteCardCancel");
	const cardNameEl = document.getElementById("deleteCardName");
	const cardIdInput = document.getElementById("deleteCardId");

	function openModal(cardId, cardName) {
		cardIdInput.value = cardId;
		cardNameEl.textContent = cardName;
		modal.classList.remove("hidden");
		modal.classList.add("flex");
		document.body.style.overflow = "hidden";
	}

	function closeModal() {
		modal.classList.add("hidden");
		modal.classList.remove("flex");
		document.body.style.overflow = "";
		cardIdInput.value = "";
	}

	// Délégation sur tous les boutons supprimer
	document.addEventListener("click", (e) => {
		const btn = e.target.closest("[data-delete-id]");
		if (btn) openModal(btn.dataset.deleteId, btn.dataset.deleteName);
	});

	cancelBtn.addEventListener("click", closeModal);
	backdrop.addEventListener("click", closeModal);
	document.addEventListener("keydown", (e) => {
		if (e.key === "Escape") closeModal();
	});
});
