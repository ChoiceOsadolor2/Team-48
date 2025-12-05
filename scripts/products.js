

// //Shop All products page, get template, make a clone, Category products, onclick , get category, query string, and match to dtb, di=ynamically change shopAll page

//get query string parameters
const query = window.location.search;
const parameters= new URLSearchParams(query);

const productid = parameters.get('id');
const category = parameters.get('category');

//get template and container
const ProductCard_template=document.querySelector('template');
const container=document.getElementById('products_container');
const container2=document.getElementById('product_display');

//basket management
let count =0;
let basket=[];//get basket from local storage or empty array / REPLACE WITH ACTUAL BASKET MANAGEMENT  

                                                //load products from json
fetch('products.json')
.then(res => res.json()).then(products =>{
    let currentCategory;
    localStorage.setItem('products', JSON.stringify(products));//store products in local storage

window.AddToBasket =function(id){
let chosenProduct=products.find(product=>product.id==id);
let basketItem=basket.find(item=>item.id==id);
let quantity=1;

if(basketItem){
  basketItem.quantity+=quantity;
}else{
  basket.push({...chosenProduct, quantity:quantity});
  localStorage.setItem('basket', JSON.stringify(basket));

const BasketContainer = document.querySelector('#basket_display');
basket.forEach(item=>{

const basket_product_template = document.querySelector('.basket_template');
const clone=basket_product_template.content.cloneNode(true);

clone.querySelector('img').src=item.thumbnail;
clone.querySelector('p').textContent=item.name;
clone.querySelector('sub').textContent=`£${item.price} x ${item.quantity}`;

quantity=document.getElementsByClassName('quantity').value;
BasketContainer.append(clone);

});

}
  count++;
    for(let i=0; i<basket.length; i++){
    document.getElementsByClassName('basket_count')[i].textContent = `(${count})`;
    total= basket.reduce((acc, item) => acc + item.price * item.quantity, 0);
    document.getElementById('total').textContent = `£${total.toFixed(2)}`;
  }
}

  if(productid){
    const product= products.find(product=>product.id==productid);
    console.log(product);

    if(product){
    container2.querySelector('.product_image').src=product.thumbnail;
    container2.querySelector('.product_name').textContent=product.name;
    container2.querySelector('#product_brand').textContent=product.brand;
    container2.querySelector('.product_description').textContent=product.full_description;
    container2.querySelector('.product_price').textContent=`£${product.price}`;

    const button=container2.querySelector('.add_to_basket');
    button.addEventListener('click',()=> AddToBasket(product.id));

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


});





// Quantity Input function
(function () {
	const qtyInputs = document.querySelectorAll(".qty-input");

	if (!qtyInputs.length) {
		return;
	}

	qtyInputs.forEach(function(qtyContainer) {
		const input = qtyContainer.querySelector(".product-qty");
		const minusBtn = qtyContainer.querySelector(".qty-count--minus");
		const addBtn = qtyContainer.querySelector(".qty-count--add");
		
		const qtyMin = parseInt(input.getAttribute("min")) || 0;
		const qtyMax = parseInt(input.getAttribute("max")) || 10;

		// Update button states based on current value
		function updateButtons() {
			const qty = parseInt(input.value);

			if (qty <= qtyMin) {
				minusBtn.setAttribute("disabled", "true");
			} else {
				minusBtn.removeAttribute("disabled");
			}

			if (qty >= qtyMax) {
				addBtn.setAttribute("disabled", "true");
			} else {
				addBtn.removeAttribute("disabled");
			}
		}

		// Handle input change
		input.addEventListener("change", updateButtons);
		input.addEventListener("input", updateButtons);

		// Handle button clicks
		minusBtn.addEventListener("click", function() {
			let qty = parseInt(input.value);
			qty = qty > qtyMin ? qty - 1 : qtyMin;
			input.value = qty;
			updateButtons();
		});

		addBtn.addEventListener("click", function() {
			let qty = parseInt(input.value);
			qty = qty < qtyMax ? qty + 1 : qtyMax;
			input.value = qty;
			updateButtons();
		});

		// Initialize button states
		updateButtons();
	});
})();


const reviews_button = document.getElementById("reviews_button");
const reviewsContainer = document.getElementById("reviews");
const template_review = document.getElementById("template_review");

function addReview(review){
    const clone = template_review.content.cloneNode(true);
    clone.querySelector(".reviewer_name").textContent = review.name;
    clone.querySelector(".review_date").textContent = review.date;
    clone.querySelector(".review_rating").textContent = "Rating: " + review.rating + "/5";
    clone.querySelector(".review_comment").textContent = review.comment;
    reviewsContainer.appendChild(clone);
}