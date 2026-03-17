const wishlistGrid = document.getElementById('wishlist_grid');
const wishlistEmpty = document.getElementById('wishlist_empty');
const wishlistError = document.getElementById('wishlist_error');

function getCsrfToken() {
  const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  if (metaToken) return metaToken;

  const cookieMatch = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
  return cookieMatch ? decodeURIComponent(cookieMatch[1]) : '';
}

async function ensureCsrfToken() {
  const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  if (metaToken) return metaToken;

  const existing = getCsrfToken();
  if (existing) return existing;

  try {
    const response = await fetch('/csrf-token', {
      method: 'GET',
      headers: { Accept: 'application/json' },
      credentials: 'include',
    });

    if (!response.ok) {
      return getCsrfToken() || '';
    }

    const data = await response.json().catch(() => ({}));
    return data?.token || getCsrfToken() || '';
  } catch (error) {
    console.error('Failed to refresh CSRF token:', error);
    return getCsrfToken() || '';
  }
}

function getWishlistImageSrc(product) {
  if (!product?.image_url) {
    return '../assets/MainLogo.png';
  }

  if (String(product.image_url).startsWith('http')) {
    return product.image_url;
  }

  return `/storage/${String(product.image_url).replace(/^\/+/, '')}`;
}

function setWishlistState({ empty = false, error = '' } = {}) {
  if (wishlistEmpty) wishlistEmpty.hidden = !empty;
  if (wishlistError) {
    wishlistError.hidden = !error;
    wishlistError.textContent = error || '';
  }
}

function renderWishlistProducts(products = []) {
  if (!wishlistGrid) return;

  wishlistGrid.innerHTML = '';

  if (!products.length) {
    setWishlistState({ empty: true, error: '' });
    return;
  }

  setWishlistState({ empty: false, error: '' });

  const fragment = document.createDocumentFragment();

  products.forEach((product) => {
    const card = document.createElement('article');
    card.className = 'wishlist-card';

    card.innerHTML = `
      <img class="wishlist-image" src="${getWishlistImageSrc(product)}" alt="${product.name}">
      <h2 class="wishlist-name">${product.name}</h2>
      <p class="wishlist-price">${Number(product.price || 0).toFixed(2)} GBP</p>
      <div class="wishlist-actions">
        <a class="wishlist-button" href="/pages/ProductPage.html?id=${product.id}">View Product</a>
        <button type="button" class="wishlist-button" data-remove-id="${product.id}">Remove from Wishlist</button>
      </div>
    `;

    const removeButton = card.querySelector('[data-remove-id]');
    if (removeButton) {
      removeButton.addEventListener('click', async () => {
        try {
          const csrfToken = await ensureCsrfToken();
          const response = await fetch(`/wishlist/${product.id}`, {
            method: 'DELETE',
            headers: {
              Accept: 'application/json',
              ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            },
            credentials: 'include',
          });

          if (!response.ok) {
            throw new Error('Could not remove wishlist item.');
          }

          await loadWishlistPage();
        } catch (error) {
          console.error('Wishlist remove error:', error);
          setWishlistState({ empty: false, error: 'Could not remove wishlist item.' });
        }
      });
    }

    fragment.appendChild(card);
  });

  wishlistGrid.appendChild(fragment);
}

async function loadWishlistPage() {
  if (!wishlistGrid) return;

  try {
    const userStatus = await fetch('/user-status', {
      headers: { Accept: 'application/json' },
      credentials: 'include',
    }).then((response) => response.ok ? response.json() : null);

    if (!userStatus?.logged_in) {
      window.location.href = `/pages/login.html?redirect=${encodeURIComponent('/wishlist')}`;
      return;
    }

    const response = await fetch('/wishlist/products', {
      headers: { Accept: 'application/json' },
      credentials: 'include',
    });

    if (!response.ok) {
      throw new Error('Could not load wishlist.');
    }

    const data = await response.json();
    renderWishlistProducts(Array.isArray(data?.products) ? data.products : []);
  } catch (error) {
    console.error('Wishlist load error:', error);
    setWishlistState({ empty: false, error: 'We could not load your wishlist right now.' });
  }
}

loadWishlistPage();
