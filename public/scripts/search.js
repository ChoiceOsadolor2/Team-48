(function () {
  const form = document.getElementById("search-form");
  const input = document.getElementById("search-bar");
  if (!form || !input) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const query = (input.value || "").trim();
    if (!query) return;

    localStorage.setItem("veltrix_search_query", query);

    // IMPORTANT: make sure this path matches where ShopAll actually lives
    window.location.href = "ShopAll.html";
  });
})();
