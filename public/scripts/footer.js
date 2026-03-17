fetch("../pages/footer.html", { credentials: "same-origin" })
    .then((response) => {
        if (!response.ok) {
            throw new Error(`Failed to load footer: ${response.status}`);
        }

        return response.text();
    })
    .then((data) => {
        const footer = document.getElementById("footer");
        if (footer) {
            footer.innerHTML = data;
        }
    })
    .catch((error) => {
        console.error(error);
    });
