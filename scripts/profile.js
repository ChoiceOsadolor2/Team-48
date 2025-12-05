document.addEventListener("DOMContentLoaded", () => {
    const user = JSON.parse(localStorage.getItem("currentUser"));

    if (!user) {
        window.location.href = "login.html";
        return;
    }

    document.getElementById("profileUsername").textContent = user.username;
    document.getElementById("profileEmail").textContent = user.email;
});

function logout() {
    localStorage.removeItem("currentUser");
    window.location.href = "login.html";
}
