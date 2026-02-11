const input= document.getElementById('Search_Input');
input.addEventListener('input',()=>{
    if(!input.value ===' '){
        show('search');
    }
})