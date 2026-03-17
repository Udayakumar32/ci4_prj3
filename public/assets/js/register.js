 // ── Show/hide password ──────────────────────────────────────────
  function toggleVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
    } else {
      input.type = 'password';
      icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
    }
  }
  document.getElementById('togglePw') .addEventListener('click', () => toggleVisibility('password',        'pwIcon'));
  document.getElementById('toggleCpw').addEventListener('click', () => toggleVisibility('confirmPassword', 'cpwIcon'));

  // ── Password strength ───────────────────────────────────────────
  const pwInput      = document.getElementById('password');
  const strengthWrap = document.getElementById('strengthWrap');
  const strengthFill = document.getElementById('strengthFill');
  const strengthText = document.getElementById('strengthText');

  pwInput.addEventListener('input', () => {
    const val = pwInput.value;
    strengthWrap.style.display = val ? 'block' : 'none';
    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
      { pct: '20%',  bg: '#e74c3c', label: 'Very weak'   },
      { pct: '45%',  bg: '#e67e22', label: 'Weak'        },
      { pct: '65%',  bg: '#f1c40f', label: 'Fair'        },
      { pct: '85%',  bg: '#355872', label: 'Strong'      },
      { pct: '100%', bg: '#1a2f3f', label: 'Very strong' },
    ];
    const lvl = levels[score] || levels[0];
    strengthFill.style.width      = lvl.pct;
    strengthFill.style.background = lvl.bg;
    strengthText.textContent      = lvl.label;
  });

  // ── Confirm password match ──────────────────────────────────────
  const cpwInput = document.getElementById('confirmPassword');
  const matchMsg = document.getElementById('matchMsg');
  function checkMatch() {
    if (!cpwInput.value) { matchMsg.textContent = ''; return; }
    if (cpwInput.value === pwInput.value) {
      matchMsg.style.color = '#1a5276';
      matchMsg.innerHTML   = '<i class="bi bi-check-circle-fill"></i> Passwords match';
    } else {
      matchMsg.style.color = '#c0392b';
      matchMsg.innerHTML   = '<i class="bi bi-x-circle-fill"></i> Passwords do not match';
    }
  }
  cpwInput.addEventListener('input', checkMatch);
  pwInput.addEventListener ('input', checkMatch);

  // ── Profile picture preview ─────────────────────────────────────
  document.getElementById('profilePicInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      const img  = document.getElementById('avatarPreview');
      const icon = document.getElementById('avatarIcon');
      img.src            = e.target.result;
      img.style.display  = 'block';
      icon.style.display = 'none';
    };
    reader.readAsDataURL(file);
  });
