

// //Shop All products page, get template, make a clone, 
//Category products, onclick , get category, query string, and match to dtb, di=ynamically change shopAll page
const query = window.location.search;
const parameters= new URLSearchParams(query);

const productid = parameters.get('id');
const category = parameters.get('category');

const ProductCard_template=document.querySelector('template');
const container=document.getElementById('products_container');
const container2=document.getElementById('product_display');

let count =0;//ammount of products in the basket


fetch('products.json')
.then(res => res.json()).then(products =>{
  let currentCategory;

  if(productid){
    const product= products.find(product=>product.id==productid);
    console.log(product);

    if(product){
    container2.querySelector('.product_image').src=product.thumbnail;
    container2.querySelector('.product_name').textContent=product.name;
    container2.querySelector('#product_brand').textContent=product.brand;
    container2.querySelector('.product_description').textContent=product.full_description;
    container2.querySelector('.product_price').textContent=`£${product.price}`;
    document.title=product.name;
    }
  }

  if(category){
currentCategory = products.filter(product=>product.category ==category);
document.getElementById('title').textContent=category;

  }else{
  currentCategory=products;
 document.getElementById('title').textContent='Shop All';
  }//Open depending on the category in the url, products and title are dependant on catgegory, else do all.

      container.innerHTML = ' ';

currentCategory.forEach(product => {
/** @type {DocumentFragment} */
    const clone=ProductCard_template.content.cloneNode(true);
    
clone.querySelector('.product_price').textContent=product.price;
clone.querySelector('.product_description').textContent=product.description;
clone.querySelector('.product_name').textContent=product.name;
clone.querySelector('.product_image').src=product.thumbnail;

clone.querySelector('.product_card').id=product.id;

const image=clone.querySelector('.product_image');
image.addEventListener('click',()=>{
    window.location.href=`ProductPage.html?id=${product.id}`;
});
const button=clone.querySelector('.add_to_basket');
button.addEventListener('click',()=> AddToBasket(product.id));



    container.appendChild(clone);
});

window.AddToBasket =function(id){
      count ++;
for(let i=0; i< document.getElementsByClassName('basket_count').length; i++)
    document.getElementsByClassName('basket_count')[i].textContent = `(${count})`;

const BasketContainer = document.querySelector('#basket_display');
const basket_product_template = document.querySelector('.basket_template');
const clone=basket_product_template.content.cloneNode(true);

let chosenProduct=products.find(product=>product.id==id);

clone.querySelector('img').src=chosenProduct.thumbnail;
clone.querySelector('p').textContent=chosenProduct.name;
clone.querySelector('sub').textContent=chosenProduct.price;

BasketContainer.append(clone);

}

function RemoveFromBasket(product_id){
  clone=null;
    count--;
    document.getElementById('basket_count').textContent = `(${count})`;
}

});

