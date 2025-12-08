const headerFile = '../pages/header.html';

fetch(headerFile)
  .then(response => response.text())
  .then(html => {
    const headerEl = document.querySelector('header');
    headerEl.innerHTML = html;

    // After header HTML is inserted, wire up login/profile + admin link
    const profileLink = headerEl.querySelector('#profileLink');
    const adminLink = headerEl.querySelector('#admin-link');

    // Default: assume logged out → go to custom login
    if (profileLink) {
      profileLink.href = '/pages/login.html';
    }
    if (adminLink) {
      adminLink.style.display = 'none';
    }

    // Ask backend who is logged in
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

        if (!data || !profileLink) return;

        if (data.logged_in) {
          // Logged in → send to Laravel profile page
          profileLink.href = '/profile';

          // Show admin link if user is admin
          if (adminLink && data.user && data.user.role === 'admin') {
            adminLink.style.display = 'inline-block';
          }
        } else {
          // Not logged in → stay on Login page
          profileLink.href = '/pages/login.html';
          if (adminLink) adminLink.style.display = 'none';
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



//Ill possibly chnage teh code logic in header.js so that they use toggles for dropdowns, shows, close sections, slides etc
function toggleDropdown(){
    const auth_section=document.getElementById('auth_dropdown');
    if(auth_section.style.height==='0%' || auth_section.style.height===''){
        auth_section.style.height='auto';
        auth_section.padding='1%';
    }else{
        auth_section.style.height='0%';
        auth_section.padding='0%';
    }
}