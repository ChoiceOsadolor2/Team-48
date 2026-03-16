// ===============================
// URL Params
// ===============================
const query = window.location.search;
const parameters = new URLSearchParams(query);

const productid = parameters.get('id');
const category = parameters.get('category');
const searchQ = parameters.get('q');


// ===============================
// DOM
// ===============================
const container = document.getElementById('products_container');
const ProductCard_template = container?.querySelector('template') || null;
const container2 = document.getElementById('product_display');
const productReviewsSection = document.getElementById('product_reviews_section');
const productReviewsList = document.getElementById('product_reviews_list');
const productReviewsEmpty = document.getElementById('product_reviews_empty');
const minPriceInput = document.getElementById('filter_min_price');
const maxPriceInput = document.getElementById('filter_max_price');
const minPriceRange = document.getElementById('filter_min_price_range');
const maxPriceRange = document.getElementById('filter_max_price_range');
const minPriceValue = document.getElementById('filter_min_price_value');
const maxPriceValue = document.getElementById('filter_max_price_value');
const priceRangeFill = document.getElementById('shop_price_range_fill');
const categorySelect = document.getElementById('filter_category');
const availabilitySelect = document.getElementById('filter_availability');
const sortSelect = document.getElementById('sort_products');
const applyFiltersButton = document.getElementById('apply_filters');
const clearFiltersButton = document.getElementById('clear_filters');
const toggleFiltersButton = document.getElementById('toggle_filters');
const resultsSummary = document.getElementById('results_summary');
const emptyProductsState = document.getElementById('empty_products_state');
const shopToolbar = document.querySelector('.shop-toolbar');
const PRICE_RANGE_MIN = 0;
let currentPriceRangeMax = 10000;
let wishlistLoaded = false;
let wishlistAuthenticated = false;
let wishlistLoadPromise = null;
const wishlistProductIds = new Set();

function ensureInlineNotice(id, parent, className = '') {
  if (!parent) return null;

  let notice = document.getElementById(id);
  if (!notice) {
    notice = document.createElement('div');
    notice.id = id;
    if (className) notice.className = className;
    parent.prepend(notice);
  }

  return notice;
}

function setProductsPageError(message) {
  const loadingEl = document.getElementById('loading_products');
  if (!loadingEl) return;

  loadingEl.style.display = 'block';
  loadingEl.textContent = message;
  loadingEl.style.color = '#ffa825';
}

function clearProductsPageError() {
  const loadingEl = document.getElementById('loading_products');
  if (!loadingEl) return;

  loadingEl.textContent = 'Loading products...';
  loadingEl.style.color = '';
}

function showBasketError(message, options = {}) {
  const basketError = document.getElementById('basket_stock_error');
  const normalizedMessage = message || '';

  if (!basketError) return;

  basketError.textContent = normalizedMessage;
  basketError.style.display = normalizedMessage ? 'block' : 'none';

  if (normalizedMessage && options.toast && typeof window.showSiteToast === 'function') {
    window.showSiteToast(options.type || 'error', normalizedMessage, options.toastOptions || {});
  }
}

function showProductPageError(message) {
  if (!container2) return;

  const notice = ensureInlineNotice('product_page_error', container2, 'product-page-error');
  if (!notice) return;

  notice.textContent = message;
  notice.style.color = '#ffa825';
  notice.style.textAlign = 'center';
  notice.style.margin = '0 auto 20px';
  notice.style.maxWidth = '820px';
}

function setWishlistButtonState(button, isWishlisted, labels = {}) {
  if (!button) return;

  const idleLabel = labels.idle || 'Add to Wishlist';
  const activeLabel = labels.active || 'Wishlisted';

  button.classList.toggle('is-active', Boolean(isWishlisted));
  button.dataset.wishlisted = isWishlisted ? '1' : '0';
  button.setAttribute('aria-pressed', isWishlisted ? 'true' : 'false');
  button.textContent = isWishlisted ? activeLabel : idleLabel;
}

function refreshWishlistButtons(scope = document) {
  scope.querySelectorAll('[data-wishlist-product-id]').forEach((button) => {
    const productId = Number(button.dataset.wishlistProductId || 0);
    if (!productId) return;

    setWishlistButtonState(button, wishlistProductIds.has(productId), {
      idle: button.dataset.idleLabel || 'Add to Wishlist',
      active: button.dataset.activeLabel || 'Wishlisted',
    });
  });
}

async function loadWishlist(force = false) {
  if (wishlistLoaded && !force) {
    return wishlistProductIds;
  }

  if (wishlistLoadPromise && !force) {
    return wishlistLoadPromise;
  }

  wishlistLoadPromise = fetch('/wishlist/json', {
    credentials: 'include',
    headers: {
      Accept: 'application/json',
    },
  })
    .then(async (response) => {
      if (!response.ok) {
        throw new Error('Failed to load wishlist');
      }

      const data = await response.json();
      wishlistProductIds.clear();
      wishlistAuthenticated = Boolean(data?.authenticated);

      (Array.isArray(data?.product_ids) ? data.product_ids : []).forEach((id) => {
        wishlistProductIds.add(Number(id));
      });

      wishlistLoaded = true;
      refreshWishlistButtons();
      return wishlistProductIds;
    })
    .catch((error) => {
      console.error('Error loading wishlist:', error);
      wishlistLoaded = false;
      return wishlistProductIds;
    })
    .finally(() => {
      wishlistLoadPromise = null;
    });

  return wishlistLoadPromise;
}

function setProductLoadingState(isLoading) {
  const loadingState = document.getElementById('product_loading_state');
  const extraPanels = document.getElementById('product_extra_panels');
  if (loadingState) {
    loadingState.hidden = !isLoading;
  }

  if (container2) {
    container2.classList.toggle('is-loading', isLoading);
    container2.classList.toggle('is-ready', !isLoading);
  }

  if (extraPanels) {
    extraPanels.classList.toggle('is-ready', !isLoading);
  }
}

function getDeliveryEstimate(product) {
  const stock = Number(product?.stock ?? 0);
  if (stock <= 0) return 'Restock update pending';
  if (stock <= 3) return 'Dispatch in 1-2 days';
  return 'Dispatches within 24 hours';
}

function getPlatformStockMap(product) {
  const rawMap = product?.platform_stock_map;
  if (!rawMap || typeof rawMap !== 'object') return {};

  return Object.fromEntries(
    Object.entries(rawMap).map(([platform, stock]) => [String(platform), Number(stock ?? 0)])
  );
}

function getProductStockForPlatform(product, platform = '') {
  const stockMap = getPlatformStockMap(product);
  const selectedPlatform = String(platform || '').trim();

  if (selectedPlatform && Object.prototype.hasOwnProperty.call(stockMap, selectedPlatform)) {
    return Number(stockMap[selectedPlatform] ?? 0);
  }

  if (selectedPlatform && Object.keys(stockMap).length) {
    return 0;
  }

  return Number(product?.stock ?? 0);
}

function getPriceTier(product) {
  const price = Number(product?.price ?? 0);
  if (price >= 500) return 'Premium';
  if (price >= 150) return 'Mid-range';
  return 'Everyday pick';
}

function updateProductDetailView(product, allProducts, selectedPlatform = '') {
  if (!container2 || !product) return;

  const img = container2.querySelector('.product_image');
  const nameEl = container2.querySelector('.product_name');
  const brandEl = container2.querySelector('#product_brand');
  const descEl = container2.querySelector('.product_description');
  const priceEl = container2.querySelector('.product_price');
  const categoryBadge = document.getElementById('product_category_badge');
  const platformBadge = document.getElementById('product_platform_badge');
  const stockBadge = document.getElementById('product_stock_badge');
  const availabilityText = document.getElementById('product_availability_text');
  const deliveryText = document.getElementById('product_delivery');
  const detailCategory = document.getElementById('product_detail_category');
  const detailPlatform = document.getElementById('product_detail_platform');
  const detailStock = document.getElementById('product_detail_stock');
  const detailDispatch = document.getElementById('product_detail_dispatch');
  const detailSupport = document.getElementById('product_detail_support');
  const detailPriceTier = document.getElementById('product_detail_price_tier');
  const detailReturns = document.getElementById('product_detail_returns');
  const detailSku = document.getElementById('product_detail_sku');

  setProductImage(img, product);
  if (nameEl) nameEl.textContent = product.name;
  if (brandEl) brandEl.textContent = product.brand || '';
  if (descEl) descEl.textContent = product.description || 'No product description is available yet.';
  if (priceEl) priceEl.textContent = `${product.price} GBP`;

  if (categoryBadge) {
    const categoryName = product.category?.name || 'General';
    categoryBadge.hidden = false;
    categoryBadge.textContent = categoryName;
  }

  if (platformBadge) {
    if (product.platform) {
      platformBadge.hidden = false;
      platformBadge.textContent = product.platform;
    } else {
      platformBadge.hidden = true;
    }
  }

  const resolvedPlatform = selectedPlatform || parseProductPlatforms(product)[0] || 'Universal';
  const stock = getProductStockForPlatform(product, resolvedPlatform);
  const stockMessage = stock <= 0 ? 'Out of stock' : stock <= 3 ? `Low stock: ${stock} left` : `In stock: ${stock} left`;
  if (stockBadge) {
    stockBadge.textContent = stockMessage;
    stockBadge.classList.toggle('is-low-stock', stock > 0 && stock <= 3);
    stockBadge.classList.toggle('is-out', stock <= 0);
  }

  if (availabilityText) {
    availabilityText.textContent = stock <= 0 ? 'Currently unavailable' : `${stock} ready to order`;
  }

  const deliveryEstimate = getDeliveryEstimate({ ...product, stock });
  if (deliveryText) deliveryText.textContent = deliveryEstimate;
  if (detailCategory) detailCategory.textContent = product.category?.name || 'General';
  if (detailPlatform) detailPlatform.textContent = resolvedPlatform;
  if (detailStock) detailStock.textContent = stock <= 0 ? 'Unavailable' : `${stock} units`;
  if (detailDispatch) detailDispatch.textContent = deliveryEstimate;
  if (detailSupport) detailSupport.textContent = 'Help available 7 days a week';
  if (detailPriceTier) detailPriceTier.textContent = getPriceTier(product);
  if (detailReturns) detailReturns.textContent = '14-day returns support';
  if (detailSku) detailSku.textContent = `VEL-${String(product.id).padStart(4, '0')}`;

  renderRelatedProducts(product, allProducts || []);
}

function renderRelatedProducts(currentProduct, products) {
  const relatedContainer = document.getElementById('related_products');
  const emptyState = document.getElementById('related_products_empty');
  if (!relatedContainer || !currentProduct) return;

  relatedContainer.innerHTML = '';
  const fragment = document.createDocumentFragment();

  const currentCategoryName = currentProduct.category?.name || '';
  const related = products
    .filter((item) => String(item.id) !== String(currentProduct.id))
    .filter((item) => item.category?.name === currentCategoryName)
    .slice(0, 3);

  if (!related.length) {
    if (emptyState) emptyState.hidden = false;
    return;
  }

  if (emptyState) emptyState.hidden = true;

  related.forEach((product) => {
    const reviewSummary = getProductReviewSummary(product);
    const card = document.createElement('article');
    card.className = 'product_card related-product-card';

    const image = document.createElement('img');
    image.className = 'product_image';
    setProductImage(image, product);
    image.alt = `${product.name} image`;

    const title = document.createElement('h3');
    title.className = 'product_name';
    title.textContent = product.name;

    const price = document.createElement('p');
    price.className = 'product_price';
    price.textContent = `${product.price} GBP`;

    const link = document.createElement('a');
    link.className = 'view_product';
    link.href = `ProductPage.html?id=${product.id}`;
    link.textContent = 'View Product';

    const reviewWrap = document.createElement('div');
    reviewWrap.className = 'product_review_summary';

    const reviewStars = document.createElement('span');
    reviewStars.className = 'product_review_stars';
    if (reviewSummary.isEmpty) {
      reviewStars.classList.add('is-empty');
    }
    reviewStars.textContent = reviewSummary.text;

    reviewWrap.appendChild(reviewStars);

    card.appendChild(image);
    card.appendChild(title);
    card.appendChild(price);
    card.appendChild(link);
    card.appendChild(reviewWrap);
    fragment.appendChild(card);
  });

  relatedContainer.appendChild(fragment);
}

function renderProductReviews(reviews = []) {
  if (!productReviewsSection || !productReviewsList || !productReviewsEmpty) return;

  productReviewsSection.hidden = false;
  productReviewsList.innerHTML = '';

  if (!reviews.length) {
    productReviewsEmpty.hidden = false;
    productReviewsEmpty.textContent = 'No reviews yet';
    return;
  }

  productReviewsEmpty.hidden = true;

  const fragment = document.createDocumentFragment();

  reviews.forEach((review) => {
    const card = document.createElement('article');
    card.className = 'product-review-card';

    const top = document.createElement('div');
    top.className = 'product-review-top';

    const author = document.createElement('span');
    author.className = 'product-review-author';
    author.textContent = review.user_name || 'Veltrix customer';

    const platform = document.createElement('span');
    platform.className = 'product-review-platform';
    platform.textContent = review.platform || 'Universal';

    const date = document.createElement('span');
    date.className = 'product-review-date';
    date.textContent = review.created_at || '';

    top.appendChild(author);
    top.appendChild(platform);
    if (date.textContent) top.appendChild(date);

    const rating = document.createElement('div');
    rating.className = 'product-review-rating';
    const filledStars = '★'.repeat(Math.max(0, Math.min(5, Number(review.rating || 0))));
    const emptyStars = '☆'.repeat(Math.max(0, 5 - Number(review.rating || 0)));
    rating.textContent = `${filledStars}${emptyStars}`;

    const title = document.createElement('h3');
    title.className = 'product-review-title';
    title.textContent = review.title || 'Review';

    const message = document.createElement('p');
    message.className = 'product-review-message';
    message.textContent = review.message || '';

    card.appendChild(top);
    card.appendChild(rating);
    card.appendChild(title);
    card.appendChild(message);
    fragment.appendChild(card);
  });

  productReviewsList.appendChild(fragment);
}

async function loadProductReviews(productId) {
  if (!productReviewsSection || !productReviewsList || !productReviewsEmpty || !productId) return;

  productReviewsSection.hidden = false;
  productReviewsEmpty.hidden = false;
  productReviewsEmpty.textContent = 'Loading reviews...';
  productReviewsList.innerHTML = '';

  try {
    const response = await fetch(`/products/id/${encodeURIComponent(productId)}/reviews`, {
      credentials: 'include',
    });

    if (!response.ok) {
      throw new Error('Failed to load product reviews');
    }

    const data = await response.json();
    renderProductReviews(Array.isArray(data?.reviews) ? data.reviews : []);
  } catch (error) {
    console.error('Error loading product reviews:', error);
    productReviewsList.innerHTML = '';
    productReviewsEmpty.hidden = false;
    productReviewsEmpty.textContent = 'Unable to load reviews right now';
  }
}

function isShopAllPage() {
  return Boolean(container && !container2);
}

function updatePriceRangeBounds(products = [], explicitMax = null) {
  if (!minPriceRange || !maxPriceRange) return;

  const prices = products
    .map((product) => Number(product?.price ?? 0))
    .filter((price) => Number.isFinite(price) && price >= 0);

  const highestFilteredPrice = prices.length ? Math.ceil(Math.max(...prices)) : 0;
  const highestPrice = Number.isFinite(Number(explicitMax))
    ? Math.ceil(Number(explicitMax))
    : highestFilteredPrice;

  currentPriceRangeMax = Math.max(PRICE_RANGE_MIN, highestPrice);

  minPriceRange.max = String(currentPriceRangeMax);
  maxPriceRange.max = String(currentPriceRangeMax);

  if (Number(minPriceRange.value || 0) > currentPriceRangeMax) {
    minPriceRange.value = String(currentPriceRangeMax);
  }
  if (Number(maxPriceRange.value || 0) > currentPriceRangeMax || !maxPriceRange.value) {
    maxPriceRange.value = String(currentPriceRangeMax);
  }
}

function syncPriceRangeUI() {
  if (!minPriceRange || !maxPriceRange || !minPriceInput || !maxPriceInput) return;

  let min = Number(minPriceRange.value || PRICE_RANGE_MIN);
  let max = Number(maxPriceRange.value || currentPriceRangeMax);

  min = Math.max(PRICE_RANGE_MIN, Math.min(min, currentPriceRangeMax));
  max = Math.max(PRICE_RANGE_MIN, Math.min(max, currentPriceRangeMax));
  minPriceRange.value = String(min);
  maxPriceRange.value = String(max);

  if (min > max) {
    if (document.activeElement === minPriceRange) {
      max = min;
      maxPriceRange.value = String(max);
    } else {
      min = max;
      minPriceRange.value = String(min);
    }
  }

  minPriceInput.value = min <= PRICE_RANGE_MIN ? '' : String(min);
  maxPriceInput.value = max >= currentPriceRangeMax ? '' : String(max);

  if (minPriceValue) minPriceValue.textContent = String(min);
  if (maxPriceValue) maxPriceValue.textContent = String(max);

  if (priceRangeFill) {
    const span = Math.max(1, currentPriceRangeMax - PRICE_RANGE_MIN);
    const left = ((min - PRICE_RANGE_MIN) / span) * 100;
    const right = ((max - PRICE_RANGE_MIN) / span) * 100;
    priceRangeFill.style.left = `${left}%`;
    priceRangeFill.style.width = `${Math.max(0, right - left)}%`;
  }
}

function syncShopControlsFromQuery() {
  if (!isShopAllPage()) return;

  if (minPriceInput) minPriceInput.value = parameters.get('min_price') || '';
  if (maxPriceInput) maxPriceInput.value = parameters.get('max_price') || '';
  if (minPriceRange) minPriceRange.value = parameters.get('min_price') || String(PRICE_RANGE_MIN);
  if (maxPriceRange) maxPriceRange.value = parameters.get('max_price') || String(currentPriceRangeMax);
  if (categorySelect) categorySelect.value = parameters.get('category') || '';
  if (availabilitySelect) availabilitySelect.value = parameters.get('availability') || '';
  if (sortSelect) sortSelect.value = parameters.get('sort') || 'default';
  syncPriceRangeUI();

  const categoryLabel = document.querySelector('.custom-select--category .val');
  const selectedCategoryOption = categorySelect?.selectedOptions?.[0];
  if (categoryLabel && selectedCategoryOption) {
    categoryLabel.textContent = selectedCategoryOption.textContent;
  }

  const sortLabel = document.querySelector('.shop-filter-field--sort .custom-select .val');
  const selectedSortOption = sortSelect?.selectedOptions?.[0];
  if (sortLabel && selectedSortOption) {
    sortLabel.textContent = selectedSortOption.textContent;
  }

  const availabilityLabel = document.querySelector('.custom-select--availability .val');
  const selectedAvailabilityOption = availabilitySelect?.selectedOptions?.[0];
  if (availabilityLabel && selectedAvailabilityOption) {
    availabilityLabel.textContent = selectedAvailabilityOption.textContent;
  }
}

function buildShopRequestUrl() {
  const requestParams = new URLSearchParams();

  ['category', 'q', 'availability', 'min_price', 'max_price', 'sort'].forEach((key) => {
    const value = parameters.get(key);
    if (value) requestParams.set(key, value);
  });

  const queryString = requestParams.toString();
  return queryString ? `/products?${queryString}` : '/products';
}

function applyShopFilters() {
  if (!isShopAllPage()) return;

  const nextParams = new URLSearchParams(window.location.search);

  const setOrDelete = (key, value) => {
    const normalized = String(value || '').trim();
    if (normalized) {
      nextParams.set(key, normalized);
    } else {
      nextParams.delete(key);
    }
  };

  setOrDelete('category', categorySelect?.value);
  setOrDelete('min_price', minPriceInput?.value);
  setOrDelete('max_price', maxPriceInput?.value);
  setOrDelete('availability', availabilitySelect?.value);
  setOrDelete('sort', sortSelect?.value === 'default' ? '' : sortSelect?.value);

  const nextQuery = nextParams.toString();
  const nextUrl = `${window.location.pathname}${nextQuery ? `?${nextQuery}` : ''}`;
  window.location.href = nextUrl;
}

function clearShopFilters() {
  if (!isShopAllPage()) return;

  const nextParams = new URLSearchParams(window.location.search);
  ['category', 'min_price', 'max_price', 'availability', 'sort'].forEach((key) => nextParams.delete(key));

  const nextQuery = nextParams.toString();
  const nextUrl = `${window.location.pathname}${nextQuery ? `?${nextQuery}` : ''}`;
  window.location.href = nextUrl;
}

function updateShopTitle(products) {
  const titleEl = document.getElementById('title');
  if (!titleEl) return;

  if (searchQ) {
    titleEl.textContent = `Results for "${searchQ}"`;
    return;
  }

  if (category) {
    titleEl.textContent = category;
    return;
  }

  titleEl.textContent = products.length ? 'All Products' : 'Shop All';
}

function updateResultsSummary(products) {
  if (!resultsSummary) return;

  const summaryParts = [`${products.length} product${products.length === 1 ? '' : 's'}`];

  if (category) summaryParts.push(category);
  if (parameters.get('availability') === 'in_stock') summaryParts.push('in stock only');
  if (parameters.get('availability') === 'out_of_stock') summaryParts.push('out of stock only');
  if (parameters.get('min_price')) summaryParts.push(`from £${parameters.get('min_price')}`);
  if (parameters.get('max_price')) summaryParts.push(`up to £${parameters.get('max_price')}`);

  resultsSummary.textContent = summaryParts.join(' • ');
}

function toggleEmptyProductsState(products) {
  if (!emptyProductsState) return;
  emptyProductsState.hidden = products.length > 0;
}

function bindShopFilterControls() {
  if (!isShopAllPage()) return;

  syncShopControlsFromQuery();

  if (toggleFiltersButton && shopToolbar && toggleFiltersButton.dataset.bound !== '1') {
    toggleFiltersButton.dataset.bound = '1';
    toggleFiltersButton.addEventListener('click', () => {
      const willHide = !shopToolbar.classList.contains('is-hidden');
      shopToolbar.classList.toggle('is-hidden', willHide);
      toggleFiltersButton.textContent = willHide ? 'Show filters' : 'Hide filters';
    });
  }

  if (applyFiltersButton && applyFiltersButton.dataset.bound !== '1') {
    applyFiltersButton.dataset.bound = '1';
    applyFiltersButton.addEventListener('click', applyShopFilters);
  }

  if (clearFiltersButton && clearFiltersButton.dataset.bound !== '1') {
    clearFiltersButton.dataset.bound = '1';
    clearFiltersButton.addEventListener('click', clearShopFilters);
  }

  [minPriceInput, maxPriceInput].forEach((input) => {
    if (!input || input.dataset.bound === '1') return;
    input.dataset.bound = '1';
    input.addEventListener('keydown', (event) => {
      if (event.key === 'Enter') {
        event.preventDefault();
        applyShopFilters();
      }
    });
  });

  [minPriceRange, maxPriceRange].forEach((input) => {
    if (!input || input.dataset.bound === '1') return;
    input.dataset.bound = '1';
    input.addEventListener('input', syncPriceRangeUI);
    input.addEventListener('keydown', (event) => {
      if (event.key === 'Enter') {
        event.preventDefault();
        syncPriceRangeUI();
      }
    });
  });

  if (availabilitySelect && availabilitySelect.dataset.bound !== '1') {
    availabilitySelect.dataset.bound = '1';
  }

  if (sortSelect && sortSelect.dataset.bound !== '1') {
    sortSelect.dataset.bound = '1';
  }
}


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
  const immediateInput = findHeaderSearchInput();
  bind(immediateInput);

  // Observe DOM because header.js injects elements later
  const obs = new MutationObserver(() => {
    const input = findHeaderSearchInput();
    if (input) {
      bind(input);
      obs.disconnect();
    }
  });

  if (!immediateInput) {
    obs.observe(document.documentElement, { childList: true, subtree: true });
  }

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

  if (!imageUrl) return '';
  if (imageUrl.includes('example.com')) return '';

  if (imageUrl.startsWith('http://') || imageUrl.startsWith('https://')) {
    return imageUrl;
  }

  const clean = imageUrl.replace(/^\/+/, '');

  if (clean.startsWith('storage/')) {
    return '/' + clean;
  }

  return '/storage/' + clean;
}

const NO_IMAGE_SVG = encodeURIComponent(
  '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300">' +
  '<rect width="400" height="300" fill="transparent"/>' +
  '<text x="200" y="155" text-anchor="middle" fill="#8a8a8a" ' +
  'font-family="Arial, sans-serif" font-size="28">No image</text>' +
  '</svg>'
);
const NO_IMAGE_DATA_URI = `data:image/svg+xml;charset=UTF-8,${NO_IMAGE_SVG}`;

function setProductImage(imgEl, product) {
  if (!imgEl) return;
  const resolvedUrl = getImageUrl(product);
  const isProductHeroImage = imgEl.classList.contains('product_image') && Boolean(container2);

  imgEl.decoding = 'async';
  imgEl.loading = isProductHeroImage ? 'eager' : 'lazy';
  if (isProductHeroImage) {
    imgEl.fetchPriority = 'high';
  }

  // No image configured: show a simple "No image" placeholder.
  if (!resolvedUrl) {
    imgEl.onerror = null;
    imgEl.src = NO_IMAGE_DATA_URI;
    imgEl.alt = 'No image';
    return;
  }

  imgEl.onerror = null;
  imgEl.alt = product?.name ? `${product.name} image` : 'Product image';
  imgEl.src = resolvedUrl;
  imgEl.onerror = () => {
    imgEl.onerror = null;
    imgEl.src = NO_IMAGE_DATA_URI;
    imgEl.alt = 'No image';
  };
}

// ===============================
// Cart UI state
// ===============================
document.body.classList.remove('cart-ready');

function getCsrfToken() {
  const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  if (metaToken) return metaToken;

  const cookieMatch = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
  return cookieMatch ? decodeURIComponent(cookieMatch[1]) : '';
}

async function ensureCsrfToken() {
  const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  if (metaToken) return metaToken;

  try {
    const response = await fetch('/csrf-token', {
      method: 'GET',
      headers: {
        Accept: 'application/json',
      },
      credentials: 'include',
    });

    if (!response.ok) {
      return '';
    }

    const data = await response.json().catch(() => ({}));
    const refreshedToken = data?.token || getCsrfToken();

    if (refreshedToken) {
      let metaTag = document.querySelector('meta[name="csrf-token"]');
      if (!metaTag) {
        metaTag = document.createElement('meta');
        metaTag.setAttribute('name', 'csrf-token');
        document.head.appendChild(metaTag);
      }
      metaTag.setAttribute('content', refreshedToken);
    }

    return refreshedToken || getCsrfToken() || '';
  } catch (error) {
    console.error('Failed to refresh CSRF token:', error);
    return getCsrfToken() || '';
  }
}

window.ToggleWishlist = async function (productId, button = null) {
  await loadWishlist();

  if (!wishlistAuthenticated) {
    if (typeof window.showSiteToast === 'function') {
      window.showSiteToast('error', 'Please sign in to use your wishlist.');
    }
    return;
  }

  const numericProductId = Number(productId);
  const isWishlisted = wishlistProductIds.has(numericProductId);

  try {
    const csrfToken = await ensureCsrfToken();
    const response = await fetch(`/wishlist/${numericProductId}`, {
      method: isWishlisted ? 'DELETE' : 'POST',
      headers: {
        Accept: 'application/json',
        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
      },
      credentials: 'include',
    });

    if (!response.ok) {
      let data = null;
      try {
        data = await response.json();
      } catch (e) {}

      throw new Error(data?.message || 'Could not update wishlist.');
    }

    if (isWishlisted) {
      wishlistProductIds.delete(numericProductId);
      if (typeof window.showSiteToast === 'function') {
        window.showSiteToast('success', 'Removed from wishlist.');
      }
    } else {
      wishlistProductIds.add(numericProductId);
      if (typeof window.showSiteToast === 'function') {
        window.showSiteToast('success', 'Added to wishlist.');
      }
    }

    refreshWishlistButtons();

    if (button) {
      setWishlistButtonState(button, wishlistProductIds.has(numericProductId), {
        idle: button.dataset.idleLabel || 'Add to Wishlist',
        active: button.dataset.activeLabel || 'Wishlisted',
      });
    }
  } catch (error) {
    console.error('Error toggling wishlist:', error);
    if (typeof window.showSiteToast === 'function') {
      window.showSiteToast('error', error?.message || 'Could not update wishlist.');
    }
  }
}

function setBasketCheckoutState(hasItems) {
  const checkoutBtn = document.getElementById('basket_checkout_btn');
  if (!checkoutBtn) return;

  checkoutBtn.disabled = !hasItems;
  checkoutBtn.setAttribute('aria-disabled', hasItems ? 'false' : 'true');
}

async function loadCartFromBackend() {
  try {
    const res = await fetch('/cart/json', {
      headers: { Accept: 'application/json' },
      credentials: 'include',
    });

    if (!res.ok) {
      const text = await res.text();
      console.error('Cart JSON error body:', text);
      showBasketError('We could not load your cart right now.');
      return;
    }

    const text = await res.text();
    let data = {};
    try {
      data = JSON.parse(text);
    } catch (e) {
      console.error('Non-JSON cart response (probably HTML login page).');
      showBasketError('Your cart is unavailable right now. Please refresh or sign in again.');
      return;
    }

    showBasketError(data.message || '');

    let rawItems = data.items || [];

    const items = rawItems.map((it) => {
      if (it.product) {
        return {
          id: it.product.id,
          name: it.product.name,
          price: it.product.price,
          image_url: it.product.image_url,
          platform: it.platform || it.product.platform || 'Universal',
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
    const fragment = document.createDocumentFragment();

    const basketTemplate = document.querySelector('.basket_template');
    if (!basketTemplate) {
      console.warn('.basket_template not found in DOM');
      return;
    }

    items.forEach((item) => {
      const clone = basketTemplate.content.cloneNode(true);

      const imgEl = clone.querySelector('img');
      const nameEl = clone.querySelector('.basket_name');
      const platformEl = clone.querySelector('.basket_platform');
      const priceEl = clone.querySelector('.basket_price');
      const quantityTextEl = clone.querySelector('.basket_quantity_text');

      setProductImage(imgEl, item);
      if (imgEl && item?.id) {
        imgEl.style.cursor = 'pointer';
        imgEl.title = 'View product';
        imgEl.onclick = (e) => {
          e.preventDefault();
          e.stopPropagation();
          window.location.href = `/pages/ProductPage.html?id=${encodeURIComponent(item.id)}`;
        };
      }
      if (nameEl) nameEl.textContent = item.name;
      if (platformEl) {
        const platformText = (item.platform || '').trim();
        platformEl.textContent = platformText;
        platformEl.style.display = platformText ? '' : 'none';
      }
      if (priceEl) priceEl.textContent = `${item.price} GBP`;
      if (quantityTextEl) quantityTextEl.textContent = `${item.quantity}`;

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
        (quantityTextEl || priceEl || nameEl)?.after(warning);
      }

      const removeBtn = clone.querySelector('.remove_from_cart');
      if (removeBtn) {
        removeBtn.onclick = (e) => {
          e.preventDefault();
          e.stopPropagation();
          window.RemoveFromCart(item.id);
        };
      }

      fragment.appendChild(clone);
    });

    BasketContainer.appendChild(fragment);


    const count = items.reduce((acc, it) => acc + (it.quantity || 0), 0);
    const basketCountEls = document.getElementsByClassName('basket_count');
    for (let i = 0; i < basketCountEls.length; i++) {
      basketCountEls[i].textContent = `(${count})`;
    }

    setBasketCheckoutState(count > 0);

    const totalEl = document.getElementById('total');
    if (totalEl) {
      totalEl.textContent = `${Number(total || 0).toFixed(2)} GBP`;
    }

    document.body.classList.add('cart-ready');
  } catch (err) {
    console.error('Error loading cart:', err);
    showBasketError('We could not load your cart right now.');
    setBasketCheckoutState(false);
  }
}


// ===============================
// Add to basket
// ===============================
window.AddToBasket = async function (id, qty = 1, platform = '') {
  try {
    qty = parseInt(qty, 10);
    if (!Number.isFinite(qty) || qty < 1) qty = 1;

    const params = new URLSearchParams({ quantity: String(qty) });
    const selectedPlatform = String(platform || '').trim();
    if (selectedPlatform) {
      params.set('platform', selectedPlatform);
    }

    const url = `/cart/add-json/${id}`;
    const csrfToken = await ensureCsrfToken();
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
      },
      credentials: 'include',
      body: JSON.stringify({
        quantity: qty,
        ...(selectedPlatform ? { platform: selectedPlatform } : {}),
      }),
    });

    if (!res.ok) {
      let data = null;
      try {
        data = await res.json();
      } catch (e) {}

      console.error('AddToBasket failed:', data);
      showBasketError(data?.message || 'Could not add to cart.', { toast: true, type: 'error' });
      await loadCartFromBackend();
      return;
    }

    showBasketError('');
    if (typeof window.showSiteToast === 'function') {
      window.showSiteToast('success', 'Added item to cart.');
    }
    await loadCartFromBackend();
  } catch (err) {
    console.error('Error adding to cart:', err);
    showBasketError('Could not add to cart.', { toast: true, type: 'error' });
  }
};


window.UpdateCartQty = async function (productId, qty) {
  try {
    const csrfToken = await ensureCsrfToken();
    const res = await fetch(`/cart/update-json/${productId}`, {
      method: 'PATCH',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
      },
      credentials: 'include',
      body: JSON.stringify({ quantity: qty }),
    });

    if (!res.ok) {
      let data = null;
      try {
        data = await res.json();
      } catch (e) {}

      console.error('UpdateCartQty failed:', data);
      showBasketError(data?.message || 'Could not update cart.', { toast: true, type: 'error' });
      await loadCartFromBackend();
      return;
    }

    showBasketError('');
    if (typeof window.showSiteToast === 'function') {
      window.showSiteToast('success', 'Cart updated.');
    }
    await loadCartFromBackend();
  } catch (e) {
    console.error(e);
    showBasketError('Could not update cart.', { toast: true, type: 'error' });
  }
};






// Render Products (Shop All)
// - NO description on cards
// - View Product button does navigation
// - Image click DOES NOTHING

function getProductReviewSummary(product) {
  const reviewCount = Number(product.reviews_count ?? 0);
  const averageRating = Number(product.reviews_avg_rating ?? 0);

  if (!reviewCount || !Number.isFinite(averageRating)) {
    return {
      text: 'No reviews',
      isEmpty: true,
    };
  }

  const filledStars = Math.max(0, Math.min(5, Math.round(averageRating)));
  return {
    text: `${'★'.repeat(filledStars)}${'☆'.repeat(5 - filledStars)}`,
    isEmpty: false,
  };
}

function renderProducts(list) {
  if (!container || !ProductCard_template) return;

  container.querySelectorAll('.product_card').forEach((card) => card.remove());
  toggleEmptyProductsState(list);
  updateResultsSummary(list);
  const fragment = document.createDocumentFragment();

  list.forEach((product) => {
    const clone = ProductCard_template.content.cloneNode(true);

    const priceEl = clone.querySelector('.product_price');
    const nameEl = clone.querySelector('.product_name');
    const img = clone.querySelector('.product_image');
    const descEl = clone.querySelector('.product_description'); // in case template still has it

    if (priceEl) priceEl.textContent = `${product.price} GBP`;
    if (nameEl) nameEl.textContent = product.name;
    if (clone.firstElementChild) {
      clone.firstElementChild.dataset.name = product.name || '';
      clone.firstElementChild.dataset.price = product.price || '';
      clone.firstElementChild.dataset.category = product.category?.name || '';
      clone.firstElementChild.dataset.stock = product.stock || 0;
    }
    setProductImage(img, product);

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

    const wishlistBtn = clone.querySelector('.wishlist_product');
    if (wishlistBtn) {
      wishlistBtn.dataset.wishlistProductId = String(product.id);
      wishlistBtn.dataset.idleLabel = 'Add to Wishlist';
      wishlistBtn.dataset.activeLabel = 'Wishlisted';
      setWishlistButtonState(wishlistBtn, wishlistProductIds.has(Number(product.id)), {
        idle: 'Add to Wishlist',
        active: 'Wishlisted',
      });
      wishlistBtn.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        window.ToggleWishlist(product.id, wishlistBtn);
      });
    }

    const reviewSummaryEl = clone.querySelector('.product_review_stars');
    if (reviewSummaryEl) {
      const reviewSummary = getProductReviewSummary(product);
      reviewSummaryEl.textContent = reviewSummary.text;
      reviewSummaryEl.classList.toggle('is-empty', reviewSummary.isEmpty);
    }

    fragment.appendChild(clone);
  });

  container.appendChild(fragment);
}



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

  console.log(' Shop search initialised');
}

function initProductPageQty(initialStock = 0) {
  const scope = document.querySelector('#product_display');
  if (!scope) return () => 1;

  const box = scope.querySelector('.qty-input');
  if (!box) return () => 1;

  const input = box.querySelector('.product-qty');
  const minusBtn = box.querySelector('.qty-count--minus');
  const addBtn = box.querySelector('.qty-count--add');

  if (!input || !minusBtn || !addBtn) return () => 1;


  input.setAttribute('readonly', 'readonly');
  input.addEventListener('keydown', (e) => e.preventDefault());
  input.addEventListener('paste', (e) => e.preventDefault());

  let currentStock = Number(initialStock ?? 0);
  let outOfStock = currentStock <= 0;
  let min = outOfStock ? 0 : 1;
  let max = outOfStock ? 0 : Math.min(currentStock, 10);

  function setQty(next) {
    let qty = parseInt(next, 10);
    if (!Number.isFinite(qty)) qty = min;

    qty = Math.max(min, Math.min(max, qty));
    input.value = String(qty);

    if (outOfStock) {
      minusBtn.disabled = true;
      addBtn.disabled = true;
      return;
    }

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

  function setAvailableStock(nextStock) {
    currentStock = Number(nextStock ?? 0);
    outOfStock = currentStock <= 0;
    min = outOfStock ? 0 : 1;
    max = outOfStock ? 0 : Math.min(currentStock, 10);
    setQty(outOfStock ? 0 : parseInt(input.value, 10) || 1);
  }

  setAvailableStock(currentStock);

  return {
    getQty() {
      const qty = parseInt(input.value, 10);
      if (!Number.isFinite(qty)) return min;
      return Math.max(min, Math.min(max, qty));
    },
    setAvailableStock,
  };
}

function parseProductPlatforms(product) {
  const rawPlatform = String(product?.platform || '').trim();

  if (!rawPlatform) {
    return ['Universal'];
  }

  const platforms = rawPlatform
    .split(',')
    .map((platform) => platform.trim())
    .filter(Boolean);

  return platforms.length ? [...new Set(platforms)] : ['Universal'];
}

function initProductPagePlatformPicker(product, onChange) {
  const select = document.getElementById('product_platform_select');
  const picker = document.getElementById('product_platform_picker');
  const selectedDiv = picker?.querySelector('.product-platform-selected');
  const selectedValue = selectedDiv?.querySelector('.val');
  const itemsDiv = picker?.querySelector('.product-platform-items');
  if (!select || !picker || !selectedDiv || !selectedValue || !itemsDiv) return () => 'Universal';

  const platforms = parseProductPlatforms(product);
  select.innerHTML = '';
  itemsDiv.innerHTML = '';
  let glowSyncFrame = null;

  const stopGlowSync = () => {
    if (glowSyncFrame !== null) {
      cancelAnimationFrame(glowSyncFrame);
      glowSyncFrame = null;
    }
  };

  const syncGlow = () => {
    const glowStyles = window.getComputedStyle(selectedDiv, '::after');
    const borderColor = glowStyles.borderTopColor || glowStyles.borderColor;
    const boxShadow = glowStyles.boxShadow;

    if (borderColor) {
      picker.style.setProperty('--platform-glow-color', borderColor);
    }

    if (boxShadow && boxShadow !== 'none') {
      picker.style.setProperty('--platform-glow-shadow', boxShadow);
    }

    if (picker.classList.contains('is-open')) {
      glowSyncFrame = requestAnimationFrame(syncGlow);
    } else {
      glowSyncFrame = null;
    }
  };

  const startGlowSync = () => {
    if (glowSyncFrame === null) {
      syncGlow();
    }
  };

  const closePicker = () => {
    picker.classList.remove('is-open');
    itemsDiv.classList.add('product-platform-hide');
    stopGlowSync();
  };

  platforms.forEach((platform) => {
    const option = document.createElement('option');
    option.value = platform;
    option.textContent = platform;
    select.appendChild(option);

    const customOption = document.createElement('div');
    customOption.className = 'product-platform-option';
    customOption.textContent = platform;
    if (platform === platforms[0]) {
      customOption.classList.add('is-selected');
    }
    customOption.addEventListener('click', (event) => {
      event.stopPropagation();
      select.value = platform;
      selectedValue.textContent = platform;
      itemsDiv.querySelectorAll('.product-platform-option').forEach((item) => {
        item.classList.toggle('is-selected', item.textContent === platform);
      });
      if (typeof onChange === 'function') onChange(platform);
      closePicker();
    });
    itemsDiv.appendChild(customOption);
  });

  select.value = platforms[0];
  selectedValue.textContent = platforms[0];
  select.disabled = platforms.length <= 1;
  picker.classList.toggle('is-disabled', platforms.length <= 1);

  if (picker.dataset.bound !== '1') {
    picker.dataset.bound = '1';

    selectedDiv.addEventListener('click', (event) => {
      event.stopPropagation();
      if (picker.classList.contains('is-disabled')) return;

      const willOpen = itemsDiv.classList.contains('product-platform-hide');
      closePicker();
      if (willOpen) {
        picker.classList.add('is-open');
        itemsDiv.classList.remove('product-platform-hide');
        startGlowSync();
      }
    });

    document.addEventListener('click', () => {
      closePicker();
    });
  }

  if (typeof onChange === 'function') onChange(platforms[0]);

  return () => select.value || platforms[0] || 'Universal';
}


// Load Products

if (container || container2) {
  clearProductsPageError();
  bindShopFilterControls();
  setProductLoadingState(Boolean(container2));
  fetch(buildShopRequestUrl(), { credentials: 'include' })
    .then((res) => {
      if (!res.ok) throw new Error('Failed to load products from backend');
      return res.json();
    })
    .then((data) => {
      const products = Array.isArray(data) ? data : data.products || [];
      const shopMaxPrice = Array.isArray(data) ? null : data.shop_max_price;
      window.allProducts = products;
      localStorage.setItem('products', JSON.stringify(products));
      updatePriceRangeBounds(products, shopMaxPrice);
      syncShopControlsFromQuery();


      // Product page (single item)
      // - description SHOULD show here

      if (productid && container2) {
        const product = products.find((p) => String(p.id) === String(productid));
        console.log('Selected product:', product);

        if (product) {
          const button = container2.querySelector('.add_to_basket');
          const wishlistButton = container2.querySelector('.wishlist_toggle');
          const initialPlatform = parseProductPlatforms(product)[0] || 'Universal';
          const qtyController = initProductPageQty(getProductStockForPlatform(product, initialPlatform));

          const syncSelectedPlatform = (platform) => {
            const selectedPlatform = platform || initialPlatform;
            const stock = getProductStockForPlatform(product, selectedPlatform);

            updateProductDetailView(product, products, selectedPlatform);
            qtyController.setAvailableStock(stock);

            if (button) {
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
              }
            }
          };

          const getPlatform = initProductPagePlatformPicker(product, syncSelectedPlatform);

          if (button) {
            button.onclick = () => {
              const qty = qtyController.getQty();
              const platform = getPlatform();
              window.AddToBasket(product.id, qty, platform);
            };
          }

          if (wishlistButton) {
            wishlistButton.dataset.wishlistProductId = String(product.id);
            wishlistButton.dataset.idleLabel = 'Add to Wishlist';
            wishlistButton.dataset.activeLabel = 'Wishlisted';
            setWishlistButtonState(wishlistButton, wishlistProductIds.has(Number(product.id)), {
              idle: 'Add to Wishlist',
              active: 'Wishlisted',
            });
            wishlistButton.addEventListener('click', () => {
              window.ToggleWishlist(product.id, wishlistButton);
            });
            loadWishlist().then(() => {
              setWishlistButtonState(wishlistButton, wishlistProductIds.has(Number(product.id)), {
                idle: 'Add to Wishlist',
                active: 'Wishlisted',
              });
            });
          }

          syncSelectedPlatform(initialPlatform);


          document.title = product.name;
          loadProductReviews(product.id);
        }

        loadCartFromBackend();
        return;
      }

      updateShopTitle(products);
      window.currentCategoryProducts = products;
      window.visibleBaseProducts = products;
      renderProducts(products);
      loadWishlist();

      // Hide loading message after products are rendered
      const loadingEl = document.getElementById("loading_products");
      if (loadingEl) loadingEl.style.display = "none";

      // Cart
      loadCartFromBackend();

    })
    .catch((err) => {
      console.error('Error loading products:', err);
      if (container) {
        setProductsPageError('We could not load products right now. Please refresh and try again.');
      }
      if (container2) {
        showProductPageError('We could not load this product right now. Please go back and try again.');
        setProductLoadingState(false);
      }
    });
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
    const csrfToken = await ensureCsrfToken();
    const res = await fetch(`/cart/update-json/${productId}`, {
      method: 'PATCH',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
      },
      credentials: 'include',
      body: JSON.stringify({ quantity: qty }),
    });

    if (!res.ok) {
      const text = await res.text();
      console.error('UpdateCartQty failed:', res.status, text);
      showBasketError('Could not update cart.', { toast: true, type: 'error' });
      return;
    }

    showBasketError('');
    if (typeof window.showSiteToast === 'function') {
      window.showSiteToast('success', 'Cart updated.');
    }
    await loadCartFromBackend();
  } catch (e) {
    console.error(e);
    showBasketError('Could not update cart.', { toast: true, type: 'error' });
  }
};

window.RemoveFromCart = async function (productId) {
  try {
    const csrfToken = await ensureCsrfToken();
    const res = await fetch(`/cart/remove-json/${productId}`, {
      method: 'DELETE',
      headers: {
        Accept: 'application/json',
        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
      },
      credentials: 'include',
    });

    if (!res.ok) {
      const text = await res.text();
      console.error('RemoveFromCart failed:', res.status, text);
      showBasketError('Could not remove item from cart.', { toast: true, type: 'error' });
      return;
    }

    showBasketError('');
    if (typeof window.showSiteToast === 'function') {
      window.showSiteToast('success', 'Item removed from cart.');
    }
    await loadCartFromBackend();
  } catch (e) {
    console.error(e);
    showBasketError('Could not remove item from cart.', { toast: true, type: 'error' });
  }
};
