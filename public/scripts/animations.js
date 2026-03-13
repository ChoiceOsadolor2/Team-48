// moving background animation --> background is a section that repeats.
const section = document.getElementById('wrapper_overlay');
const section2 = document.querySelector('main');
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

let x = 0;
let y = 0;
let animationFrameId = null;
let lastFrameTime = 0;

function updateBackgroundPositions() {
  if (section) {
    section.style.backgroundPosition = `${x}px`;
  }

  if (section2) {
    section2.style.backgroundPosition = `center ${y}px`;
  }

  document.body.style.setProperty('--bg-y-pos', `center ${y}px`);
}

function animateBackground(timestamp) {
  if (document.hidden || prefersReducedMotion.matches) {
    animationFrameId = null;
    return;
  }

  if (!lastFrameTime || timestamp - lastFrameTime >= 40) {
    x -= 1;
    y += 1;
    updateBackgroundPositions();
    lastFrameTime = timestamp;
  }

  animationFrameId = window.requestAnimationFrame(animateBackground);
}

function startBackgroundAnimation() {
  if (animationFrameId || prefersReducedMotion.matches) return;
  animationFrameId = window.requestAnimationFrame(animateBackground);
}

function stopBackgroundAnimation() {
  if (!animationFrameId) return;
  window.cancelAnimationFrame(animationFrameId);
  animationFrameId = null;
}

if (section || section2) {
  updateBackgroundPositions();
  startBackgroundAnimation();

  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      stopBackgroundAnimation();
      return;
    }

    lastFrameTime = 0;
    startBackgroundAnimation();
  });

  if (typeof prefersReducedMotion.addEventListener === 'function') {
    prefersReducedMotion.addEventListener('change', () => {
      if (prefersReducedMotion.matches) {
        stopBackgroundAnimation();
      } else {
        lastFrameTime = 0;
        startBackgroundAnimation();
      }
    });
  }
}




// Open Hidden :Basket, Menu, Search

// function show(id){
// const section =document.getElementById(id);
// const section_main = document.getElementById(`${id}_main`);
// const filter_options= document.getElementById(`${id}_content`);


// if(section){
//     section.style.display='block';
// }
// if(section_main){
//     section_main.style.width='30em';
// }

// if(filter){
//     document.getElementById('toggle_down').style.display='flex';
//         document.getElementById('toggle_up').style.display='none';

//     filter_options.style.height='70%';
// }
// }

// function closeSection(id){
//     const section = document.getElementById(id);
//     const section_main = document.getElementById(`${id}_main`);
//     const filter_options= document.getElementById(`${id}_content`);

//     if(section_main) section_main.style.width='0';
//     if(section) section.style.display='none';

// if(filter_options){
//     document.getElementById('toggle_down').style.display='none';
//         document.getElementById('toggle_up').style.display='flex';

//     filter_options.style.height='0%';
//     section.style.display='flex'
// }}


// Show filter, Hide Filter on scroll

const filter = document.getElementById("filter");
let lastScrollY = window.scrollY;

if (filter) {
  let filterTicking = false;

  window.addEventListener("scroll", () => {
    if (filterTicking) return;
    filterTicking = true;

    window.requestAnimationFrame(() => {
    if (window.scrollY > lastScrollY) {
      filter.style.opacity = "0";
    } else {
      filter.style.opacity = "1";
    }

    lastScrollY = window.scrollY;
      filterTicking = false;
    });
  }, { passive: true });
}




// Dark Mode Toggle 


// Create a button element for theme toggling
const ThemeToggleContainer = document.createElement('div');
ThemeToggleContainer.setAttribute('id', 'theme-toggle-container');
document.body.appendChild(ThemeToggleContainer);

const ThemeToggle = document.createElement('input');
const ThemeToggleLabel = document.createElement('label');
ThemeToggleLabel.setAttribute('for', 'theme-toggle-button');
ThemeToggle.type = "checkbox";
ThemeToggle.id = "theme-toggle-button";
ThemeToggle.setAttribute('data-theme-toggle', '');
ThemeToggle.setAttribute('aria-label', 'Change to light theme');


ThemeToggleContainer.appendChild(ThemeToggle);
ThemeToggleContainer.appendChild(ThemeToggleLabel);


// Sourced from Codepen
/**
* Utility function to calculate the current theme setting.
* Look for a local storage value.
* Fall back to system setting.
* Fall back to light mode.
*/
function calculateSettingAsThemeString({ localStorageTheme, systemSettingDark }) {
  if (localStorageTheme !== null) {
    return localStorageTheme;
  }

  if (systemSettingDark.matches) {
    return "dark";
  }

  return "light";
}

/**
* Utility function to update the button text and aria-label.
*/
function updateButton({ buttonEl, isDark }) {
  const newCta = isDark ? "Change to light theme" : "Change to dark theme";
  // use an aria-label if you are omitting text on the button
  // and using a sun/moon icon, for example
  buttonEl.setAttribute("aria-label", newCta);
  buttonEl.innerText = newCta;
}

/**
* Utility function to update the theme setting on the html tag
*/
function updateThemeOnHtmlEl({ theme }) {
  document.querySelector("html").setAttribute("data-theme", theme);
}


/**
* On page load:
*/

/**
* 1. Grab what we need from the DOM and system settings on page load
*/
const button = ThemeToggle;
const localStorageTheme = localStorage.getItem("theme");
const systemSettingDark = window.matchMedia("(prefers-color-scheme: dark)");

/**
* 2. Work out the current site settings
*/
let currentThemeSetting = calculateSettingAsThemeString({ localStorageTheme, systemSettingDark });

/**
* 3. Update the theme setting and button text accoridng to current settings
*/
updateButton({ buttonEl: button, isDark: currentThemeSetting === "dark" });
updateThemeOnHtmlEl({ theme: currentThemeSetting });
button.checked = (currentThemeSetting === "dark");

/**
* 4. Add an event listener to toggle the theme
*/
button.addEventListener("click", (event) => {
  const newTheme = currentThemeSetting === "dark" ? "light" : "dark";

  localStorage.setItem("theme", newTheme);
  updateButton({ buttonEl: button, isDark: newTheme === "dark" });
  updateThemeOnHtmlEl({ theme: newTheme });

  currentThemeSetting = newTheme;
  button.checked = (currentThemeSetting === "dark");
});

systemSettingDark.addEventListener("change", (event) => {
  if (localStorage.getItem("theme") !== null) return;

  const nextTheme = event.matches ? "dark" : "light";
  updateButton({ buttonEl: button, isDark: nextTheme === "dark" });
  updateThemeOnHtmlEl({ theme: nextTheme });
  currentThemeSetting = nextTheme;
  button.checked = (currentThemeSetting === "dark");
});


// ============================================
// Reviews: Infinite Loop + Drag to Scroll
// ============================================

const reviewsSlider = document.querySelector('.reviews-scroll-container');

if (reviewsSlider) {

  // --- Infinite Loop Setup ---
  const originalCards = Array.from(reviewsSlider.children);
  const totalCards = originalCards.length;

  // Clone all cards and append a copy at the end and prepend at the start
  originalCards.forEach(card => {
    const cloneEnd = card.cloneNode(true);
    cloneEnd.setAttribute('aria-hidden', 'true');
    reviewsSlider.appendChild(cloneEnd);
  });

  originalCards.forEach(card => {
    const cloneStart = card.cloneNode(true);
    cloneStart.setAttribute('aria-hidden', 'true');
    reviewsSlider.prepend(cloneStart);
  });

  // Calculate card width (including gap)
  function getCardWidth() {
    const card = reviewsSlider.querySelector('.review-card');
    const gap = parseInt(getComputedStyle(reviewsSlider).gap) || 32;
    return card.offsetWidth + gap;
  }

  // Start scroll position = width of one full set of clones (at the start)
  function initScroll() {
    reviewsSlider.scrollLeft = getCardWidth() * totalCards;
  }

  initScroll();

  // On scroll: silently jump when entering the clone zones
  let isJumping = false;
  reviewsSlider.addEventListener('scroll', () => {
    if (isJumping) return;
    const cardW = getCardWidth();
    const cloneSetWidth = cardW * totalCards;
    const sl = reviewsSlider.scrollLeft;
    const maxScroll = reviewsSlider.scrollWidth - reviewsSlider.clientWidth;

    // If scrolled into the leading clone zone, jump to same position in real cards
    if (sl < cloneSetWidth - cardW) {
      isJumping = true;
      reviewsSlider.scrollLeft = sl + cloneSetWidth;
      requestAnimationFrame(() => { isJumping = false; });
    }

    // If scrolled into the trailing clone zone, jump back
    if (sl > cloneSetWidth * 2 - cardW) {
      isJumping = true;
      reviewsSlider.scrollLeft = sl - cloneSetWidth;
      requestAnimationFrame(() => { isJumping = false; });
    }
  });

  // --- Drag to Scroll ---
  let isDown = false;
  let startX;
  let scrollStart;

  reviewsSlider.addEventListener('mousedown', (e) => {
    isDown = true;
    reviewsSlider.classList.add('active');
    startX = e.pageX - reviewsSlider.offsetLeft;
    scrollStart = reviewsSlider.scrollLeft;
  });

  reviewsSlider.addEventListener('mouseleave', () => {
    isDown = false;
    reviewsSlider.classList.remove('active');
  });

  reviewsSlider.addEventListener('mouseup', () => {
    isDown = false;
    reviewsSlider.classList.remove('active');
  });

  reviewsSlider.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - reviewsSlider.offsetLeft;
    const walk = (x - startX) * 1.5;
    reviewsSlider.scrollLeft = scrollStart - walk;
  });
}
