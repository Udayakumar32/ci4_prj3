<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>

  <style>
    :root {
      --deep:   #355872;
      --mid:    #7AAACE;
      --light:  #9CD5FF;
      --pale:   #F7F8F0;
      --text:   #1a2f3f;
      --muted:  #4a7a9b;
      --border: #a8cfe8;
    }

    *, *::before, *::after { box-sizing: border-box; }

    body {
      min-height: 100vh;
      background: linear-gradient(135deg, var(--pale) 0%, var(--light) 55%, var(--mid) 100%);
      font-family: 'DM Sans', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    /* Decorative blobs */
    body::before, body::after {
      content: '';
      position: fixed;
      border-radius: 50%;
      opacity: .22;
      pointer-events: none;
    }
    body::before {
      width: 460px; height: 460px;
      background: var(--deep);
      top: -140px; left: -130px;
    }
    body::after {
      width: 320px; height: 320px;
      background: var(--mid);
      bottom: -90px; right: -90px;
    }

    /* Card */
    .login-card {
      background: rgba(255,255,255,.84);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,.65);
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(53,88,114,.18);
      width: 100%;
      max-width: 460px;
      padding: 2.8rem 3rem;
      position: relative;
      z-index: 1;
    }

    /* Brand */
    .brand-bar {
      display: flex;
      align-items: center;
      gap: .65rem;
      margin-bottom: 2rem;
    }
    .brand-icon {
      width: 42px; height: 42px;
      background: var(--deep);
      border-radius: 12px;
      display: grid;
      place-items: center;
      color: #fff;
      font-size: 1.3rem;
    }
    .brand-name {
      font-family: 'DM Serif Display', serif;
      font-size: 1.4rem;
      color: var(--text);
    }

    /* Heading */
    .welcome-block { margin-bottom: 2rem; }
    .welcome-block h2 {
      font-family: 'DM Serif Display', serif;
      font-size: 2rem;
      color: var(--text);
      margin-bottom: .25rem;
      line-height: 1.15;
    }
    .welcome-block p {
      color: var(--muted);
      font-size: .9rem;
      margin: 0;
    }

    /* Labels */
    .form-label {
      font-weight: 600;
      font-size: .8rem;
      color: var(--text);
      letter-spacing: .05em;
      text-transform: uppercase;
      margin-bottom: .38rem;
    }

    /* Inputs */
    .form-control {
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: .68rem .95rem;
      font-size: .95rem;
      color: var(--text);
      background: rgba(156,213,255,.12);
      transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .form-control:focus {
      border-color: var(--deep);
      box-shadow: 0 0 0 3.5px rgba(122,170,206,.28);
      background: #fff;
      outline: none;
    }
    .form-control::placeholder { color: #8ab8d4; }

    /* Input with icon */
    .input-icon-wrap { position: relative; }
    .input-icon-wrap .input-icon {
      position: absolute;
      left: .9rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      font-size: 1rem;
      pointer-events: none;
    }
    .input-icon-wrap .form-control { padding-left: 2.6rem; }

    /* Password toggle */
    .pw-wrapper { position: relative; }
    .pw-wrapper .form-control { padding-left: 2.6rem; padding-right: 2.8rem; }
    .pw-toggle {
      position: absolute;
      right: .75rem;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--muted);
      cursor: pointer;
      padding: 0;
      font-size: 1.1rem;
      line-height: 1;
      transition: color .2s;
    }
    .pw-toggle:hover { color: var(--deep); }

    /* Remember + Forgot row */
    .meta-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.6rem;
    }
    .form-check-input {
      width: 1rem; height: 1rem;
      border: 1.5px solid var(--border);
      border-radius: 4px;
      cursor: pointer;
    }
    .form-check-input:checked {
      background-color: var(--deep);
      border-color: var(--deep);
    }
    .form-check-input:focus { box-shadow: 0 0 0 3px rgba(122,170,206,.28); }
    .form-check-label {
      font-size: .87rem;
      color: var(--muted);
      cursor: pointer;
    }
    .forgot-link {
      font-size: .87rem;
      color: var(--deep);
      font-weight: 600;
      text-decoration: none;
    }
    .forgot-link:hover { text-decoration: underline; }

    /* Submit */
    .btn-login {
      background: var(--deep);
      color: #fff;
      border: none;
      border-radius: 12px;
      padding: .8rem;
      font-size: 1rem;
      font-weight: 700;
      letter-spacing: .03em;
      width: 100%;
      transition: background .2s, transform .15s, box-shadow .2s;
      box-shadow: 0 6px 20px rgba(53,88,114,.35);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
    }
    .btn-login:hover {
      background: #2a4760;
      transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(53,88,114,.40);
      color: #fff;
    }
    .btn-login:active { transform: translateY(0); }

    /* Divider */
    .or-divider {
      display: flex;
      align-items: center;
      gap: .8rem;
      margin: 1.5rem 0;
      color: var(--muted);
      font-size: .82rem;
    }
    .or-divider::before, .or-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border);
    }

    /* Social buttons */
    .btn-social {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .55rem;
      width: 100%;
      padding: .65rem;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      background: rgba(247,248,240,.7);
      color: var(--text);
      font-size: .9rem;
      font-weight: 600;
      text-decoration: none;
      transition: background .2s, border-color .2s, transform .15s;
    }
    .btn-social:hover {
      background: var(--pale);
      border-color: var(--mid);
      transform: translateY(-1px);
      color: var(--text);
    }
    .btn-social img { width: 18px; height: 18px; }

    /* Register link */
    .register-link {
      text-align: center;
      font-size: .87rem;
      color: var(--muted);
      margin-top: 1.3rem;
    }
    .register-link a {
      color: var(--deep);
      font-weight: 700;
      text-decoration: none;
    }
    .register-link a:hover { text-decoration: underline; }

    @media (max-width: 480px) {
      .login-card { padding: 2rem 1.5rem; }
      .welcome-block h2 { font-size: 1.7rem; }
    }
  </style>
</head>
<body>

<div class="login-card">
  <!-- Heading -->
  <div class="welcome-block">
    <h2>Welcome back </h2>
    <p>Sign in to continue to your account.</p>
  </div>

  <form action="#" method="POST" novalidate>

    <!-- Email -->
    <div class="mb-3">
      <label class="form-label" for="email">Email Address</label>
      <div class="input-icon-wrap">
        <i class="bi bi-envelope-fill input-icon"></i>
        <input type="email" id="email" name="email" class="form-control"
               placeholder="john@example.com" required/>
      </div>
    </div>

    <!-- Password -->
    <div class="mb-3">
      <label class="form-label" for="password">Password</label>
      <div class="pw-wrapper input-icon-wrap">
        <i class="bi bi-lock-fill input-icon"></i>
        <input type="password" id="password" name="password" class="form-control"
               placeholder="Enter your password" required/>
        <button type="button" class="pw-toggle" id="togglePw" aria-label="Toggle password">
          <i class="bi bi-eye-fill" id="pwIcon"></i>
        </button>
      </div>
    </div>
    <!-- Submit -->
    <button type="submit" class="btn-login">
      <i class="bi bi-box-arrow-in-right"></i> Sign In
    </button>

  </form>

 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Show / hide password
  document.getElementById('togglePw').addEventListener('click', function () {
    const input = document.getElementById('password');
    const icon  = document.getElementById('pwIcon');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
    } else {
      input.type = 'password';
      icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
    }
  });
</script>
</body>
</html>