document.addEventListener("DOMContentLoaded", () => {
	const columns = document.querySelectorAll("[data-drop-zone]");

	columns.forEach((col) => {
		Sortable.create(col, {
			group: "kanban",
			animation: 150,
			ghostClass: "sortable-ghost",
			chosenClass: "sortable-chosen",

			onStart() {
				columns.forEach((c) => c.classList.add("droppable"));
			},

			onEnd(evt) {
				columns.forEach((c) => c.classList.remove("droppable", "droppable-active"));

				const cardId = evt.item.dataset.cardId;
				const newCategoryId = evt.to.dataset.dropZone;
				const oldCategoryId = evt.from.dataset.dropZone;

				if (newCategoryId === oldCategoryId) return;

				evt.item.dataset.categoryId = newCategoryId;
				// Met à jour le bouton éditer pour que le modal reflète la nouvelle catégorie
				const editBtn = evt.item.querySelector("[data-edit-category-id]");
				if (editBtn) editBtn.setAttribute("data-edit-category-id", newCategoryId);

				// Cache le message vide + bouton de la colonne destination
				evt.to.querySelector("[data-empty-message]")?.classList.add("hidden");
				evt.to.querySelector("[data-open-add-card]")?.classList.add("hidden");

				// Ré-affiche l'état vide si la colonne source n'a plus de cartes
				const remaining = evt.from.querySelectorAll("[data-card-id]").length;
				if (remaining === 0) {
					evt.from.querySelector("[data-empty-message]")?.classList.remove("hidden");
					evt.from.querySelector("[data-open-add-card]")?.classList.remove("hidden");
				}

				// Met à jour les compteurs des deux colonnes
				const fromCount = evt.from.closest("section")?.querySelector("[data-card-count]");
				const toCount = evt.to.closest("section")?.querySelector("[data-card-count]");
				if (fromCount) fromCount.textContent = remaining;
				if (toCount) toCount.textContent = evt.to.querySelectorAll("[data-card-id]").length;

				fetch("/board/move", {
					method: "POST",
					headers: { "Content-Type": "application/x-www-form-urlencoded" },
					body: `card_id=${encodeURIComponent(cardId)}&category_id=${encodeURIComponent(newCategoryId)}`,
				})
					.then((r) => r.json())
					.then((data) => {
						if (!data.success) {
							console.error("Erreur déplacement :", data.error);
							evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex] ?? null);
							evt.item.dataset.categoryId = oldCategoryId;
						}
					})
					.catch((err) => console.error("Erreur réseau :", err));
			},

			onMove(evt) {
				columns.forEach((c) => c.classList.remove("droppable-active"));
				evt.to.classList.add("droppable-active");
			},
		});
	});
});
