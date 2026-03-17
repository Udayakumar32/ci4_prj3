<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Register Account<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/register.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
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

    <h2>Create Account</h2>
    <p class="subtitle">Join us — it only takes a moment.</p>

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
          style="background:var(--light);border-color:var(--border); border-radius:10px 0 0 10px;color:var(--muted); <?= ! empty($errors['phone_number']) ? 'border-color:var(--err-text)!important;' : '' ?>">
      <i class="bi bi-telephone-fill"></i>
    </span>
    
    <input type="tel" 
           id="phone" 
           name="phone"
           class="form-control <?= ! empty($errors['phone_number']) ? 'is-invalid' : '' ?>"
           style="border-radius:0 10px 10px 0;"
           placeholder="9876543210"
           value="<?= old('phone') ?>"
           inputmode="numeric"
           maxlength="10"
           pattern="[0-9]{10}"
           oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);"
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
  <?= $this->endSection() ?>
</div>
<?= $this->section('page_scripts') ?>
    <script src="<?= base_url('assets/js/register.js') ?>"></script>
<?= $this->endSection() ?>
