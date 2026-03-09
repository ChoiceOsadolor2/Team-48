(function () {

  const form = document.getElementById("search-form");
  const input = document.getElementById("search-bar");

  if (!form || !input) return;

  form.addEventListener("submit", function (e) {

    e.preventDefault();

    const query = (input.value || "").trim();

    // If search box is empty remove stored query
    if (!query) {
      localStorage.removeItem("veltrix_search_query");
      window.location.href = "ShopAll.html";
      return;
    }

    // Save query so ShopAll page can read it
    localStorage.setItem("veltrix_search_query", query);

    // Redirect to shop page
    window.location.href = "ShopAll.html";

  });

})();