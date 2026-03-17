// ── Password show / hide ──────────────────────────────────────
    document.getElementById('pwToggle').addEventListener('click', function () {
      const input = document.getElementById('password');
      const icon  = document.getElementById('pwIcon');
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      icon.classList.toggle('bi-eye-fill',      !isHidden);
      icon.classList.toggle('bi-eye-slash-fill',  isHidden);
    });

    // ── Clear inline error as soon as user starts retyping ────────
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('input', function () {
        if (this.classList.contains('is-invalid')) {
          this.classList.remove('is-invalid');
          const errEl = this.closest('.field-group')?.querySelector('.field-error');
          if (errEl) errEl.style.opacity = '0';
        }
      });
    });
