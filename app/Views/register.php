<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>

  <style>
    :root {
      --deep:    #355872;
      --mid:     #7AAACE;
      --light:   #9CD5FF;
      --pale:    #F7F8F0;
      --text:    #1a2f3f;
      --muted:   #4a7a9b;
      --border:  #a8cfe8;
      --white:   #ffffff;
      --err-bg:  #fff0f0;
      --err-border: #f5c6c6;
      --err-text:   #c0392b;
    }

    *, *::before, *::after { box-sizing: border-box; }

    body {
      min-height: 100vh;
      background: linear-gradient(135deg, var(--pale) 0%, var(--light) 50%, var(--mid) 100%);
      font-family: 'DM Sans', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    body::before, body::after {
      content: '';
      position: fixed;
      border-radius: 50%;
      opacity: .25;
      pointer-events: none;
    }
    body::before { width:420px;height:420px;background:var(--deep);top:-120px;left:-120px; }
    body::after  { width:300px;height:300px;background:var(--mid);bottom:-80px;right:-80px; }

    .register-card {
      background: rgba(255,255,255,.82);
      backdrop-filter: blur(18px);
      border: 1px solid rgba(255,255,255,.6);
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(53,88,114,.18);
      width: 100%;
      max-width: 540px;
      padding: 2.6rem 2.8rem;
      position: relative;
      z-index: 1;
    }

    h2 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.85rem;
      color: var(--text);
      margin-bottom: .3rem;
    }
    .subtitle { color:var(--muted);font-size:.9rem;margin-bottom:1.8rem; }

    /* ── Global error banner ── */
    .alert-error {
      background: var(--err-bg);
      border: 1.5px solid var(--err-border);
      border-radius: 12px;
      padding: .85rem 1rem;
      margin-bottom: 1.4rem;
      font-size: .88rem;
      color: var(--err-text);
      display: flex;
      gap: .6rem;
      align-items: flex-start;
    }
    .alert-error i { font-size: 1.05rem; margin-top: .05rem; flex-shrink:0; }
    .alert-error ul { margin: 0; padding-left: 1.1rem; }
    .alert-error li { margin-bottom: .2rem; }

    /* ── Success banner ── */
    .alert-success {
      background: #f0fff4;
      border: 1.5px solid #a3d9b1;
      border-radius: 12px;
      padding: .85rem 1rem;
      margin-bottom: 1.4rem;
      font-size: .88rem;
      color: #1a5c30;
      display: flex;
      gap: .6rem;
      align-items: center;
    }

    /* Labels */
    .form-label {
      font-weight: 600;
      font-size: .82rem;
      color: var(--text);
      letter-spacing: .04em;
      text-transform: uppercase;
      margin-bottom: .35rem;
    }

    /* Inputs */
    .form-control, .form-select {
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: .62rem .9rem;
      font-size: .95rem;
      color: var(--text);
      background: rgba(232,245,189,.25);
      transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .form-control:focus, .form-select:focus {
      border-color: var(--deep);
      box-shadow: 0 0 0 3.5px rgba(122,170,206,.28);
      background: #fff;
      outline: none;
    }
    .form-control::placeholder { color: #8ab8d4; }

    /* ── Field error state ── */
    .form-control.is-invalid,
    .form-select.is-invalid {
      border-color: var(--err-text) !important;
      background: var(--err-bg) !important;
      box-shadow: none !important;
    }
    .field-error {
      font-size: .78rem;
      color: var(--err-text);
      margin-top: .3rem;
      display: flex;
      align-items: center;
      gap: .3rem;
    }
    .field-error i { font-size: .85rem; }

    /* Password toggle */
    .pw-wrapper { position: relative; }
    .pw-wrapper .form-control { padding-right: 2.8rem; }
    .pw-toggle {
      position: absolute; right:.75rem; top:50%; transform:translateY(-50%);
      background:none; border:none; color:var(--muted); cursor:pointer;
      padding:0; font-size:1.1rem; line-height:1; transition:color .2s;
    }
    .pw-toggle:hover { color:var(--deep); }

    /* Avatar */
    .avatar-upload { display:flex; align-items:center; gap:1rem; }
    .avatar-preview {
      width:64px; height:64px; border-radius:50%; object-fit:cover;
      border:2.5px solid var(--mid); background:var(--light);
      display:flex; align-items:center; justify-content:center;
      overflow:hidden; flex-shrink:0;
    }
    .avatar-preview img { width:100%;height:100%;object-fit:cover;display:none; }
    .avatar-preview .placeholder-icon { font-size:1.6rem;color:var(--muted); }
    .avatar-btn {
      display:inline-flex; align-items:center; gap:.4rem;
      background:var(--light); border:1.5px dashed var(--border);
      border-radius:10px; padding:.5rem 1rem; font-size:.87rem;
      color:var(--text); font-weight:600; cursor:pointer;
      transition:background .2s, border-color .2s;
    }
    .avatar-btn:hover { background:var(--pale); border-color:var(--deep); }
    #profilePicInput { display:none; }

    /* Gender radios */
    .gender-group { display:flex; gap:.7rem; flex-wrap:wrap; }
    .gender-option { position:relative; }
    .gender-option input[type="radio"] { position:absolute;opacity:0;width:0;height:0; }
    .gender-option label {
      display:inline-flex; align-items:center; gap:.45rem;
      padding:.45rem 1rem; border:1.5px solid var(--border);
      border-radius:8px; font-size:.9rem; font-weight:500;
      color:var(--muted); cursor:pointer;
      transition:border-color .2s, background .2s, color .2s; user-select:none;
    }
    .gender-option label i { font-size:1rem; }
    .gender-option input:checked + label {
      background:var(--mid); border-color:var(--deep);
      color:var(--text); font-weight:600;
    }
    .gender-option label:hover { border-color:var(--deep); background:var(--pale); }
    /* invalid gender border */
    .gender-group.is-invalid .gender-option label { border-color: var(--err-text); }

    .field-divider { border-color:var(--light); margin:1.4rem 0; }

    /* Submit */
    .btn-register {
      background:var(--deep); color:#fff; border:none;
      border-radius:12px; padding:.78rem; font-size:1rem;
      font-weight:700; letter-spacing:.03em; width:100%;
      transition:background .2s, transform .15s, box-shadow .2s;
      box-shadow:0 6px 20px rgba(53,88,114,.35);
    }
    .btn-register:hover {
      background:#2a4760; transform:translateY(-2px);
      box-shadow:0 10px 28px rgba(53,88,114,.40); color:#fff;
    }
    .btn-register:active { transform:translateY(0); }

    .login-link { text-align:center;font-size:.87rem;color:var(--muted);margin-top:1.1rem; }
    .login-link a { color:var(--deep);font-weight:700;text-decoration:none; }
    .login-link a:hover { text-decoration:underline; }

    /* Strength bar */
    .strength-wrap { margin-top:.45rem; display:none; }
    .strength-bar { height:4px;border-radius:4px;background:var(--light);overflow:hidden; }
    .strength-fill { height:100%;width:0%;border-radius:4px;transition:width .35s, background .35s; }
    .strength-text { font-size:.75rem;margin-top:.25rem;color:var(--muted); }

    @media (max-width:480px) { .register-card { padding:1.8rem 1.4rem; } }
  </style>
</head>
<body>

<div class="register-card">
  <h2>Create Account</h2>
  <p class="subtitle">Join us — it only takes a moment.</p>

  <?php
  // ── Pull flash data set by AuthController ──────────────────────
  $errors  = session()->getFlashdata('errors') ?? [];   // array of field => message
  $error   = session()->getFlashdata('error')  ?? null; // single string error
  $success = session()->getFlashdata('success') ?? null;
  ?>

  <!-- ── Global success banner ──────────────────────────────────── -->
  <?php if ($success): ?>
    <div class="alert-success">
      <i class="bi bi-check-circle-fill"></i>
      <?= esc($success) ?>
    </div>
  <?php endif; ?>

  <!-- ── Global error banner (single message OR multiple) ───────── -->
  <?php if ($error): ?>
    <div class="alert-error">
      <i class="bi bi-exclamation-triangle-fill"></i>
      <?= esc($error) ?>
    </div>
  <?php endif; ?>

  <?php if (! empty($errors) && count($errors) > 1): ?>
    <div class="alert-error">
      <i class="bi bi-exclamation-triangle-fill"></i>
      <div>
        <strong>Please fix the following errors:</strong>
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?= esc($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('register') ?>" method="POST" enctype="multipart/form-data" novalidate>
    <?= csrf_field() ?>

    <!-- ── Profile Picture ──────────────────────────────────────── -->
    <div class="mb-3">
      <label class="form-label">Profile Picture</label>
      <div class="avatar-upload">
        <div class="avatar-preview" id="avatarPreviewWrap">
          <i class="bi bi-person-fill placeholder-icon" id="avatarIcon"></i>
          <img id="avatarPreview" alt="Preview"/>
        </div>
        <label class="avatar-btn" for="profilePicInput">
          <i class="bi bi-upload"></i> Upload Photo
        </label>
        <input type="file" id="profilePicInput" name="profile_pic" accept="image/*"/>
      </div>
      <?php if (! empty($errors['profile_pic'])): ?>
        <div class="field-error">
          <i class="bi bi-x-circle-fill"></i> <?= esc($errors['profile_pic']) ?>
        </div>
      <?php endif; ?>
    </div>

    <hr class="field-divider"/>

    <!-- ── Username + Email ─────────────────────────────────────── -->
    <div class="row g-3 mb-3">
      <div class="col-sm-6">
        <label class="form-label" for="username">Username</label>
        <input type="text" id="username" name="username"
               class="form-control <?= ! empty($errors['username']) ? 'is-invalid' : '' ?>"
               placeholder="john_doe"
               value="<?= old('username') ?>"
               required/>
        <?php if (! empty($errors['username'])): ?>
          <div class="field-error">
            <i class="bi bi-x-circle-fill"></i> <?= esc($errors['username']) ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-sm-6">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" name="email"
               class="form-control <?= ! empty($errors['email']) ? 'is-invalid' : '' ?>"
               placeholder="john@example.com"
               value="<?= old('email') ?>"
               required/>
        <?php if (! empty($errors['email'])): ?>
          <div class="field-error">
            <i class="bi bi-x-circle-fill"></i> <?= esc($errors['email']) ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ── Phone ────────────────────────────────────────────────── -->
    <div class="mb-3">
      <label class="form-label" for="phone">Phone Number</label>
      <div class="input-group">
        <span class="input-group-text"
              style="background:var(--light);border-color:var(--border);
                     border-radius:10px 0 0 10px;color:var(--muted);
                     <?= ! empty($errors['phone_number']) ? 'border-color:var(--err-text)!important;' : '' ?>">
          <i class="bi bi-telephone-fill"></i>
        </span>
        <input type="tel" id="phone" name="phone"
               class="form-control <?= ! empty($errors['phone_number']) ? 'is-invalid' : '' ?>"
               style="border-radius:0 10px 10px 0;"
               placeholder="+91 98765 43210"
               value="<?= old('phone') ?>"
               required/>
      </div>
      <?php if (! empty($errors['phone_number'])): ?>
        <div class="field-error">
          <i class="bi bi-x-circle-fill"></i> <?= esc($errors['phone_number']) ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- ── Password ─────────────────────────────────────────────── -->
    <div class="mb-1">
      <label class="form-label" for="password">Password</label>
      <div class="pw-wrapper">
        <input type="password" id="password" name="password"
               class="form-control <?= ! empty($errors['password']) ? 'is-invalid' : '' ?>"
               placeholder="Create a strong password" required/>
        <button type="button" class="pw-toggle" id="togglePw" aria-label="Show password">
          <i class="bi bi-eye-fill" id="pwIcon"></i>
        </button>
      </div>
      <?php if (! empty($errors['password'])): ?>
        <div class="field-error">
          <i class="bi bi-x-circle-fill"></i> <?= esc($errors['password']) ?>
        </div>
      <?php endif; ?>
      <!-- Strength bar -->
      <div class="strength-wrap" id="strengthWrap">
        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
        <p class="strength-text" id="strengthText"></p>
      </div>
    </div>

    <!-- ── Confirm Password ──────────────────────────────────────── -->
    <div class="mb-3 mt-3">
      <label class="form-label" for="confirmPassword">Confirm Password</label>
      <div class="pw-wrapper">
        <input type="password" id="confirmPassword" name="confirm_password"
               class="form-control <?= ! empty($errors['confirm_password']) ? 'is-invalid' : '' ?>"
               placeholder="Repeat your password" required/>
        <button type="button" class="pw-toggle" id="toggleCpw" aria-label="Show confirm password">
          <i class="bi bi-eye-fill" id="cpwIcon"></i>
        </button>
      </div>
      <?php if (! empty($errors['confirm_password'])): ?>
        <div class="field-error">
          <i class="bi bi-x-circle-fill"></i> <?= esc($errors['confirm_password']) ?>
        </div>
      <?php endif; ?>
      <div id="matchMsg" style="font-size:.78rem;margin-top:.3rem;"></div>
    </div>

    <!-- ── Gender ───────────────────────────────────────────────── -->
    <div class="mb-4">
      <label class="form-label d-block">Gender</label>
      <div class="gender-group <?= ! empty($errors['gender']) ? 'is-invalid' : '' ?>">
        <div class="gender-option">
          <input type="radio" name="gender" id="genderMale" value="male"
                 <?= old('gender') === 'male' ? 'checked' : '' ?> required/>
          <label for="genderMale"><i class="bi bi-gender-male"></i> Male</label>
        </div>
        <div class="gender-option">
          <input type="radio" name="gender" id="genderFemale" value="female"
                 <?= old('gender') === 'female' ? 'checked' : '' ?>/>
          <label for="genderFemale"><i class="bi bi-gender-female"></i> Female</label>
        </div>
        <div class="gender-option">
          <input type="radio" name="gender" id="genderOther" value="other"
                 <?= old('gender') === 'other' ? 'checked' : '' ?>/>
          <label for="genderOther"><i class="bi bi-gender-ambiguous"></i> Other</label>
        </div>
      </div>
      <?php if (! empty($errors['gender'])): ?>
        <div class="field-error mt-1">
          <i class="bi bi-x-circle-fill"></i> <?= esc($errors['gender']) ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- ── Submit ───────────────────────────────────────────────── -->
    <button type="submit" class="btn-register">
      <i class="bi bi-person-check-fill me-2"></i>Create My Account
    </button>

  </form>

  <p class="login-link">Already have an account? <a href="<?= base_url('login') ?>">Sign in</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
</script>
</body>
</html>