const headerFile = '/pages/header.html';
const footerFile = '/pages/footer.html';
const PAGE_CHROME_CACHE_TTL = 5 * 60 * 1000;
let userStatusPromise = null;

function readCachedPageChrome(cacheKey) {
  try {
    const raw = sessionStorage.getItem(cacheKey);
    if (!raw) return null;

    const parsed = JSON.parse(raw);
    if (!parsed?.html || !parsed?.savedAt) return null;
    if (Date.now() - parsed.savedAt > PAGE_CHROME_CACHE_TTL) return null;

    return parsed.html;
  } catch (_) {
    return null;
  }
}

function writeCachedPageChrome(cacheKey, html) {
  try {
    sessionStorage.setItem(cacheKey, JSON.stringify({ html, savedAt: Date.now() }));
  } catch (_) {
    // Ignore storage failures.
  }
}

async function fetchTextWithSessionCache(url, cacheKey) {
  const cached = readCachedPageChrome(cacheKey);
  if (cached) return cached;

  const response = await fetch(url, { credentials: 'same-origin' });
  if (!response.ok) throw new Error(`Failed to load ${url}`);

  const html = await response.text();
  writeCachedPageChrome(cacheKey, html);
  return html;
}

function getUserStatus() {
  if (!userStatusPromise) {
    userStatusPromise = fetch('/user-status', {
      headers: { Accept: 'application/json' },
      credentials: 'include'
    })
      .then(async (res) => {
        if (!res.ok) return null;
        try {
          return await res.json();
        } catch (_) {
          return null;
        }
      })
      .catch(() => null);
  }

  return userStatusPromise;
}

function initSiteToasts() {
  if (window.__siteToastInit) return;

  let toastRegion = document.getElementById('site-toast-region');
  if (!toastRegion) {
    toastRegion = document.createElement('div');
    toastRegion.id = 'site-toast-region';
    toastRegion.className = 'site-toast-region';
    toastRegion.setAttribute('aria-live', 'polite');
    toastRegion.setAttribute('aria-atomic', 'true');
    document.body.appendChild(toastRegion);
  }

  window.__siteToastInit = true;

  const TOAST_TITLES = {
    success: 'Success',
    error: 'Something went wrong',
    info: 'Notice'
  };

  const TOAST_ICONS = {
    success: 'OK',
    error: '!',
    info: 'i'
  };

  function closeToast(toast) {
    if (!toast || toast.dataset.closing === '1') return;

    toast.dataset.closing = '1';
    toast.classList.remove('is-visible');
    toast.classList.add('is-closing');
    window.setTimeout(() => toast.remove(), 220);
  }

  window.showSiteToast = function (type, message, options = {}) {
    const normalizedType = ['success', 'error', 'info'].includes(type) ? type : 'info';
    const normalizedMessage = String(message || '').trim();

    if (!normalizedMessage) return null;

    const toast = document.createElement('div');
    toast.className = `site-toast site-toast--${normalizedType}`;
    toast.setAttribute('role', normalizedType === 'error' ? 'alert' : 'status');

    const title = options.title || TOAST_TITLES[normalizedType];
    const icon = options.icon || TOAST_ICONS[normalizedType];

    toast.innerHTML = `
      <span class="site-toast-icon" aria-hidden="true">${icon}</span>
      <div class="site-toast-content">
        <span class="site-toast-title">${title}</span>
        <span class="site-toast-message"></span>
      </div>
      <button type="button" class="site-toast-close" aria-label="Dismiss notification">&times;</button>
    `;

    toast.querySelector('.site-toast-message').textContent = normalizedMessage;
    toast.querySelector('.site-toast-close').addEventListener('click', () => closeToast(toast));

    toastRegion.appendChild(toast);
    window.requestAnimationFrame(() => toast.classList.add('is-visible'));

    const duration = Number(options.duration || 5000);
    if (duration > 0) {
      window.setTimeout(() => closeToast(toast), duration);
    }

    return toast;
  };
}

function bindVeltrixHeader(headerEl) {
  if (!headerEl || headerEl.dataset.veltrixHeaderBound === '1') return;
  headerEl.dataset.veltrixHeaderBound = '1';

  // Extract fixed UI elements from header to prevent CSS flex/filter containing-block traps
  const chatbotUI = document.getElementById('vx-chatbot-container');
  if (chatbotUI) document.body.appendChild(chatbotUI);
  const scrollTopBtn = document.getElementById('vx-scroll-top');
  if (scrollTopBtn) document.body.appendChild(scrollTopBtn);

  fetchTextWithSessionCache(footerFile, 'veltrix:footer')
    .then(html => {
      const footerEl = document.querySelector('footer');
      if (footerEl) footerEl.innerHTML = html;
    })
    .catch(err => console.error('Footer load error:', err));

  (function bindVeltrixSearch() {
    const form =
      headerEl.querySelector('#vx-search-form') ||
      headerEl.querySelector('#global-search-form') ||
      headerEl.querySelector('#search-form') ||
      headerEl.querySelector('#search')?.querySelector('form');

    const input =
      headerEl.querySelector('#vx-search-input') ||
      headerEl.querySelector('#global-search-input') ||
      headerEl.querySelector('#global_search') ||
      headerEl.querySelector('#search-bar') ||
      form?.querySelector('input[type="search"]') ||
      form?.querySelector('input[type="text"]') ||
      headerEl.querySelector('#search input');

    if (!form || !input) return;
    if (form.dataset.boundSearch === '1') return;
    form.dataset.boundSearch = '1';

    function goToShopAll() {
      const q = (input.value || '').trim();
      if (!q) return;
      try { closeSection('search'); } catch (_) { }
      window.location.href = `/pages/ShopAll.html?q=${encodeURIComponent(q)}`;
    }

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      goToShopAll();
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        e.stopPropagation();
        if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
        goToShopAll();
      }
    }, true);

    let resultsContainer = headerEl.querySelector('#vx-search-results');
    if (!resultsContainer) {
      resultsContainer = document.createElement('ul');
      resultsContainer.id = 'vx-search-results';
      resultsContainer.className = 'vx-search-dropdown';
      resultsContainer.style.display = 'none';
      const searchRoot = headerEl.querySelector('#search');
      if (searchRoot) {
        searchRoot.appendChild(resultsContainer);
      }
    }
    let debounceTimer = null;
    let searchController = null;

    if (resultsContainer) {
      input.addEventListener('input', (e) => {
        const query = e.target.value.trim();

        if (!query) {
          resultsContainer.style.display = 'none';
          resultsContainer.innerHTML = '';
          if (searchController) searchController.abort();
          return;
        }

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
          if (searchController) searchController.abort();
          searchController = new AbortController();

          fetch(`/products/search-json?q=${encodeURIComponent(query)}`, {
            signal: searchController.signal,
            credentials: 'include'
          })
            .then(async res => {
              if (!res.ok) {
                throw new Error('Search request failed.');
              }

              return res.json();
            })
            .then(data => {
              resultsContainer.innerHTML = '';

              if (!data.success || !data.results || data.results.length === 0) {
                resultsContainer.innerHTML = '<li class="vx-search-dropdown-empty">No products found.</li>';
                resultsContainer.style.display = 'block';
                return;
              }

              const fragment = document.createDocumentFragment();

              data.results.slice(0, 5).forEach(product => {
                let imgUrl = '/assets/MainLogo.png';
                if (product.image_url) {
                  imgUrl = product.image_url.startsWith('http')
                    ? product.image_url
                    : '/storage/' + product.image_url.replace(/^\/+/, '');
                }

                const li = document.createElement('li');
                li.innerHTML = `
                  <img src="${imgUrl}" alt="${product.name}" class="vx-search-dropdown-img" onerror="this.src='/assets/MainLogo.png'" loading="lazy" decoding="async">
                  <div class="vx-search-dropdown-info">
                    <span class="vx-search-dropdown-title">${product.name}</span>
                    <span class="vx-search-dropdown-price">&#163;${Number(product.price).toFixed(2)}</span>
                  </div>
                `;
                li.addEventListener('click', () => {
                  window.location.href = `/pages/ProductPage.html?id=${product.id}`;
                });
                fragment.appendChild(li);
              });

              resultsContainer.appendChild(fragment);
              resultsContainer.style.display = 'flex';
              resultsContainer.style.flexDirection = 'column';
            })
            .catch(err => {
              if (err.name === 'AbortError') return;
              console.error('Live search error:', err);
              resultsContainer.innerHTML = '<li class="vx-search-dropdown-empty">Search is unavailable right now.</li>';
              resultsContainer.style.display = 'block';
            });
        }, 220);
      });

      document.addEventListener('click', (e) => {
        if (!form.contains(e.target) && !resultsContainer.contains(e.target)) {
          resultsContainer.style.display = 'none';
        }
      });

      input.addEventListener('focus', () => {
        if (input.value.trim() && resultsContainer.innerHTML !== '') {
          resultsContainer.style.display = 'flex';
        }
      });
    }
  })();

  const userMenuBtn = headerEl.querySelector('#userMenuBtn');
  const userMenuDropdown = headerEl.querySelector('#userMenuDropdown');
  const adminLink = headerEl.querySelector('#admin-link');
  const logoEl = headerEl.querySelector('#header_logo');

  if (logoEl) {
    const goHomeTop = () => {
      window.location.href = '/pages/index.html';
    };

    logoEl.style.cursor = 'pointer';
    logoEl.setAttribute('role', 'link');
    logoEl.setAttribute('tabindex', '0');
    logoEl.addEventListener('click', goHomeTop);
    logoEl.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        goHomeTop();
      }
    });
  }

  if (adminLink) adminLink.style.display = 'none';
  if (userMenuBtn && userMenuDropdown) {
    const closeMenu = () => {
      userMenuDropdown.classList.remove('open');
      userMenuBtn.setAttribute('aria-expanded', 'false');
    };

    userMenuBtn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const open = userMenuDropdown.classList.toggle('open');
      userMenuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    document.addEventListener('click', closeMenu);
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeMenu();
    });

    getUserStatus()
      .then(data => {
        if (data?.logged_in) {
          userMenuDropdown.innerHTML = `
            <a href="/orders" class="user-menu-item">Previous Orders</a>
            <a href="/profile" class="user-menu-item">Profile Info</a>
            <button type="button" class="user-menu-item danger" id="logoutBtn">
              Logout
            </button>
          `;

          if (adminLink && data.user?.role === 'admin') {
            adminLink.style.display = 'inline-block';
          }

          const logoutBtn = userMenuDropdown.querySelector('#logoutBtn');
          if (logoutBtn) {
            logoutBtn.onclick = async () => {
              try {
                await fetch('/logout-json', { credentials: 'include' });
              } catch (_) {}
              window.location.href = '/pages/login.html';
            };
          }
        } else {
          userMenuDropdown.innerHTML = `
            <a href="/pages/login.html" class="user-menu-item">Login</a>
            <a href="/pages/register.html" class="user-menu-item">Register</a>
          `;
        }
      })
      .catch(() => {
        userMenuDropdown.innerHTML = `
          <a href="/pages/login.html" class="user-menu-item">Login</a>
          <a href="/pages/register.html" class="user-menu-item">Register</a>
        `;
      });
  }

  initChatbot();
  initScrollTop();
  initSiteToasts();
}

const existingHeader = document.querySelector('header');

if (existingHeader && existingHeader.querySelector('#userMenuBtn')) {
  bindVeltrixHeader(existingHeader);
} else {
  fetchTextWithSessionCache(headerFile, 'veltrix:header')
    .then(html => {
      const headerEl = document.querySelector('header');
      if (!headerEl) return;
      headerEl.innerHTML = html;
      bindVeltrixHeader(headerEl);
    })
    .catch(err => console.error('Header load error:', err));
}

; (async function () {
  try {
    const data = await getUserStatus();
    if (!data) return;

    const remember = localStorage.getItem('rememberLogin') === '1';
    const temp = sessionStorage.getItem('tempLoggedIn') === '1';

    if (data.logged_in && !remember && !temp) {
      await fetch('/logout-json', {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
      });

      window.location.reload();
    }
  } catch (err) {
    console.error('Logout check error:', err);
  }
})();

/* =========================================
   SCROLL TO TOP LOGIC
   ========================================= */
function initScrollTop() {
  const scrollTopBtn = document.getElementById('vx-scroll-top');
  if (!scrollTopBtn) return;

  let ticking = false;

  function syncScrollTopVisibility() {
    if (window.scrollY > 300) {
      scrollTopBtn.classList.remove('hidden');
    } else {
      scrollTopBtn.classList.add('hidden');
    }
    ticking = false;
  }

  window.addEventListener('scroll', () => {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(syncScrollTopVisibility);
  }, { passive: true });

  syncScrollTopVisibility();

  // Smooth scroll to top on click
  scrollTopBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
}

/* =========================================
   AI CHATBOT LOGIC
   ========================================= */
function initChatbot() {
  const toggleBtn = document.getElementById('vx-chatbot-toggle');
  const closeBtn = document.getElementById('vx-chatbot-close');
  const chatWindow = document.getElementById('vx-chatbot-window');
  const chatForm = document.getElementById('vx-chat-form');
  const chatInput = document.getElementById('vx-chat-input');
  const chatMessages = document.getElementById('vx-chat-messages');

  if (!toggleBtn || !chatWindow) return;

  // Toggle Window
  const openChat = () => {
    chatWindow.classList.remove('hidden');
    chatInput.focus();
  };

  const closeChat = () => {
    chatWindow.classList.add('hidden');
  };

  toggleBtn.addEventListener('click', () => {
    if (chatWindow.classList.contains('hidden')) {
      openChat();
    } else {
      closeChat();
    }
  });

  closeBtn.addEventListener('click', closeChat);

  async function sendChatMessage(userMessage) {
    if (!userMessage) return;

    appendMessage('user', userMessage);
    chatInput.value = '';
    const typingIndicator = appendMessage('ai', '...');

    try {
      const response = await fetch('/chatbot/ask', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ message: userMessage })
      });

      const rawText = await response.text();
      let data = null;

      try {
        data = JSON.parse(rawText);
      } catch (parseError) {
        throw new Error('Chatbot returned an invalid response.');
      }

      if (response.ok && data.status === 'success') {
        renderAiResponse(typingIndicator, data.reply, data.suggestions || []);
      } else {
        typingIndicator.textContent = data?.message || data?.reply || "The chatbot couldn't answer right now. Please try again in a moment.";
      }
    } catch (error) {
      console.error('Chatbot Error:', error);
      typingIndicator.textContent = 'The chatbot could not reach the site backend. Make sure the Laravel server is running, then try again.';
    }
  }

  // Handle Form Submission
  chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const userMessage = chatInput.value.trim();
    await sendChatMessage(userMessage);
  });

  function appendMessage(senderType, text) {
    const msgDiv = document.createElement('div');
    msgDiv.classList.add('vx-message');
    msgDiv.classList.add(senderType === 'ai' ? 'ai-message' : 'user-message');
    msgDiv.textContent = text;
    chatMessages.appendChild(msgDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    return msgDiv;
  }

  function renderAiResponse(messageEl, text, suggestions) {
    messageEl.textContent = '';

    const body = document.createElement('div');
    body.className = 'vx-message-body';
    body.textContent = text;
    messageEl.appendChild(body);

    if (Array.isArray(suggestions) && suggestions.length) {
      const chips = document.createElement('div');
      chips.className = 'vx-chat-suggestions';

      suggestions.slice(0, 4).forEach((suggestion) => {
        const chip = document.createElement(suggestion.url ? 'a' : 'button');
        chip.className = 'vx-chat-suggestion';
        chip.textContent = suggestion.label || 'Open';

        if (suggestion.url) {
          chip.href = suggestion.url;
        } else {
          chip.type = 'button';
          chip.addEventListener('click', () => sendChatMessage(suggestion.message || suggestion.label || 'Help'));
        }

        chips.appendChild(chip);
      });

      messageEl.appendChild(chips);
    }

    chatMessages.scrollTop = chatMessages.scrollHeight;
  }
}

/* Close and open functions have been refactored to be more generic and reusable
Rule- As long as you pass in the ID of the panel to be opened or closed ,
its class list is toggled between 'open' and 'close'
These classLists can be found in Style.JS , Animations Sectio */

function openPanel(id) {
  const obj = document.getElementById(id);
  //toggle between open and close, with less code
  if (obj.classList.contains('open')) {
    obj.classList.remove('open');
    obj.classList.add('close');
  } else {
    obj.classList.add('open');
    obj.classList.remove('close');
  }
  syncCartScrollLock();
}

function closePanel(id) {
  const obj = document.getElementById(id);
  obj.classList.remove('open');
  obj.classList.add('close');
  syncCartScrollLock();
}

function syncCartScrollLock() {
  const basketEl = document.getElementById('basket');
  const isCartOpen = !!basketEl && basketEl.classList.contains('open');

  document.body.classList.toggle('cart-open', isCartOpen);
  document.documentElement.classList.toggle('cart-open', isCartOpen);
}

// Inline Header Search Toggle
window.toggleInlineSearch = function () {
  const headerEl = document.querySelector('header');

  if (headerEl) {
    headerEl.classList.toggle('search-active');
  }
};
