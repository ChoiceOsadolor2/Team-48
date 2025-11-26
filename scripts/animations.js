// moving background animation --> backgrund is of a section to to repeat, this section x position is dcremented by 1 each time. 
// Moving background
let x= 0;
const section=document.getElementById('wrapper_overlay');

function moveX(){
x--
wrapper_overlay.style.backgroundPosition= `${x}px`;

}
setInterval(moveX,30)






// Open Hidden :Basket, Menu, Search

function show(id){
const section =document.getElementById(id);
const section_main = document.getElementById(`${id}_main`);
const filter_options= document.getElementById(`${id}_content`);


if(section){
    section.style.display='block';
}
if(section_main){
    section_main.style.width='30em';
}

if(filter){
    document.getElementById('toggle_down').style.display='flex';
        document.getElementById('toggle_up').style.display='none';

    filter_options.style.height='70%';
}
}

function closeSection(id){
    const section = document.getElementById(id);
    const section_main = document.getElementById(`${id}_main`);
    const filter_options= document.getElementById(`${id}_content`);

    if(section_main) section_main.style.width='0';
    if(section) section.style.display='none';

if(filter_options){
    document.getElementById('toggle_down').style.display='none';
        document.getElementById('toggle_up').style.display='flex';

    filter_options.style.height='0%';
    section.style.display='flex'
}}


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
