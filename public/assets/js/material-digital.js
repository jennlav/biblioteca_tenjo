// =========================================================
// material-digital.js
// Filtros, búsqueda y navegación de Material Digital
// =========================================================

document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll(".material-tab");
  const cards = Array.from(document.querySelectorAll(".resource-card"));
  const searchForm = document.getElementById("materialSearchForm");
  const searchInput = document.getElementById("materialSearchInput");
  const emptyState = document.getElementById("materialEmpty");
  const jumpButtons = document.querySelectorAll(".material-jump");

  let activeFilter = "todos";

  const normalizeText = (text) =>
    text
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .trim();

  const applyFilters = () => {
    const term = normalizeText(searchInput?.value ?? "");
    let visibleCount = 0;

    cards.forEach((card) => {
      const audience = card.dataset.audience;
      const searchableText = normalizeText(
        `${card.textContent} ${card.dataset.search ?? ""}`
      );

      const matchesAudience =
        activeFilter === "todos" || audience === activeFilter;

      const matchesSearch =
        !term || searchableText.includes(term);

      const visible = matchesAudience && matchesSearch;
      card.hidden = !visible;

      if (visible) {
        visibleCount += 1;
      }
    });

    document.querySelectorAll(".audience-section").forEach((section) => {
      const audience = section.id;

      const hasVisibleCards = cards.some(
        (card) =>
          card.dataset.audience === audience &&
          !card.hidden
      );

      section.hidden = !hasVisibleCards;
    });

    if (emptyState) {
      emptyState.hidden = visibleCount > 0;
    }
  };

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      activeFilter = tab.dataset.filter ?? "todos";

      tabs.forEach((item) => {
        const isActive = item === tab;

        item.classList.toggle("active", isActive);
        item.setAttribute("aria-selected", String(isActive));
      });

      applyFilters();
    });
  });

  searchInput?.addEventListener("input", applyFilters);

  searchForm?.addEventListener("submit", (event) => {
    event.preventDefault();
    applyFilters();
  });

  jumpButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const target = document.getElementById(button.dataset.target);

      target?.scrollIntoView({
        behavior: "smooth",
        block: "start"
      });
    });
  });

  const animatedItems = document.querySelectorAll(
    ".resource-card, .material-note-box"
  );

  animatedItems.forEach((item, index) => {
    item.style.opacity = "0";
    item.style.animation = "materialFadeUp .55s ease forwards";
    item.style.animationDelay = `${index * 0.045}s`;
  });
});

const materialAnimationStyle = document.createElement("style");

materialAnimationStyle.textContent = `
  @keyframes materialFadeUp {
    from {
      opacity: 0;
      transform: translateY(18px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
`;

document.head.appendChild(materialAnimationStyle);
