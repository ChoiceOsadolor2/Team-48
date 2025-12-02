

// //Shop All products page, get template, make a clone, 
//Category products, onclick , get category, query string, and match to dtb, di=ynamically change shopAll page
const query = window.location.search;
const parameters= new URLSearchParams(query);

const productid = parameters.get('id');
const category = parameters.get('category');

const ProductCard_template=document.querySelector('template');
const container=document.getElementById('products_container');

if(category){
document.getElementById('title').textContent=category;
}else{ document.getElementById('title').textContent='Shop All'}

fetch('products.json')
.then(res => res.json()).then(products =>{

  let currentCategory;

  if(category){
currentCategory = products.filter(product=>product.category ==category);
document.getElementById('title').textContent=category;
  }else{
  currentCategory=products;
 document.getElementById('title').textContent='Shop All';
  }

currentCategory.forEach(product => {
/** @type {DocumentFragment} */
    const clone=ProductCard_template.content.cloneNode(true);
    
clone.querySelector('.product_price').textContent=product.price;
clone.querySelector('.product_description').textContent=product.description;
clone.querySelector('.product_name').textContent=product.name;
clone.querySelector('.product_image').src=product.thumbnail;
clone.querySelector('.product_card').id=product.id;
clone.querySelector('a').href = `ProductPage.html?id=${product.id}`;


    container.appendChild(clone);
});

})