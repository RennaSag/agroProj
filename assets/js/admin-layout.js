(() => {
  const sidebar = document.querySelector('.sidebar');
  const topbar = document.querySelector('.topbar');

  if (sidebar && topbar) {
    sidebar.id = sidebar.id || 'adminSidebar';
    sidebar.setAttribute('aria-label', 'Navegação administrativa');

    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'menu-toggle';
    toggle.setAttribute('aria-controls', sidebar.id);
    toggle.setAttribute('aria-expanded', 'false');
    toggle.innerHTML = '<span class="menu-toggle-icon" aria-hidden="true">&#9776;</span>Menu';
    topbar.prepend(toggle);

    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.insertBefore(overlay, document.querySelector('.main'));

    const closeMenu = (returnFocus = true) => {
      sidebar.classList.remove('is-open');
      overlay.classList.remove('is-open');
      document.body.classList.remove('admin-nav-open');
      toggle.setAttribute('aria-expanded', 'false');
      if (returnFocus && window.innerWidth <= 900) toggle.focus();
    };

    const openMenu = () => {
      sidebar.classList.add('is-open');
      overlay.classList.add('is-open');
      document.body.classList.add('admin-nav-open');
      toggle.setAttribute('aria-expanded', 'true');
      const firstLink = sidebar.querySelector('a');
      if (firstLink) firstLink.focus();
    };

    toggle.addEventListener('click', () => {
      if (sidebar.classList.contains('is-open')) {
        closeMenu();
      } else {
        openMenu();
      }
    });

    overlay.addEventListener('click', () => closeMenu());
    sidebar.addEventListener('click', event => {
      if (event.target.closest('a') && window.innerWidth <= 900) closeMenu(false);
    });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && sidebar.classList.contains('is-open')) closeMenu();
    });
    window.addEventListener('resize', () => {
      if (window.innerWidth > 900 && sidebar.classList.contains('is-open')) closeMenu(false);
    });
  }

  document.querySelectorAll('table').forEach(table => {
    const labels = [...table.querySelectorAll('thead th')].map(header => header.textContent.trim());
    if (!labels.length) return;

    table.classList.add('responsive-table');
    table.querySelectorAll('tbody tr').forEach(row => {
      [...row.querySelectorAll('td')].forEach((cell, index) => {
        cell.dataset.label = labels[index] || '';
      });
    });
  });

  document.querySelectorAll('input[type="file"][data-preview-target]').forEach(input => {
    input.addEventListener('change', () => {
      const file = input.files && input.files[0];
      const preview = document.getElementById(input.dataset.previewTarget);
      const empty = document.getElementById(input.dataset.emptyTarget);
      if (!file || !preview) return;

      const reader = new FileReader();
      reader.addEventListener('load', event => {
        preview.src = event.target.result;
        preview.hidden = false;
        if (empty) empty.hidden = true;
      });
      reader.readAsDataURL(file);
    });
  });
})();
