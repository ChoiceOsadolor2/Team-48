

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
    // section.style.display='block';
    section.style.visibility='visible';
    section.style.backdropFilter = 'blur(30px)'; //blur

    section.style.backgroundColor = 'rgba(0,0,0,0.6)';

}
if(section_main){
    section_main.style.width='35%';
    section_main.style.minWidth='20em';
}

if(filter_options){
    document.getElementById('toggle_down').style.display='flex';
    document.getElementById('toggle_up').style.display='none';

    filter_options.style.height='70%';

    section.style.backdropFilter = 'none'; //blur

    section.style.backgroundColor = 'transparent';
}
if( id ==='search'){
      section.style.display = "flex";

}
}



function closeSection(id){
    const section = document.getElementById(id);
    const section_main = document.getElementById(`${id}_main`);
    const filter_options= document.getElementById(`${id}_content`);

    if(section_main) section_main.style.width='0';
    // if(section) section.style.display='none';
    if(section) section.style.visibility='hidden';

if(filter_options){
    document.getElementById('toggle_down').style.display='none';
    document.getElementById('toggle_up').style.display='flex';
        section.style.visibility = 'visible';
}

if(id==='search'){
      section.style.display = "none";
}

}








// const headerFile='../pages/header.html';

// fetch (headerFile)
// .then(Response => Response.text())
// .then(y => document.querySelector('header').innerHTML = y);



// // Open Hidden :Basket, Menu, Search

// function show(id){
//     const section = document.getElementById(id);
//     const section_main = document.getElementById(`${id}_main`);
//     const filter_options= document.getElementById(`${id}_content`);

//     if(section){
//         section.style.visibility='visible';
//         section.style.backdropFilter = 'blur(30px)';
//         section.style.backgroundColor = 'rgba(0,0,0,0.6)';
//     }
//     if(section_main){
//         section_main.style.width='35%';
//     }
//     if(filter_options){
//         filter_options.style.height='70%';
//     }
// }


// function closeSection(id){
//     const section = document.getElementById(id);
//     const section_main = document.getElementById(`${id}_main`);
//     const filter_options= document.getElementById(`${id}_content`);

//     if(section_main) section_main.style.width='0';
//     // if(section) section.style.display='none';
//     if(section) section.style.visibility='hidden';

// if(filter_options){
//     document.getElementById('toggle_down').style.display='none';
//     document.getElementById('toggle_up').style.display='flex';
//         section.style.visibility = 'visible';
// }

// if(id==='search'){
//       section.style.display = "none";
// }

// }


