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
    footerEl.innerHTML = html;
  });

    


    

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
        // On error, fall back to login link
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
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!res.ok) return;

        const data = await res.json();

        const remember = localStorage.getItem('rememberLogin') === '1';
        const temp = sessionStorage.getItem('tempLoggedIn') === '1';

        if (data.logged_in && !remember && !temp) {
            await fetch('/logout-json', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            window.location.reload();
        }
    } catch (err) {
        console.error('Auto-logout check failed:', err);
    }
})();
