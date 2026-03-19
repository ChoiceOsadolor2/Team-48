const headerFile = '../pages/header.html';

fetch(headerFile)
  .then(response => response.text())
  .then(html => {
    const headerEl = document.querySelector('header');
    headerEl.innerHTML = html;

    // Extract chatbot from header to prevent CSS flex/filter containing-block traps
    // Extract fixed UI elements from header to prevent CSS flex/filter containing-block traps
    const chatbotUI = document.getElementById('vx-chatbot-container');
    if (chatbotUI) document.body.appendChild(chatbotUI);
    const scrollTopBtn = document.getElementById('vx-scroll-top');
    if (scrollTopBtn) document.body.appendChild(scrollTopBtn);

    const footerFile = '../pages/footer.html';
    fetch(footerFile)
      .then(response => response.text())
      .then(html => {
        const footerEl = document.querySelector('footer');
        if (footerEl) footerEl.innerHTML = html;
      });

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
        window.location.href = `ShopAll.html?q=${encodeURIComponent(q)}`;
      }

      form.addEventListener('submit', (e) => {
        e.preventDefault();
        goToShopAll();
      });

      // Force Enter to work even if other scripts intercept it
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          e.stopPropagation();
          if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
          goToShopAll();
        }
      }, true);

      // ==========================
      // Ajax Live Autocomplete
      // ==========================
      const resultsContainer = headerEl.querySelector('#vx-search-results');
      let debounceTimer = null;

      if (resultsContainer) {
        input.addEventListener('input', (e) => {
          const query = e.target.value.trim();

          if (!query) {
            resultsContainer.style.display = 'none';
            resultsContainer.innerHTML = '';
            return;
          }

          clearTimeout(debounceTimer);
          debounceTimer = setTimeout(() => {
            fetch(`/products/search-json?q=${encodeURIComponent(query)}`)
              .then(res => res.json())
              .then(data => {
                resultsContainer.innerHTML = '';

                if (!data.success || !data.results || data.results.length === 0) {
                  resultsContainer.innerHTML = '<li class="vx-search-dropdown-empty">No products found.</li>';
                  resultsContainer.style.display = 'block';
                  return;
                }

                data.results.slice(0, 5).forEach(product => {
                  let imgUrl = '../assets/MainLogo.png';
                  if (product.image_url) {
                    imgUrl = product.image_url.startsWith('http')
                      ? product.image_url
                      : '/storage/' + product.image_url.replace(/^\/+/, '');
                  }

                  const li = document.createElement('li');
                  li.innerHTML = `
                    <img src="${imgUrl}" alt="${product.name}" class="vx-search-dropdown-img" onerror="this.src='../assets/MainLogo.png'">
                    <div class="vx-search-dropdown-info">
                      <span class="vx-search-dropdown-title">${product.name}</span>
                      <span class="vx-search-dropdown-price">£${Number(product.price).toFixed(2)}</span>
                    </div>
                  `;
                  li.addEventListener('click', () => {
                    window.location.href = `ProductPage.html?id=${product.id}`;
                  });
                  resultsContainer.appendChild(li);
                });

                resultsContainer.style.display = 'flex';
                resultsContainer.style.flexDirection = 'column';
              })
              .catch(err => console.error('Live search error:', err));
          }, 300);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
          if (!form.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.style.display = 'none';
          }
        });

        // Focus brings it back if there's text
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
    const ordersLink = headerEl.querySelector('#orders-link');
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
    if (ordersLink) ordersLink.style.display = 'none';

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

      fetch('/user-status', {
        headers: { Accept: 'application/json' },
        credentials: 'include'
      })
        .then(res => res.json())
        .then(data => {
          if (data.logged_in) {
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
                await fetch('/logout-json', { credentials: 'include' });
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

    fetch('/user-status', {
      headers: { 'Accept': 'application/json' }
    })
      .then(res => res.text())
      .then(text => {
        try {
          JSON.parse(text);
        } catch (e) {
          console.error('Non-JSON /user-status response:', text);
        }
      })
      .catch(err => {
        console.error('User status error:', err);
      });

    initChatbot();
    initScrollTop();
  });

// ; (async function () {
//   try {
//     const res = await fetch('/user-status', {
//       headers: { 'Accept': 'application/json' }
//     });

//     if (!res.ok) return;

//     const data = await res.json();

//     const remember = localStorage.getItem('rememberLogin') === '1';
//     const temp = sessionStorage.getItem('tempLoggedIn') === '1';

//     if (data.logged_in && !remember && !temp) {
//       await fetch('/logout-json', {
//         method: 'GET',
//         headers: { 'Accept': 'application/json' }
//       });

//       // window.location.reload();
//     }
//   } catch (err) {
//     console.error('Logout check error:', err);
//   }
// })();

/* =========================================
   SCROLL TO TOP LOGIC
   ========================================= */
function initScrollTop() {
  const scrollTopBtn = document.getElementById('vx-scroll-top');
  if (!scrollTopBtn) return;

  // Show/hide based on scroll position
  window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
      scrollTopBtn.classList.remove('hidden');
    } else {
      scrollTopBtn.classList.add('hidden');
    }
  });

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

  // Handle Form Submission
  chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const userMessage = chatInput.value.trim();
    if (!userMessage) return;

    // Append User Message
    appendMessage('user', userMessage);
    chatInput.value = '';

    // Append 'Typing...' Indicator
    const typingIndicator = appendMessage('ai', '...');

    try {
      // Send to Backend
      const response = await fetch('/chatbot/ask', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ message: userMessage })
      });

      const data = await response.json();

      // Update the typing indicator with the real response
      if (data.status === 'success') {
        typingIndicator.textContent = data.reply;
      } else {
        typingIndicator.textContent = "Error: Couldn't reach the server right now.";
      }

    } catch (error) {
      console.error("Chatbot Error:", error);
      typingIndicator.textContent = "Oops! My circuits are crossed. Try again later.";
    }
  });

  // Helper to append a bubble
  function appendMessage(senderType, text) {
    const msgDiv = document.createElement('div');
    msgDiv.classList.add('vx-message');
    msgDiv.classList.add(senderType === 'ai' ? 'ai-message' : 'user-message');
    msgDiv.textContent = text;
    chatMessages.appendChild(msgDiv);

    // Auto-scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;

    return msgDiv;
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
        obj.style.transition = ' 0.3s ease';

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
  const searchInput = document.getElementById('vx-search-input');

  if (headerEl) {
    headerEl.classList.toggle('search-active');
  }
};
