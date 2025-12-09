// -------------------------------
// Query string & DOM references
// -------------------------------
const query = window.location.search;
const parameters = new URLSearchParams(query);

const productid = parameters.get('id');
const category = parameters.get('category');

const ProductCard_template = document.querySelector('template');
const container = document.getElementById('products_container');
const container2 = document.getElementById('product_display');

// -------------------------------
// Helper: normalize image URLs
// -------------------------------
function getImageUrl(product) {
  if (product.image_url) {
    if (product.image_url.includes('example.com')) {
      return '/assets/placeholder.png';
    }

    if (product.image_url.startsWith('http')) {
      return product.image_url;
    }

    return '/' + product.image_url.replace(/^\/+/, '');
  }

  if (product.thumbnail) {
    if (product.thumbnail.startsWith('http')) {
      return product.thumbnail;
    }
    return '/' + product.thumbnail.replace(/^\/+/, '');
  }

  return '/assets/placeholder.png';
}

async function loadCartFromBackend() {
  try {
    console.log('Loading cart from backend...');

    const res = await fetch('/cart/json', {
      headers: { 'Accept': 'application/json' },
      credentials: 'include',
    });

    console.log('Cart JSON status:', res.status);

    if (!res.ok) {
      const text = await res.text();
      console.error('Cart JSON error body:', text);
      return;
    }

    const text = await res.text();
    console.log('Cart JSON raw response:', text);

    let data = {};
    try {
      data = JSON.parse(text);
    } catch (e) {
      console.error('Non-JSON cart response (probably HTML login page).');
      return;
    }

    let rawItems = data.items || [];
    const items = rawItems.map((it) => {
      if (it.product) {
        return {
          id: it.product.id,
          name: it.product.name,
          price: it.product.price,
          image_url: it.product.image_url,
          quantity: it.quantity,
        };
      }
      return it;
    });

    const total = data.total || items.reduce(
      (sum, it) => sum + (parseFloat(it.price) || 0) * (it.quantity || 0),
      0
    );

    console.log('Parsed cart items:', items, 'total:', total);

    const BasketContainer = document.querySelector('#basket_display');
    if (!BasketContainer) {
      console.warn('#basket_display not found in DOM');
      return;
    }

    BasketContainer.innerHTML = '';

    const basketTemplate = document.querySelector('.basket_template');
    if (!basketTemplate) {
      console.warn('.basket_template not found in DOM');
      return;
    }

    items.forEach((item) => {
      const clone = basketTemplate.content.cloneNode(true);

      const imgEl = clone.querySelector('img');
      const nameEl = clone.querySelector('p');
      const subEl = clone.querySelector('sub');
      const qtyInput = clone.querySelector('.quantity');

      if (imgEl) imgEl.src = getImageUrl(item);
      if (nameEl) nameEl.textContent = item.name;
      if (subEl) subEl.textContent = `£${item.price} x ${item.quantity}`;
      if (qtyInput) qtyInput.value = item.quantity;

      BasketContainer.appendChild(clone);
    });

    const count = items.reduce((acc, it) => acc + (it.quantity || 0), 0);
    const basketCountEls = document.getElementsByClassName('basket_count');
    for (let i = 0; i < basketCountEls.length; i++) {
      basketCountEls[i].textContent = `(${count})`;
    }

    const totalEl = document.getElementById('total');
    if (totalEl) {
      totalEl.textContent = `£${total.toFixed(2)}`;
    }
  } catch (err) {
    console.error('Error loading cart:', err);
  }
}


// -------------------------------
// Add to basket
// -------------------------------
window.AddToBasket = async function (id) {
  try {
    console.log('AddToBasket called with id:', id);

    // This route is defined in web.php as cart.add.json
    const url = `/cart/add-json/${id}?quantity=1`;
    const res = await fetch(url, {
      method: 'GET',
      headers: {
        Accept: 'application/json',
      },
      credentials: 'include',
    });

    console.log('AddToBasket status:', res.status);

    if (!res.ok) {
      const text = await res.text();
      console.error('AddToBasket error body:', text);
      alert('Could not add to cart. You may need to log in first.');
      return;
    }

    // Try to consume JSON, but don’t crash if it isn’t
    try {
      const data = await res.json();
      console.log('AddToBasket JSON response:', data);
    } catch (e) {
      console.warn('AddToBasket response not JSON (maybe HTML).');
    }

    await loadCartFromBackend();
  } catch (err) {
    console.error('Error adding to cart:', err);
    alert('Could not add to cart.');
  }
};

// -------------------------------
// Load products from backend
// -------------------------------
fetch('/products', { credentials: 'include' })
  .then((res) => {
    if (!res.ok) {
      throw new Error('Failed to load products from backend');
    }
    return res.json();
  })
  .then((data) => {
    const products = Array.isArray(data) ? data : data.products || [];

    window.allProducts = products;
    localStorage.setItem('products', JSON.stringify(products));

    let currentCategory;

    // ---------------------------
    // Product page (single item)
    // ---------------------------
    if (productid && container2) {
      const product = products.find((p) => String(p.id) === String(productid));
      console.log('Selected product:', product);

      if (product) {
        const img = container2.querySelector('.product_image');
        const nameEl = container2.querySelector('.product_name');
        const brandEl = container2.querySelector('#product_brand');
        const descEl = container2.querySelector('.product_description');
        const priceEl = container2.querySelector('.product_price');

        if (img) img.src = getImageUrl(product);
        if (nameEl) nameEl.textContent = product.name;
        if (brandEl) brandEl.textContent = product.brand || '';
        if (descEl) descEl.textContent = product.description;
        if (priceEl) priceEl.textContent = `£${product.price}`;

        const button = container2.querySelector('.add_to_basket');
        if (button) {
          button.addEventListener('click', () => window.AddToBasket(product.id));
        }

        document.title = product.name;
      }
    }

    // ---------------------------
    // Category filtering
    // ---------------------------
    if (category) {
      currentCategory = products.filter(
        (p) => p.category && p.category.name === category
      );
      const titleEl = document.getElementById('title');
      if (titleEl) titleEl.textContent = category;
    } else {
      currentCategory = products;
      const titleEl = document.getElementById('title');
      if (titleEl) titleEl.textContent = 'Shop All';
    }

    // ---------------------------
    // Shop All listing
    // ---------------------------
    if (container && ProductCard_template) {
      container.innerHTML = '';

      currentCategory.forEach((product) => {
        const clone = ProductCard_template.content.cloneNode(true);

        const priceEl = clone.querySelector('.product_price');
        const descEl = clone.querySelector('.product_description');
        const nameEl = clone.querySelector('.product_name');
        const img = clone.querySelector('.product_image');
        const card = clone.querySelector('.product_card');

        if (priceEl) priceEl.textContent = product.price;
        if (descEl) descEl.textContent = product.description;
        if (nameEl) nameEl.textContent = product.name;
        if (img) img.src = getImageUrl(product);
        if (card) card.id = product.id;

        if (img) {
          img.addEventListener('click', () => {
            window.location.href = `ProductPage.html?id=${product.id}`;
          });
        }

        const button = clone.querySelector('.add_to_basket');
        if (button) {
          button.addEventListener('click', () => window.AddToBasket(product.id));
        }

        container.appendChild(clone);
      });
    }

    // Initial cart render
    loadCartFromBackend();
  })
  .catch((err) => {
    console.error('Error loading products:', err);
  });

// -------------------------------
// Quantity controls (for basket rows)
// -------------------------------
(function () {
  const qtyInputs = document.querySelectorAll('.qty-input');

  if (!qtyInputs.length) {
    return;
  }

  qtyInputs.forEach(function (qtyContainer) {
    const input = qtyContainer.querySelector('.product-qty');
    const minusBtn = qtyContainer.querySelector('.qty-count--minus');
    const addBtn = qtyContainer.querySelector('.qty-count--add');

    if (!input || !minusBtn || !addBtn) return;

    const qtyMin = parseInt(input.getAttribute('min')) || 0;
    const qtyMax = parseInt(input.getAttribute('max')) || 10;

    function updateButtons() {
      const qty = parseInt(input.value) || 0;

      if (qty <= qtyMin) {
        minusBtn.setAttribute('disabled', 'true');
      } else {
        minusBtn.removeAttribute('disabled');
      }

      if (qty >= qtyMax) {
        addBtn.setAttribute('disabled', 'true');
      } else {
        addBtn.removeAttribute('disabled');
      }
    }

    input.addEventListener('change', updateButtons);
    input.addEventListener('input', updateButtons);

    minusBtn.addEventListener('click', function () {
      let qty = parseInt(input.value) || 0;
      qty = qty > qtyMin ? qty - 1 : qtyMin;
      input.value = qty;
      updateButtons();
    });

    addBtn.addEventListener('click', function () {
      let qty = parseInt(input.value) || 0;
      qty = qty < qtyMax ? qty + 1 : qtyMax;
      input.value = qty;
      updateButtons();
    });

    updateButtons();
  });
})();

// -------------------------------
// Reviews (unchanged)
// -------------------------------



let reviews=JSON.parse(localStorage.getItem('reviews')) || [];
  const reviewsContainer = document.getElementById('reviews_container');

// || = also known as /or
window.onload=function(){
reviews.forEach(review=>display(review))
}

function display(review){
  const template_review = document.getElementById('review_template');
  const clone=template_review.content.cloneNode(true);

  clone.querySelector('h3').textContent = review.username;
  clone.querySelector('p').textContent = review.text;
  clone.querySelector('span').textContent =
    'Rating: ' + review.rating + '/5';

    reviewsContainer.appendChild(clone);
};

function addReview(event) {
  event.preventDefault();

  const form=document.getElementById('review_form')
  
  const username=document.getElementById('review_name').value
  const text=document.getElementById('review_input').value
  const rating =document.getElementById('review_rating').value;

  const review={username,text,rating};

  reviews.push(review);
  localStorage.setItem('reviews',JSON.stringify(reviews))


  display(review);

  form.reset();
}

function toggleReview(){
    const form = document.getElementById('review_form');
    if(form.style.display=='none'){
        form.style.display='flex';
    }else{
        form.style.display='none';
    }
}
