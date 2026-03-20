<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<!-- ══════════════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════════════ -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid d-flex align-items-center justify-content-between">

        <a class="navbar-brand text-white" href="#">
            <i class="fas fa-users-cog me-2"></i> User Management
        </a>

        <div class="d-flex align-items-center gap-3">
            <span class="welcome-txt">
                Welcome, <strong><?= esc(session()->get('username') ?? 'User') ?></strong>
                <span class="role-pill ms-1"><?= esc(session()->get('user_type') ?? 'user') ?></span>
            </span>
            <a href="<?= base_url('logout') ?>" class="btn-logout">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
        </div>

    </div>
</nav>

<!-- ══════════════════════════════════════════════════                                                                            
     SIDEBAR
══════════════════════════════════════════════════ -->
<nav class="sidebar">
    <ul class="nav flex-column">
        <li>
            <button class="nav-link active" data-target="dashboard">
                <i class="fas fa-home"></i> Dashboard
            </button>
        </li>
        <li>
            <button class="nav-link" data-target="profile">
                <i class="fas fa-user-circle"></i> My Profile
            </button>
        </li>
    </ul>
</nav>

<!-- ══════════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════════ -->
<main class="main-wrap">

    <!-- Flash messages (success / error from redirects) -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-alert alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash-alert alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>


    <!-- ── DASHBOARD SECTION ────────────────────────── -->
    <section class="page-section active" id="sec-dashboard">

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h1 class="page-title mb-0">
                <i class="fas fa-users me-2"></i>User Directory
            </h1>
            <div class="toolbar">
                <button class="btn-export btn-csv" id="btnCSV">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
                <button class="btn-export btn-pdf" id="btnPDF">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <input type="date" id="dateFrom"
                    class="form-control form-control-sm"
                    style="width:145px;border-radius:6px;font-size:13px;"
                    title="From date">

                <span style="color:var(--muted);font-size:13px;">to</span>

                <input type="date" id="dateTo"
                    class="form-control form-control-sm"
                    style="width:145px;border-radius:6px;font-size:13px;"
                    title="To date">

                <button id="clearDates" class="btn-export"
                    style="background:var(--muted);color:#fff;font-size:12px;padding:5px 11px;">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>

        <div class="card p-4">
            <table id="userTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>gender</th>
                        <th>Mobile</th>
                        <th>Role</th>
                        <th>Join Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </section><!-- /dashboard -->


    <!-- ── MY PROFILE SECTION ───────────────────────── -->
 <!-- ── MY PROFILE SECTION ───────────────────────── -->
<section class="page-section" id="sec-profile">

    <h1 class="page-title">
        <i class="fas fa-user-circle me-2"></i>My Profile
    </h1>

    <?php $cu = $currentUser ?? []; ?>

    <div class="profile-wrap card">
        <div class="profile-header">

            <!-- Profile Avatar — shows image if exists, else default icon -->
            <div class="profile-avatar">
                <?php if (!empty($cu['profile_image']) && file_exists(FCPATH . 'uploads/profiles/' . $cu['profile_image'])): ?>
                    <img src="<?= base_url('uploads/profiles/' . esc($cu['profile_image'])) ?>"
                         alt="Profile Photo"
                         style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                <?php else: ?>
                    <i class="fas fa-user"></i>
                <?php endif; ?>
            </div>

            <h4><?= esc($cu['username'] ?? session()->get('username') ?? 'User') ?></h4>
            <span class="rp"><?= esc($cu['user_type'] ?? session()->get('user_type') ?? 'user') ?></span>

            <!-- Edit Profile Button -->
            <button class="btn-edit-profile mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#editProfileModal">
                <i class="fas fa-user-edit me-1"></i> Edit Profile
            </button>

        </div>

        <div class="profile-body">

            <div class="pf-row">
                <div class="pf-icon"><i class="fas fa-user"></i></div>
                <div>
                    <div class="pf-label">Username</div>
                    <div class="pf-value"><?= esc($cu['username'] ?? 'N/A') ?></div>
                </div>
            </div>

            <div class="pf-row">
                <div class="pf-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="pf-label">Email Address</div>
                    <div class="pf-value"><?= esc($cu['email'] ?? 'N/A') ?></div>
                </div>
            </div>

            <div class="pf-row">
                <div class="pf-icon"><i class="fas fa-phone"></i></div>
                <div>
                    <div class="pf-label">Phone Number</div>
                    <div class="pf-value"><?= esc($cu['phone_number'] ?? 'N/A') ?></div>
                </div>
            </div>

            <div class="pf-row">
                <div class="pf-icon"><i class="fas fa-venus-mars"></i></div>
                <div>
                    <div class="pf-label">Gender</div>
                    <div class="pf-value"><?= esc(ucfirst($cu['gender'] ?? 'N/A')) ?></div>
                </div>
            </div>

            <div class="pf-row">
                <div class="pf-icon"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <div class="pf-label">Role</div>
                    <div class="pf-value" style="text-transform:capitalize">
                        <?= esc($cu['user_type'] ?? 'user') ?>
                    </div>
                </div>
            </div>

            <div class="pf-row">
                <div class="pf-icon"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <div class="pf-label">Member Since</div>
                    <div class="pf-value">
                        <?= isset($cu['created_at']) ? date('F d, Y', strtotime($cu['created_at'])) : 'N/A' ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section><!-- /profile -->
</main>


<!-- ══════════════════════════════════════════════════
     EDIT USER MODAL
══════════════════════════════════════════════════ -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header mh-edit">
                <h5><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <form id="editForm" method="post" action="<?= base_url('users/update') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="POST">

                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" id="eUsername" required
                            minlength="3" maxlength="100">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone_number" id="ePhone" maxlength="20">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender" id="eGender">
                                <option value="">-- Select --</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save-edit">
                            <i class="fas fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- ══════════════════════════════════════════════════
     DELETE CONFIRM MODAL
══════════════════════════════════════════════════ -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

            <div class="modal-header mh-delete">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4 text-center">
                <i class="fas fa-user-times fa-2x mb-3" style="color:var(--danger)"></i>
                <p style="font-size:14px;margin-bottom:4px;">
                    Delete user <strong id="dUsername"></strong>?
                </p>
                <p style="font-size:12px;color:var(--muted);">This action cannot be undone.</p>
            </div>

            <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
                <button type="button" class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>

                <form id="deleteForm" method="post" action="">
                    <?= csrf_field() ?>
                    <input type="hidden" name="user_id" id="deleteUserId" value="">
                    <!-- NO trigger class here — just a plain submit button -->
                    <button type="submit" class="btn-do-delete">
                        <i class="fas fa-trash me-1"></i>Yes, Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
    <!-- ══════════════════════════════════════════════════
     EDIT PROFILE MODAL
══════════════════════════════════════════════════ -->
</div>
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header mh-edit">
                <h5><i class="fas fa-user-edit me-2"></i>Edit My Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <form id="editProfileForm" method="post"
                      action="<?= base_url('profile/update') ?>"
                      enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- Profile Image Upload -->
                    <div class="mb-3 text-center">
                        <div class="profile-preview mb-2">
                            <?php if (!empty($cu['profile_image']) && file_exists(FCPATH . 'uploads/profiles/' . $cu['profile_image'])): ?>
                                <img id="profilePreview"
                                     src="<?= base_url('uploads/profiles/' . esc($cu['profile_image'])) ?>"
                                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);">
                            <?php else: ?>
                                <div id="profilePreview"
                                     style="width:80px;height:80px;border-radius:50%;background:#eaf4ff;
                                            display:inline-flex;align-items:center;justify-content:center;
                                            border:3px solid var(--accent);font-size:2rem;color:var(--accent);">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <label class="form-label d-block" style="font-size:12px;">
                            Profile Photo
                            <span style="color:var(--muted);font-size:11px;">(optional)</span>
                        </label>
                        <input type="file" class="form-control form-control-sm"
                               name="profile_image" id="profileImageInput"
                               accept="image/jpeg,image/png,image/webp"
                               style="font-size:12px;">
                        <small style="color:var(--muted);font-size:11px;">
                            JPG, PNG or WEBP. Max 2MB.
                        </small>
                    </div>

                    <hr style="border-color:#f0f0f0;">

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label">
                            Username <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control"
                               name="username" id="epUsername"
                               value="<?= esc($cu['username'] ?? '') ?>"
                               required minlength="2" maxlength="21">
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Phone -->
                        <div class="col-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control"
                                   name="phone_number" id="epPhone"
                                   value="<?= esc($cu['phone_number'] ?? '') ?>"
                                   maxlength="20">
                        </div>
                        <!-- Gender -->
                        <div class="col-6">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender" id="epGender">
                                <option value="">-- Select --</option>
                                <option value="male"   <?= ($cu['gender'] ?? '') === 'male'   ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= ($cu['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other"  <?= ($cu['gender'] ?? '') === 'other'  ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-save-edit">
                            <i class="fas fa-save me-1"></i>Save Changes
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<div id="appConfig" 
     data-delete-url="<?= base_url('users/delete') ?>"
     data-update-url="<?= base_url('users/update') ?>"
     data-datatable-url="<?= base_url('dashboard/datatable') ?>"
     style="display:none;">
</div>
    <?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script>
    const csrfTokenName = '<?= csrf_token() ?>';
    let csrfHash      = '<?= csrf_hash() ?>';
</script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
<?= $this->endSection() ?>