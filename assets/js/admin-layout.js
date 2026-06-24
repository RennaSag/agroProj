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
      const nameTarget = document.getElementById(input.id + 'Name');
      const clearTarget = document.getElementById(input.dataset.previewTarget.replace('Preview', 'Clear'));

      if (nameTarget) {
        nameTarget.textContent = file ? file.name : 'Nenhum arquivo escolhido';
      }

      if (!file) {
        if (preview) {
          const origSrc = preview.getAttribute('data-original-src');
          if (origSrc) {
            preview.src = origSrc;
            preview.hidden = false;
            preview.style.display = 'block';
          } else {
            preview.hidden = true;
            preview.style.display = 'none';
            preview.removeAttribute('src');
            if (empty) empty.hidden = false;
          }
        }
        if (clearTarget) clearTarget.style.display = 'none';
        return;
      }

      if (!preview) return;

      const reader = new FileReader();
      reader.addEventListener('load', event => {
        preview.src = event.target.result;
        preview.hidden = false;
        preview.style.display = 'block';
        if (empty) empty.hidden = true;
        if (clearTarget) { clearTarget.style.display = 'flex'; clearTarget.hidden = false; }
      });
      reader.readAsDataURL(file);
    });
  });

  // --- IMAGE CROPPER INTERCEPTOR ---
  let cropperScriptLoaded = false;
  let cropperInstance = null;

  async function loadCropper() {
    if (cropperScriptLoaded) return;
    return new Promise((resolve) => {
      const css = document.createElement('link');
      css.rel = 'stylesheet';
      css.href = 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css';
      document.head.appendChild(css);

      const js = document.createElement('script');
      js.src = 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js';
      js.onload = () => {
        cropperScriptLoaded = true;
        resolve();
      };
      document.head.appendChild(js);
    });
  }

  async function showCropModal(file) {
    await loadCropper();
    
    return new Promise((resolve) => {
      let modal = document.getElementById('cropModal');
      let overlay = document.getElementById('cropModalOverlay');
      
      if (!modal) {
        overlay = document.createElement('div');
        overlay.id = 'cropModalOverlay';
        overlay.style = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9998; display:none;';
        document.body.appendChild(overlay);

        modal = document.createElement('div');
        modal.id = 'cropModal';
        modal.style = 'position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:9999; background:#fff; padding:20px; border-radius:10px; box-shadow:0 10px 30px rgba(0,0,0,0.3); width:90vw; max-width:600px; display:none; flex-direction:column; max-height:90vh;';
        modal.innerHTML = `
          <h3 style="margin-top:0; margin-bottom:15px; color:#333; font-family:var(--font-title, sans-serif);">Recortar Imagem</h3>
          <div style="flex:1; overflow:hidden; background:#eee; border-radius:8px; display:flex; justify-content:center; align-items:center; min-height:300px;">
            <img id="cropTargetImage" style="max-width:100%; max-height:60vh; display:block;">
          </div>
          <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
            <button type="button" id="cropCancelBtn" class="btn-secondary" style="margin:0;">Cancelar</button>
            <button type="button" id="cropConfirmBtn" class="btn-primary" style="margin:0;">Confirmar Recorte</button>
          </div>
        `;
        document.body.appendChild(modal);
      }
      
      const img = document.getElementById('cropTargetImage');
      const cancelBtn = document.getElementById('cropCancelBtn');
      const confirmBtn = document.getElementById('cropConfirmBtn');
      
      overlay.style.display = 'block';
      modal.style.display = 'flex';
      
      const reader = new FileReader();
      reader.onload = (e) => {
        img.src = e.target.result;
        
        if (cropperInstance) cropperInstance.destroy();
        cropperInstance = new Cropper(img, {
          aspectRatio: 16 / 9,
          viewMode: 2,
          autoCropArea: 1,
          background: false
        });
      };
      reader.readAsDataURL(file);
      
      const cleanup = () => {
         if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
         overlay.style.display = 'none';
         modal.style.display = 'none';
         cancelBtn.onclick = null;
         confirmBtn.onclick = null;
      };
      
      cancelBtn.onclick = () => {
         cleanup();
         resolve(null);
      };
      
      confirmBtn.onclick = () => {
         if (!cropperInstance) return;
         const type = file.type === 'image/png' ? 'image/png' : (file.type === 'image/webp' ? 'image/webp' : 'image/jpeg');
         cropperInstance.getCroppedCanvas({
           imageSmoothingEnabled: true,
           imageSmoothingQuality: 'high',
           fillColor: type === 'image/jpeg' ? '#fff' : 'transparent'
         }).toBlob((blob) => {
           cleanup();
           if (!blob) resolve(null);
           else {
             const ext = type.split('/')[1];
             const newName = file.name.replace(/\.[^/.]+$/, "") + "_crop." + ext;
             const croppedFile = new File([blob], newName, { type: type, lastModified: Date.now() });
             resolve(croppedFile);
           }
         }, type, 0.9);
      };
    });
  }

  async function processCropQueue(files) {
    const result = [];
    for (let file of files) {
       if (!file.type.startsWith('image/')) {
          result.push(file);
          continue;
       }
       const cropped = await showCropModal(file);
       if (!cropped) return null; // Se cancelar, aborta toda a fila
       result.push(cropped);
    }
    return result;
  }

  document.addEventListener('change', async (e) => {
    if (e.target && e.target.matches && e.target.matches('input[type="file"]') && e.target.accept && e.target.accept.includes('image')) {
      if (e.target.__isCropping) return;
      if (e.target.files.length === 0) return;

      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();

      const originalFiles = Array.from(e.target.files);
      e.target.value = ''; 
      
      try {
        const croppedFiles = await processCropQueue(originalFiles);
        if (croppedFiles && croppedFiles.length > 0) {
          const dt = new DataTransfer();
          croppedFiles.forEach(f => dt.items.add(f));
          e.target.files = dt.files;
          
          e.target.__isCropping = true;
          e.target.dispatchEvent(new Event('change', { bubbles: true }));
          delete e.target.__isCropping;
        }
      } catch (err) {
        console.error('Erro no crop:', err);
      }
    }
  }, true);
})();
