<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign In</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet"/>

  <style>
    :root {
      --deep:        #355872;
      --deep-hover:  #2a4760;
      --mid:         #7AAACE;
      --light:       #9CD5FF;
      --pale:        #F7F8F0;
      --text:        #1a2f3f;
      --muted:       #4a7a9b;
      --border:      #a8cfe8;
      --input-bg:    rgba(156, 213, 255, 0.12);
      --card-bg:     rgba(255, 255, 255, 0.86);
      --err-bg:      #fff0f0;
      --err-border:  #f5c6c6;
      --err-text:    #c0392b;
      --ok-bg:       #f0fff4;
      --ok-border:   #a3d9b1;
      --ok-text:     #1a5c30;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100vh;
      font-family: 'DM Sans', sans-serif;
      background: linear-gradient(140deg, var(--pale) 0%, #d4ecff 45%, var(--mid) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
      overflow-x: hidden;
    }

    /* ── Background orbs ───────────────────────────────────────── */
    .bg-orb { position: fixed; border-radius: 50%; pointer-events: none; opacity: 0; animation: orbFade 1.2s ease forwards; }
    .bg-orb-1 { width:480px;height:480px;background:radial-gradient(circle,var(--deep) 0%,transparent 70%);top:-160px;left:-160px;animation-delay:0s; }
    .bg-orb-2 { width:340px;height:340px;background:radial-gradient(circle,var(--mid) 0%,transparent 70%);bottom:-100px;right:-100px;animation-delay:0.15s; }
    .bg-orb-3 { width:220px;height:220px;background:radial-gradient(circle,var(--light) 0%,transparent 70%);top:40%;right:8%;animation-delay:0.3s; }
    @keyframes orbFade { to { opacity: 0.22; } }

    /* ── Card ───────────────────────────────────────────────────── */
    .login-card {
      position: relative; z-index: 2;
      width: 100%; max-width: 480px;
      background: var(--card-bg);
      backdrop-filter: blur(22px);
      border: 1px solid rgba(255,255,255,0.68);
      border-radius: 28px;
      box-shadow: 0 4px 6px rgba(53,88,114,.04), 0 12px 40px rgba(53,88,114,.14), 0 32px 80px rgba(53,88,114,.10);
      padding: 3rem 3.2rem;
      animation: cardRise 0.7s cubic-bezier(0.22,1,0.36,1) both;
    }
    @keyframes cardRise {
      from { opacity:0; transform:translateY(28px) scale(0.97); }
      to   { opacity:1; transform:translateY(0) scale(1); }
    }

    /* ── Brand ──────────────────────────────────────────────────── */
    .brand-mark { display:flex;align-items:center;gap:.7rem;margin-bottom:2.4rem;animation:fadeSlide .6s .1s ease both; }
    .brand-icon {
      width:44px;height:44px;background:var(--deep);border-radius:14px;
      display:grid;place-items:center;color:#fff;font-size:1.3rem;
      box-shadow:0 4px 14px rgba(53,88,114,.38);flex-shrink:0;
    }
    .brand-name { font-family:'DM Serif Display',serif;font-size:1.35rem;color:var(--text); }

    /* ── Heading ────────────────────────────────────────────────── */
    .heading-block { margin-bottom:2rem;animation:fadeSlide .6s .18s ease both; }
    .heading-block h1 { font-family:'DM Serif Display',serif;font-size:2.1rem;color:var(--text);line-height:1.15;margin-bottom:.3rem; }
    .heading-block p  { font-size:.9rem;color:var(--muted); }

    /* ── Flash banners ──────────────────────────────────────────── */
    .flash-banner {
      border-radius:12px;padding:.85rem 1.05rem;margin-bottom:1.5rem;
      font-size:.875rem;font-weight:500;
      display:flex;align-items:flex-start;gap:.6rem;
      animation:fadeSlide .4s ease both;
    }
    .flash-banner i { font-size:1rem;margin-top:.05rem;flex-shrink:0; }
    .flash-error   { background:var(--err-bg);border:1.5px solid var(--err-border);color:var(--err-text); }
    .flash-success { background:var(--ok-bg); border:1.5px solid var(--ok-border); color:var(--ok-text); }

    /* ── Field groups ───────────────────────────────────────────── */
    .field-group { margin-bottom:1.25rem;animation:fadeSlide .6s ease both; }
    .field-group:nth-of-type(1) { animation-delay:.22s; }
    .field-group:nth-of-type(2) { animation-delay:.30s; }

    .field-label {
      display:block;font-size:.78rem;font-weight:700;color:var(--text);
      letter-spacing:.06em;text-transform:uppercase;margin-bottom:.4rem;
    }

    /* Input shell with icon */
    .input-shell { position:relative; }
    .input-shell .shell-icon {
      position:absolute;left:.95rem;top:50%;transform:translateY(-50%);
      color:var(--muted);font-size:1rem;pointer-events:none;transition:color .2s;
    }
    .input-shell:focus-within .shell-icon { color:var(--deep); }
    .input-shell .form-control { padding-left:2.7rem; }
    .input-shell .form-control.has-toggle { padding-right:2.8rem; }

    /* Eye toggle */
    .pw-eye {
      position:absolute;right:.8rem;top:50%;transform:translateY(-50%);
      background:none;border:none;color:var(--muted);cursor:pointer;
      font-size:1.1rem;line-height:1;padding:0;transition:color .2s;
    }
    .pw-eye:hover { color:var(--deep); }

    /* Input */
    .form-control {
      width:100%;border:1.5px solid var(--border);border-radius:11px;
      padding:.7rem .95rem;font-size:.95rem;font-family:'DM Sans',sans-serif;
      color:var(--text);background:var(--input-bg);
      transition:border-color .2s,box-shadow .2s,background .2s;outline:none;
    }
    .form-control::placeholder { color:#8ab8d4; }
    .form-control:focus {
      border-color:var(--deep);box-shadow:0 0 0 3.5px rgba(122,170,206,.28);background:#fff;
    }
    .form-control.is-invalid {
      border-color:var(--err-text)!important;background:var(--err-bg)!important;box-shadow:none!important;
    }
    .form-control.is-invalid:focus {
      box-shadow:0 0 0 3px rgba(192,57,43,.18)!important;
    }

    /* Field error */
    .field-error {
      display:flex;align-items:center;gap:.3rem;
      font-size:.77rem;color:var(--err-text);margin-top:.32rem;
      animation:shake .35s ease;
    }
    .field-error i { font-size:.82rem; }
    @keyframes shake {
      0%,100%{ transform:translateX(0); } 20%{ transform:translateX(-4px); } 60%{ transform:translateX(4px); }
    }

    /* ── Submit ─────────────────────────────────────────────────── */
    .btn-signin {
      width:100%;background:var(--deep);color:#fff;border:none;
      border-radius:13px;padding:.82rem;font-family:'DM Sans',sans-serif;
      font-size:1rem;font-weight:700;letter-spacing:.035em;cursor:pointer;
      display:flex;align-items:center;justify-content:center;gap:.55rem;
      box-shadow:0 6px 22px rgba(53,88,114,.38);
      margin-top:1.8rem;
      transition:background .2s,transform .15s,box-shadow .2s;
      animation:fadeSlide .6s .36s ease both;
    }
    .btn-signin:hover { background:var(--deep-hover);transform:translateY(-2px);box-shadow:0 10px 30px rgba(53,88,114,.44); }
    .btn-signin:active { transform:translateY(0);box-shadow:0 4px 14px rgba(53,88,114,.30); }

    /* ── Register prompt ────────────────────────────────────────── */
    .register-prompt { text-align:center;font-size:.87rem;color:var(--muted);margin-top:1.4rem;animation:fadeSlide .6s .55s ease both; }
    .register-prompt a { color:var(--deep);font-weight:700;text-decoration:none; }
    .register-prompt a:hover { text-decoration:underline; }

    @keyframes fadeSlide {
      from { opacity:0;transform:translateY(12px); }
      to   { opacity:1;transform:translateY(0); }
    }

    @media (max-width:500px) {
      .login-card { padding:2.2rem 1.6rem; }
      .heading-block h1 { font-size:1.75rem; }
    }
  </style>
</head>
<body>

  <div class="bg-orb bg-orb-1"></div>
  <div class="bg-orb bg-orb-2"></div>
  <div class="bg-orb bg-orb-3"></div>

  <div class="login-card">

    <!-- Brand -->
    <div class="brand-mark">
      <div class="brand-icon"><i class="bi bi-shield-lock-fill"></i></div>
      <span class="brand-name">MyApp</span>
    </div>

    <!-- Heading -->
    <div class="heading-block">
      <h1>Welcome back</h1>
      <p>Sign in to continue to your account.</p>
    </div>

    <?php
      // ── Pull flash data set by AuthController ─────────────────
      $success = session()->getFlashdata('success') ?? null;
      $error   = session()->getFlashdata('error')   ?? null;
      $errors  = session()->getFlashdata('errors')  ?? [];
    ?>

    <!-- Success banner (shown after register redirect) -->
    <?php if ($success): ?>
      <div class="flash-banner flash-success">
        <i class="bi bi-check-circle-fill"></i>
        <span><?= esc($success) ?></span>
      </div>
    <?php endif; ?>

    <!-- Global error banner (generic message) -->
    <?php if ($error): ?>
      <div class="flash-banner flash-error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span><?= esc($error) ?></span>
      </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="<?= base_url('login') ?>" method="POST" novalidate>
      <?= csrf_field() ?>

      <!-- Email -->
      <div class="field-group">
        <label class="field-label" for="email">Email Address</label>
        <div class="input-shell">
          <i class="bi bi-envelope-fill shell-icon"></i>
          <input
            type="email"
            id="email"
            name="email"
            class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
            placeholder="john@example.com"
            value="<?= esc(old('email')) ?>"
            autocomplete="email"
            required
          />
        </div>
        <?php if (!empty($errors['email'])): ?>
          <div class="field-error">
            <i class="bi bi-x-circle-fill"></i>
            <?= esc($errors['email']) ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Password -->
      <div class="field-group">
        <label class="field-label" for="password">Password</label>
        <div class="input-shell">
          <i class="bi bi-lock-fill shell-icon"></i>
          <input
            type="password"
            id="password"
            name="password"
            class="form-control has-toggle <?= !empty($errors['password']) ? 'is-invalid' : '' ?>"
            placeholder="Enter your password"
            autocomplete="current-password"
            required
          />
          <button type="button" class="pw-eye" id="pwToggle" aria-label="Toggle password visibility">
            <i class="bi bi-eye-fill" id="pwIcon"></i>
          </button>
        </div>
        <?php if (!empty($errors['password'])): ?>
          <div class="field-error">
            <i class="bi bi-x-circle-fill"></i>
            <?= esc($errors['password']) ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Submit -->
      <button type="submit" class="btn-signin">
        <i class="bi bi-box-arrow-in-right"></i>
        Sign In
      </button>

    </form>

    <!-- Register link -->
    <p class="register-prompt">
      Don't have an account?
      <a href="<?= base_url('register') ?>">Create one free</a>
    </p>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
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
  </script>

</body>
</html>