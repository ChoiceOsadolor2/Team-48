const headerFile = '../pages/header.html';

fetch(headerFile)
  .then(response => response.text())
  .then(html => {
    const headerEl = document.querySelector('header');
    headerEl.innerHTML = html;

    const footerFile = '../pages/footer.html';
    fetch(footerFile)
      .then(response => response.text())
      .then(html => {
        const footerEl = document.querySelector('footer');
        if (footerEl) footerEl.innerHTML = html;
      });

    // ===============================
    // ✅ GLOBAL SEARCH (ALL pages)
    // - Enter or button always redirects to ShopAll.html?q=...
    // - Runs after header.html is injected
    // - Uses capture to beat other key handlers
    // ===============================
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
        try { closeSection('search'); } catch (_) {}
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
    })();

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
        try {
          JSON.parse(text);
        } catch (e) {
          console.error('Non-JSON /user-status response:', text);
        }
      })
      .catch(err => {
        console.error('User status error:', err);
      });
  });

// Show/close functions (unchanged)
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
