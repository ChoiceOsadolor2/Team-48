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
    filter_options.style.height='70%';
}
}

function closeSection(id){
    const section = document.getElementById(id);
    const section_main = document.getElementById(`${id}_main`);
    if(section_main) section_main.style.width='0';
    if(section) section.style.display='none';
}
