<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Login Account<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

  <div class="bg-orb bg-orb-1"></div>
  <div class="bg-orb bg-orb-2"></div>
  <div class="bg-orb bg-orb-3"></div>

  <div class="login-card">
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
<?= $this->endSection() ?>
  </div>
<?= $this->section('page_scripts') ?>
    <script src="<?= base_url('assets/js/login.js') ?>"></script>
<?= $this->endSection() ?>