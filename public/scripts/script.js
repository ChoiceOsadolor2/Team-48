function toggleMenu() {
    const nav = document.getElementById("navLinks");
    nav.style.display = (nav.style.display === "flex") ? "none" : "flex";
}

const revealElements = document.querySelectorAll(
    ".about-section, .service-box, .value-box, .testimonial-box, .services-section, .values-section, .testimonials-section"
);

function scrollReveal() {
    for (let el of revealElements) {
        const rect = el.getBoundingClientRect();
        const windowHeight = window.innerHeight;

        if (rect.top < windowHeight - 100) {
            el.classList.add("show");
        }
    }
}

window.addEventListener("scroll", scrollReveal);
window.addEventListener("load", scrollReveal);
