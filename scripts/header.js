

const headerFile='../pages/header.html';

fetch (headerFile)
.then(Response => Response.text())
.then(y => document.querySelector('header').innerHTML = y);



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
// if(search){
//       section.style.display = "flex";

// }
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
}

// if(search){
//       section.style.display = "none";
// }

}


