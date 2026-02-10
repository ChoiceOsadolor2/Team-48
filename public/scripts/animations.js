// moving background animation --> backgrund is of a section to to repeat, this section x position is dcremented by 1 each time. 
// Moving background
let x= 0;
const section=document.getElementById('wrapper_overlay');

function moveX(){
x--
wrapper_overlay.style.backgroundPosition= `${x}px`;

}

let y=0;
const section2=document.querySelector('main');

function moveY(){
y++
section2.style.backgroundPosition= `0 ${y}px`;

}
setInterval(moveY,20)

setInterval(moveX,30)




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

window.addEventListener("scroll", () => {
    if (window.scrollY > lastScrollY) {
        filter.style.opacity = "0";
    } else {
        filter.style.opacity = "1";
    }

    lastScrollY = window.scrollY;
});




                                            // Dark Mode Toggle 
document.querySelector("html").setAttribute("data-theme", 'dark');


// Create a button element for theme toggling
const ThemeToggleContainer = document.createElement('div');
ThemeToggleContainer.setAttribute('id', 'theme-toggle-container');
document.body.appendChild(ThemeToggleContainer);

const ThemeToggle= document.createElement('input');
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

/**
* 4. Add an event listener to toggle the theme
*/
button.addEventListener("click", (event) => {
  const newTheme = currentThemeSetting === "dark" ? "light" : "dark";

  localStorage.setItem("theme", newTheme);
  updateButton({ buttonEl: button, isDark: newTheme === "dark" });
  updateThemeOnHtmlEl({ theme: newTheme });

  currentThemeSetting = newTheme;
}); 


const footerFile = '../pages/footer.html';

