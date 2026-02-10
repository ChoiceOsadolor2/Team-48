// header.js
const headerFile = '../pages/header.html';

fetch(headerFile)
  .then(response => response.text())
  .then(html => {
    const headerEl = document.querySelector('header');
    headerEl.innerHTML = html;

    (function bindVeltrixSearch() {
  const form = headerEl.querySelector('#vx-search-form');
  const input = headerEl.querySelector('#vx-search-input');

  if (!form || !input) return;
  if (form.dataset.bound === '1') return;
  form.dataset.bound = '1';

  function goToShopAll() {
    const q = (input.value || '').trim();
    if (!q) return;
    try { closeSection('search'); } catch (_) {}
    window.location.href = `ShopAll.html?q=${encodeURIComponent(q)}`;
  }

  // Button / form submit
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    goToShopAll();
  });

  // ✅ Force Enter to work even if other scripts block it
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      e.stopPropagation();
      if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
      goToShopAll();
    }
  }, true); // capture = true (runs before other listeners)
})();

    const footerFile = '../pages/footer.html';
    fetch(footerFile)
      .then(response => response.text())
      .then(html => {
        const footerEl = document.querySelector('footer');
        if (footerEl) footerEl.innerHTML = html;
      });

    // ===============================
    // ✅ GLOBAL SEARCH (works on ALL pages)
    // - Press Enter or click Search
    // - Always navigates to ShopAll.html?q=...
    // - Fixes invisible button via inline styles (minimal)
    // ===============================
    function bindGlobalSearch() {
      // 1) Search inside injected header.html (the popup search)
      const searchRoot = headerEl.querySelector('#search');
      const form =
        headerEl.querySelector('#global-search-form') ||
        headerEl.querySelector('#search-form') ||
        searchRoot?.querySelector('form');

      const input =
        headerEl.querySelector('#global-search-input') ||
        headerEl.querySelector('#global_search') ||
        headerEl.querySelector('#shop_search') ||
        headerEl.querySelector('#search-bar') ||
        form?.querySelector('input[type="search"]') ||
        form?.querySelector('input[type="text"]') ||
        searchRoot?.querySelector('input');

      const submitBtn =
        headerEl.querySelector('#global-search-btn') ||
        form?.querySelector('button[type="submit"]');

      // Guard: only bind once
      if (form && form.dataset.searchBound === '1') return;

      if (form && input) {
        form.dataset.searchBound = '1';

        // Make the button visible even if CSS makes it white-on-white
        if (submitBtn) {
          submitBtn.style.background = '#111';
          submitBtn.style.color = '#fff';
          submitBtn.style.border = '1px solid #111';
          submitBtn.style.padding = '10px 14px';
          submitBtn.style.borderRadius = '8px';
          submitBtn.style.cursor = 'pointer';
        }

        form.addEventListener('submit', (e) => {
          e.preventDefault();

          const q = (input.value || '').trim();
          if (!q) return;

          // Close overlay if your UI uses it (safe if function exists)
          try { closeSection('search'); } catch (_) {}

          // Always go to ShopAll with query string
          window.location.href = `ShopAll.html?q=${encodeURIComponent(q)}`;
        });
      }

      // 2) Homepage fallback: your index.html has its own .search-container
      const homeInput = document.querySelector('.search-container .search-bar');
      if (homeInput && homeInput.dataset.searchBound !== '1') {
        homeInput.dataset.searchBound = '1';

        homeInput.addEventListener('keydown', (e) => {
          if (e.key === 'Enter') {
            e.preventDefault();
            const q = (homeInput.value || '').trim();
            if (!q) return;
            window.location.href = `ShopAll.html?q=${encodeURIComponent(q)}`;
          }
        });

        // Also fix homepage close button visibility if needed
        const homeClose = document.querySelector('.search-container .search-close');
        if (homeClose) {
          homeClose.style.background = '#111';
          homeClose.style.color = '#fff';
          homeClose.style.border = '1px solid #111';
          homeClose.style.cursor = 'pointer';
        }
      }
    }

    // Bind immediately after header injection
    bindGlobalSearch();

    // If anything in header changes later, bind again safely
    new MutationObserver(() => bindGlobalSearch())
      .observe(headerEl, { childList: true, subtree: true });

    // ===============================
    // Existing user menu logic (unchanged)
    // ===============================
    const userMenuBtn = headerEl.querySelector('#userMenuBtn');
    const userMenuDropdown = headerEl.querySelector('#userMenuDropdown');
    const adminLink = headerEl.querySelector('#admin-link');

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
        let data = null;
        try {
          data = JSON.parse(text);
        } catch (e) {
          console.error('Non-JSON /user-status response:', text);
          return;
        }
      })
      .catch(err => {
        console.error('User status error:', err);
      });
  });

// The rest of your show/close functions stay here if you had them:
function show(id) {
  const section = document.getElementById(id);
  const section_main = document.getElementById(`${id}_main`);
  const filter_options = document.getElementById(`${id}_content`);

  if (section) {
    section.style.visibility = 'visible';
    section.style.backdropFilter = 'blur(30px)';
    section.style.backgroundColor = 'rgba(0,0,0,0.6)';
  }
  if (section_main) {
    section_main.style.width = '35%';
  }
  if (filter_options) {
    document.getElementById('toggle_down').style.display = 'flex';
    document.getElementById('toggle_up').style.display = 'none';

    filter_options.style.height = '70%';
    section.style.backdropFilter = 'none';
    section.style.backgroundColor = 'transparent';
  }
  if (id === 'search') {
    section.style.display = 'flex';
  }
}

function closeSection(id) {
  const section = document.getElementById(id);
  const section_main = document.getElementById(`${id}_main`);
  const filter_options = document.getElementById(`${id}_content`);

  if (section_main) section_main.style.width = '0';
  if (section) section.style.visibility = 'hidden';

  if (filter_options) {
    document.getElementById('toggle_down').style.display = 'none';
    document.getElementById('toggle_up').style.display = 'flex';
    section.style.visibility = 'visible';
    filter_options.style.height = '0%';
  }

  if (id === 'search') {
    section.style.display = 'none';
  }
}

(async function () {
  try {
    const res = await fetch('/user-status', {
      headers: { 'Accept': 'application/json' }
    });

    if (!res.ok) return;

    const data = await res.json();

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
    console.error('Auto-logout check failed:', err);
  }
})();
