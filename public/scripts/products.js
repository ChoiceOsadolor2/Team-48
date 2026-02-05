// ===============================
// URL Params
// ===============================
const query = window.location.search;
const parameters = new URLSearchParams(query);

const productid = parameters.get('id');
const category = parameters.get('category');

// ===============================
// DOM
// ===============================
const ProductCard_template = document.querySelector('template');
const container = document.getElementById('products_container');
const container2 = document.getElementById('product_display');


// ===============================
// SEARCH PATCH (binds to header search even if injected later)
// ===============================
(function () {
  function findHeaderSearchInput() {
    return (
      document.querySelector('#search_input') ||
      document.querySelector('#search_inpu') || // common typo
      document.querySelector('#shop_search') || // if you added one
      document.querySelector('header input[type="search"]') ||
      document.querySelector('header input[type="text"]') || // fallback
      document.querySelector('input[type="search"]')
    );
  }

  function applySearchFilter() {
    if (!container) return;

    const input = findHeaderSearchInput();
    if (!input) return;

    // base list = current category products (set after products load)
    const base = window.currentCategoryProducts || window.allProducts || [];
    const term = (input.value || '').trim().toLowerCase();

    const filtered = !term
      ? base
      : base.filter((p) => (p.name || '').toLowerCase().includes(term));

    // render filtered list
    if (typeof renderProducts === 'function') {
      renderProducts(filtered);
    }
  }

  function bind(input) {
    if (!input || input.dataset.boundSearch === '1') return;
    input.dataset.boundSearch = '1';

    // live search while typing
    input.addEventListener('input', applySearchFilter);

    // Enter should search (not reload / not submit anything)
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        applySearchFilter();
      }
    });

    // if it's inside a form, block form submit
    const form = input.closest('form');
    if (form) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        applySearchFilter();
      });
    }

    console.log('✅ Search bound to:', input);
  }

  // Try bind immediately
  bind(findHeaderSearchInput());

  // Observe DOM because header.js injects elements later
  const obs = new MutationObserver(() => {
    const input = findHeaderSearchInput();
    if (input) bind(input);
  });

  obs.observe(document.documentElement, { childList: true, subtree: true });

  // manual test
  window.__applySearchFilter = applySearchFilter;
})();



// ===============================
// Product lists (for filters/search)
// ===============================
window.allProducts = [];
window.currentCategoryProducts = [];
window.visibleBaseProducts = [];

// ===============================
// Image URL helper
// ===============================
function getImageUrl(product) {
  const imageUrl = product?.image_url || product?.thumbnail || '';

  if (!imageUrl) return '/assets/placeholder.png';
  if (imageUrl.includes('example.com')) return '/assets/placeholder.png';

  if (imageUrl.startsWith('http://') || imageUrl.startsWith('https://')) {
    return imageUrl;
  }

  const clean = imageUrl.replace(/^\/+/, '');

  if (clean.startsWith('storage/')) {
    return '/' + clean;
  }

  return '/storage/' + clean;
}

// ===============================
// Cart UI state
// ===============================
document.body.classList.remove('cart-ready');

async function loadCartFromBackend() {
  try {
    console.log('Loading cart from backend...');

    const res = await fetch('/cart/json', {
      headers: { Accept: 'application/json' },
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

    const total =
      data.total ||
      items.reduce(
        (sum, it) => sum + (parseFloat(it.price) || 0) * (it.quantity || 0),
        0
      );

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

  if (qtyInput) {
    qtyInput.value = item.quantity;
    qtyInput.readOnly = true;
    qtyInput.setAttribute('inputmode', 'none');
  }

  const minusBtn = clone.querySelector('.qty-count--minus');
  const addBtn = clone.querySelector('.qty-count--add');

  const qtyMin = 0;
  const stock = Number(item.stock ?? 0);
  const qtyMax = Math.min(10, stock || 10);
  const currentQty = parseInt(item.quantity) || 0;

  if (minusBtn) {
    minusBtn.disabled = currentQty <= qtyMin;
    minusBtn.onclick = (e) => {
      e.preventDefault();
      e.stopPropagation();
      window.UpdateCartQty(item.id, Math.max(qtyMin, currentQty - 1));
    };
  }

  if (addBtn) {
    addBtn.disabled = currentQty >= qtyMax;
    addBtn.onclick = (e) => {
      e.preventDefault();
      e.stopPropagation();
      window.UpdateCartQty(item.id, Math.min(qtyMax, currentQty + 1));
    };
  }

  if (stock > 0 && currentQty > stock) {
    const warning = document.createElement('div');
    warning.style.color = 'red';
    warning.style.fontSize = '12px';
    warning.textContent = `Only ${stock} available`;
    subEl?.after(warning);
  }

  const removeBtn = clone.querySelector('.remove_from_cart');
  if (removeBtn) {
    removeBtn.onclick = (e) => {
      e.preventDefault();
      e.stopPropagation();
      window.RemoveFromCart(item.id);
    };
  }

  BasketContainer.appendChild(clone);
});


    const count = items.reduce((acc, it) => acc + (it.quantity || 0), 0);
    const basketCountEls = document.getElementsByClassName('basket_count');
    for (let i = 0; i < basketCountEls.length; i++) {
      basketCountEls[i].textContent = `(${count})`;
    }

    const totalEl = document.getElementById('total');
    if (totalEl) {
      totalEl.textContent = `£${Number(total || 0).toFixed(2)}`;
    }

    document.body.classList.add('cart-ready');
  } catch (err) {
    console.error('Error loading cart:', err);
  }
}


// ===============================
// Add to basket
// ===============================
window.AddToBasket = async function (id, qty = 1) {
  try {
    qty = parseInt(qty, 10);
    if (!Number.isFinite(qty) || qty < 1) qty = 1;

    console.log('AddToBasket called with id:', id, 'qty:', qty);

    const url = `/cart/add-json/${id}?quantity=${qty}`;
    const res = await fetch(url, {
      method: 'GET',
      headers: { Accept: 'application/json' },
      credentials: 'include',
    });

    console.log('AddToBasket status:', res.status);

    if (!res.ok) {
      const text = await res.text();
      console.error('AddToBasket error body:', text);
      alert('Could not add to cart. You may need to log in first.');
      return;
    }

    await loadCartFromBackend();
  } catch (err) {
    console.error('Error adding to cart:', err);
    alert('Could not add to cart.');
  }
};


async function updateCartQty(productId, newQty) {
  const res = await fetch(`/cart/update/${productId}`, {
    method: 'PUT',
    credentials: 'include',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/x-www-form-urlencoded',
      // CSRF is required for PUT/DELETE in Laravel web routes
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
    },
    body: new URLSearchParams({ quantity: String(newQty) }),
  });

  if (!res.ok) {
    const text = await res.text();
    console.error('Update qty failed:', res.status, text);
    throw new Error('Update qty failed');
  }

  // backend returns updated cart json
  return res.json();
}






// ===============================
// Render Products (Shop All)
// - NO description on cards
// - View Product button does navigation
// - Image click DOES NOTHING
// ===============================
function renderProducts(list) {
  if (!container || !ProductCard_template) return;

  container.innerHTML = '';

  list.forEach((product) => {
    const clone = ProductCard_template.content.cloneNode(true);

    const priceEl = clone.querySelector('.product_price');
    const nameEl = clone.querySelector('.product_name');
    const img = clone.querySelector('.product_image');
    const descEl = clone.querySelector('.product_description'); // in case template still has it

    if (priceEl) priceEl.textContent = product.price;
    if (nameEl) nameEl.textContent = product.name;
    if (img) img.src = getImageUrl(product);

    // Ensure card description is hidden even if template has it
    if (descEl) descEl.textContent = '';

    // Disable image click navigation completely
    if (img) {
      img.style.cursor = 'default';
      img.onclick = null;
      img.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
      });
    }

    // View Product button
    const viewBtn = clone.querySelector('.view_product');
    if (viewBtn) {
      viewBtn.addEventListener('click', () => {
        window.location.href = `ProductPage.html?id=${product.id}`;
      });
    }

    // Add to basket / Out of stock handling
    const addBtn = clone.querySelector('.add_to_basket');
    if (addBtn) {
      const stock = Number(product.stock ?? 0);

      if (stock <= 0) {
        addBtn.textContent = 'Out of Stock';
        addBtn.disabled = true;
        addBtn.classList.add('out-of-stock');
        addBtn.onclick = null;
        addBtn.style.pointerEvents = 'none';
        addBtn.style.opacity = '0.6';
        addBtn.style.cursor = 'not-allowed';
      } else {
        addBtn.textContent = 'Add to Basket';
        addBtn.disabled = false;
        addBtn.classList.remove('out-of-stock');
        addBtn.style.pointerEvents = '';
        addBtn.style.opacity = '';
        addBtn.style.cursor = '';

        addBtn.addEventListener('click', () => window.AddToBasket(product.id));
      }
    }

    container.appendChild(clone);
  });
}


// ===============================
// NEW Search (brand new input)
// HTML must contain: <input id="shop_search" ...>
// ===============================
function initShopSearch() {
  const input = document.getElementById('shop_search');
  if (!input) {
    console.warn('⚠️ #shop_search not found (search will not work).');
    return;
  }

  function runSearch() {
    const term = (input.value || '').trim().toLowerCase();

    const base =
      window.visibleBaseProducts?.length
        ? window.visibleBaseProducts
        : window.currentCategoryProducts?.length
        ? window.currentCategoryProducts
        : window.allProducts;

    const filtered = !term
      ? base
      : base.filter((p) => (p.name || '').toLowerCase().includes(term));

    renderProducts(filtered);
  }

  input.addEventListener('input', runSearch);

  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      runSearch();
    }
  });

  console.log('✅ Shop search initialised');
}

function initProductPageQty(product) {
  const scope = document.querySelector('#product_display');
  if (!scope) return () => 1;

  const box = scope.querySelector('.qty-input');
  if (!box) return () => 1;

  const input = box.querySelector('.product-qty');
  const minusBtn = box.querySelector('.qty-count--minus');
  const addBtn = box.querySelector('.qty-count--add');

  if (!input || !minusBtn || !addBtn) return () => 1;

  // ✅ block typing (even if readonly is removed later)
  input.setAttribute('readonly', 'readonly');
  input.addEventListener('keydown', (e) => e.preventDefault());
  input.addEventListener('paste', (e) => e.preventDefault());

  const stock = Number(product?.stock ?? 0);
  const min = 1;
  const max = stock > 0 ? Math.min(stock, 10) : 1;

  function setQty(next) {
    let qty = parseInt(next, 10);
    if (!Number.isFinite(qty)) qty = min;

    qty = Math.max(min, Math.min(max, qty));
    input.value = String(qty);

    minusBtn.disabled = qty <= min;
    addBtn.disabled = qty >= max;
  }

  minusBtn.onclick = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setQty(parseInt(input.value, 10) - 1);
  };

  addBtn.onclick = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setQty(parseInt(input.value, 10) + 1);
  };

  setQty(1);

  // return a getter for the current qty
  return () => parseInt(input.value, 10) || 1;
}

// ===============================
// Load Products
// ===============================
if (container || container2) {
  fetch('/products', { credentials: 'include' })
    .then((res) => {
      if (!res.ok) throw new Error('Failed to load products from backend');
      return res.json();
    })
    .then((data) => {
      const products = Array.isArray(data) ? data : data.products || [];
      window.allProducts = products;
      localStorage.setItem('products', JSON.stringify(products));

      // ---------------------------
      // Product page (single item)
      // - description SHOULD show here
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
          if (descEl) descEl.textContent = product.description || '';
          if (priceEl) priceEl.textContent = `£${product.price}`;



          const button = container2.querySelector('.add_to_basket');
          const getQty = initProductPageQty(product); 

          if (button) {
            const stock = Number(product.stock ?? 0);

            if (stock <= 0) {
              button.textContent = 'Out of Stock';
              button.disabled = true;
              button.style.cursor = 'not-allowed';
              button.style.opacity = '0.6';
            } else {
              button.textContent = 'Add to Basket';
              button.disabled = false;
              button.style.cursor = 'pointer';
              button.style.opacity = '1';

              button.onclick = () => {
                const qty = getQty();              
                window.AddToBasket(product.id, qty); 
              };
            }
          }

          
          document.title = product.name;
        }

        loadCartFromBackend();
        return;
      }

      // ---------------------------
      // Category filtering (Shop All)
      // ---------------------------
      let currentCategory = [];

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

      window.currentCategoryProducts = currentCategory;
      window.visibleBaseProducts = currentCategory;

      // Render initial list
      if (container && ProductCard_template) {
        renderProducts(currentCategory);
      }

      // Init search AFTER we have products + base list
      initShopSearch();

      // Cart
      loadCartFromBackend();
    })
    .catch((err) => console.error('Error loading products:', err));
}













// ===============================
// Reviews (unchanged)
// ===============================
const reviews_button = document.getElementById('reviews_button');
const reviewsContainer = document.getElementById('reviews');
const template_review = document.getElementById('template_review');

function addReview(review) {
  if (!template_review || !reviewsContainer) return;

  const clone = template_review.content.cloneNode(true);
  clone.querySelector('.reviewer_name').textContent = review.name;
  clone.querySelector('.review_date').textContent = review.date;
  clone.querySelector('.review_rating').textContent = 'Rating: ' + review.rating + '/5';
  clone.querySelector('.review_comment').textContent = review.comment;
  reviewsContainer.appendChild(clone);
}

// ===============================
// Initial cart render for ALL pages
// ===============================
function initCartUI(retry = 0) {
  const maxRetries = 20;

  const basketEl = document.querySelector('#basket_display');
  const templateEl = document.querySelector('.basket_template');

  if (basketEl && templateEl) {
    loadCartFromBackend();
  } else if (retry < maxRetries) {
    setTimeout(() => initCartUI(retry + 1), 200);
  } else {
    console.warn('Basket UI not found after waiting, giving up.');
  }
}

initCartUI();


window.UpdateCartQty = async function (productId, qty) {
  try {
    const res = await fetch(`/cart/update-json/${productId}?quantity=${qty}`, {
      method: 'GET',
      headers: { Accept: 'application/json' },
      credentials: 'include',
    });

    if (!res.ok) {
      const text = await res.text();
      console.error('UpdateCartQty failed:', res.status, text);
      alert('Could not update cart.');
      return;
    }

    await loadCartFromBackend();
  } catch (e) {
    console.error(e);
    alert('Could not update cart.');
  }
};

window.RemoveFromCart = async function (productId) {
  try {
    const res = await fetch(`/cart/remove-json/${productId}`, {
      method: 'GET',
      headers: { Accept: 'application/json' },
      credentials: 'include',
    });

    if (!res.ok) {
      const text = await res.text();
      console.error('RemoveFromCart failed:', res.status, text);
      alert('Could not remove item from cart.');
      return;
    }

    await loadCartFromBackend();
  } catch (e) {
    console.error(e);
    alert('Could not remove item from cart.');
  }
};


