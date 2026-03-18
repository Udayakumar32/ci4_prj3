<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

    <style>
        :root {
            --primary:    #355872;
            --accent:     #7AAACE;
            --light-blue: #9CD5FF;
            --bg:         #F7F8F0;
            --white:      #ffffff;
            --danger:     #e74c3c;
            --success-c:  #27ae60;
            --text:       #355872;
            --muted:      #7f8c8d;
            --border:     #dee2e6;
            --sidebar-w:  240px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
        }

        /* ── NAVBAR ─────────────────────────────────── */
        .navbar {
            background: var(--primary) !important;
            height: 56px;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 10px rgba(0,0,0,.2);
        }
        .navbar-brand { font-weight: 700; font-size: 1.05rem; letter-spacing: .3px; }
        .navbar .welcome-txt { color: rgba(255,255,255,.85); font-size: 13px; }
        .role-pill {
            background: rgba(255,255,255,.2);
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 20px;
            font-size: 10px;
            padding: 2px 8px;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: #fff;
            vertical-align: middle;
        }
        .btn-logout {
            font-size: 12px;
            padding: 4px 14px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,.5);
            color: #fff;
            background: transparent;
            transition: background .2s;
            text-decoration: none;
        }
        .btn-logout:hover { background: rgba(255,255,255,.15); color: #fff; }

        /* ── SIDEBAR ────────────────────────────────── */
        .sidebar {
            background: var(--white);
            width: var(--sidebar-w);
            min-height: calc(100vh - 56px);
            padding: 20px 12px;
            border-right: 1px solid var(--border);
            position: fixed;
            top: 56px;
            left: 0;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            color: var(--text);
            font-weight: 500;
            font-size: 13.5px;
            padding: 9px 14px;
            border-radius: 8px;
            margin-bottom: 3px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: background .18s, color .18s;
            text-decoration: none;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .sidebar .nav-link:hover  { background: #eaf4ff; }
        .sidebar .nav-link.active { background: var(--light-blue); font-weight: 600; }
        .sidebar .nav-link i { width: 18px; text-align: center; font-size: 13px; flex-shrink: 0; }

        /* ── MAIN ───────────────────────────────────── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            padding: 28px 30px;
            min-height: calc(100vh - 56px);
        }

        /* ── SECTION TOGGLE ─────────────────────────── */
        .page-section          { display: none; }
        .page-section.active   { display: block; }

        /* ── PAGE TITLE ─────────────────────────────── */
        .page-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--primary);
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-blue);
            margin-bottom: 22px;
        }
        .page-title i { color: var(--accent); }

        /* ── FLASH ALERTS ───────────────────────────── */
        .flash-alert {
            border-radius: 8px;
            font-size: 13.5px;
            padding: 10px 16px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── CARD ───────────────────────────────────── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0,0,0,.06);
            background: var(--white);
        }

        /* ── TOOLBAR ────────────────────────────────── */
        .toolbar { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .btn-export {
            font-size: 12px;
            padding: 5px 13px;
            border-radius: 6px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border: none;
            cursor: pointer;
            transition: opacity .15s;
        }
        .btn-export:hover { opacity: .85; }
        .btn-csv { background: var(--success-c); color: #fff; }
        .btn-pdf { background: var(--danger);    color: #fff; }

        /* ── TABLE ──────────────────────────────────── */
        #userTable thead th {
            background: var(--light-blue);
            color: var(--primary);
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
        }
        #userTable_wrapper .dataTables_filter input,
        #userTable_wrapper .dataTables_length select {
            border-radius: 6px;
            border: 1px solid var(--border);
            font-size: 13px;
            padding: 4px 10px;
        }

        /* ── ACTION BUTTONS ─────────────────────────── */
        .btn-act {
            padding: 4px 9px;
            font-size: 11.5px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: opacity .15s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-act:hover { opacity: .82; }
        .btn-edit-u   { background: var(--accent); color: #fff; }
        .btn-delete-u { background: var(--danger);  color: #fff; }

        /* ── PROFILE CARD ───────────────────────────── */
        .profile-wrap { max-width: 620px; }
        .profile-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 12px 12px 0 0;
            padding: 28px 28px 22px;
            color: #fff;
        }
        .profile-avatar {
            width: 68px; height: 68px;
            background: rgba(255,255,255,.22);
            border: 3px solid rgba(255,255,255,.45);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.9rem;
            margin-bottom: 12px;
        }
        .profile-header h4  { font-size: 1.15rem; font-weight: 700; margin-bottom: 5px; }
        .profile-header .rp { font-size: 11px; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.4); border-radius: 20px; padding: 2px 10px; text-transform: uppercase; letter-spacing: .5px; }

        .profile-body { padding: 6px 28px 22px; }
        .pf-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 0;
            border-bottom: 1px solid #f2f2f2;
        }
        .pf-row:last-child { border-bottom: none; }
        .pf-icon {
            width: 34px; height: 34px;
            background: #eaf4ff;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: var(--accent);
            font-size: 13px;
            flex-shrink: 0;
        }
        .pf-label { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; }
        .pf-value { font-size: 14px; font-weight: 500; color: var(--primary); }

        /* ── MODALS ─────────────────────────────────── */
        .modal-content { border-radius: 12px; overflow: hidden; border: none; }
        .modal-header  { border-radius: 0; padding: 14px 20px; }
        .modal-header h5 { font-size: 15px; font-weight: 700; margin: 0; }
        .modal-header .btn-close { filter: invert(1); opacity: .8; }
        .mh-edit   { background: var(--primary); color: #fff; }
        .mh-delete { background: var(--danger);  color: #fff; }
        .modal .form-label { font-size: 12px; font-weight: 600; color: var(--primary); margin-bottom: 4px; }
        .modal .form-control,
        .modal .form-select {
            border-radius: 7px;
            font-size: 13.5px;
            border: 1px solid var(--border);
        }
        .modal .form-control:focus,
        .modal .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(122,170,206,.18);
        }
        .btn-save-edit   { background: var(--accent); color: #fff; border: none; font-size: 13px; padding: 6px 18px; border-radius: 7px; font-weight: 600; }
        .btn-save-edit:hover { background: var(--primary); color: #fff; }
        .btn-do-delete   { background: var(--danger);  color: #fff; border: none; font-size: 13px; padding: 6px 18px; border-radius: 7px; font-weight: 600; }

        /* ── RESPONSIVE ─────────────────────────────── */
        @media (max-width: 767px) {
            .sidebar  { display: none; }
            .main-wrap { margin-left: 0; padding: 18px 14px; }
        }
    </style>
</head>
<body>

<!-- ══════════════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════════════ -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid d-flex align-items-center justify-content-between">

        <a class="navbar-brand text-white" href="#">
            <i class="fas fa-users-cog me-2"></i>AdminPanel
        </a>

        <div class="d-flex align-items-center gap-3">
            <span class="welcome-txt">
                Welcome, <strong><?= esc(session()->get('username') ?? 'User') ?></strong>
                <span class="role-pill ms-1"><?= esc(session()->get('role') ?? 'user') ?></span>
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
    <?php if(session()->getFlashdata('success')): ?>
        <div class="flash-alert alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
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
                <input type="date" id="dateFilter"
                       class="form-control form-control-sm"
                       style="width:158px;border-radius:6px;font-size:13px;"
                       title="Filter by registration date">
            </div>
        </div>

        <div class="card p-4">
            <table id="userTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Mobile</th>
                        <th>Role</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 1; foreach ($users as $u): ?>
                    <?php
                        $g     = strtolower($u['gender'] ?? '');
                        $gIcon = $g === 'male' ? 'fa-mars' : ($g === 'female' ? 'fa-venus' : 'fa-genderless');
                        $gClr  = $g === 'male' ? '#3498db' : ($g === 'female' ? '#e91e8c' : '#999');
                    ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= esc($u['username']) ?></td>
                        <td><?= esc($u['email'] ?? 'N/A') ?></td>
                        <td>
                            <i class="fas <?= $gIcon ?>" style="color:<?= $gClr ?>;margin-right:4px"></i>
                            <?= esc(ucfirst($u['gender'] ?? 'N/A')) ?>
                        </td>
                        <td><?= esc($u['phone_number'] ?? 'N/A') ?></td>
                        <td>
                            <span class="badge"
                                  style="background:<?= ($u['role'] ?? '') === 'admin' ? 'var(--primary)' : 'var(--accent)' ?>;
                                         font-size:11px;">
                                <?= esc(ucfirst($u['role'] ?? 'user')) ?>
                            </span>
                        </td>
                        <td data-order="<?= strtotime($u['created_at']) ?>">
                            <?= date('M d, Y', strtotime($u['created_at'])) ?>
                        </td>
                        <td>
                            <?php if (session()->get('role') === 'admin'): ?>
                                <!-- EDIT -->
                                <button class="btn-act btn-edit-u"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-id="<?= $u['id'] ?>"
                                        data-username="<?= esc($u['username']) ?>"
                                        data-email="<?= esc($u['email'] ?? '') ?>"
                                        data-phone="<?= esc($u['phone_number'] ?? '') ?>"
                                        data-gender="<?= esc(strtolower($u['gender'] ?? '')) ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <!-- DELETE -->
                                <button class="btn-act btn-delete-u"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-id="<?= $u['id'] ?>"
                                        data-username="<?= esc($u['username']) ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            <?php else: ?>
                                <span class="badge bg-secondary" style="font-size:11px;">View Only</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </section><!-- /dashboard -->


    <!-- ── MY PROFILE SECTION ───────────────────────── -->
    <section class="page-section" id="sec-profile">

        <h1 class="page-title">
            <i class="fas fa-user-circle me-2"></i>My Profile
        </h1>

        <?php
            // $currentUser is passed from the controller (fresh DB record)
            $cu = $currentUser ?? [];
        ?>

        <div class="profile-wrap card">
            <div class="profile-header">
                <div class="profile-avatar"><i class="fas fa-user"></i></div>
                <h4><?= esc($cu['username'] ?? session()->get('username') ?? 'User') ?></h4>
                <span class="rp"><?= esc($cu['role'] ?? session()->get('role') ?? 'user') ?></span>
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
                            <?= esc($cu['role'] ?? 'user') ?>
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
                <form id="editForm" method="post" action="">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="POST">

                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" id="eUsername" required
                               minlength="3" maxlength="100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="eEmail" required>
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
                    <input type="hidden" name="_method" value="POST">
                    <button type="submit" class="btn-do-delete">
                        <i class="fas fa-trash me-1"></i>Yes, Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>


<!-- ══════════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════════ -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {

    /* ──────────────────────────────────────────────
       1.  SIDEBAR NAVIGATION
    ────────────────────────────────────────────── */
    $('[data-target]').on('click', function () {
        const t = $(this).data('target');
        $('[data-target]').removeClass('active');
        $(this).addClass('active');
        $('.page-section').removeClass('active');
        $('#sec-' + t).addClass('active');
    });


    /* ──────────────────────────────────────────────
       2.  DATATABLES  (jQuery-powered)
    ────────────────────────────────────────────── */
    const dt = $('#userTable').DataTable({
        paging:       true,
        lengthChange: true,
        searching:    true,
        ordering:     true,
        info:         true,
        autoWidth:    false,
        responsive:   true,
        pageLength:   10,
        order:        [[6, 'desc']],   // default sort: newest first
        columnDefs: [
            { orderable: false, targets: 7 }   // Actions column — not sortable
        ],
        language: {
            search:       '<i class="fas fa-search"></i>',
            searchPlaceholder: 'Search users...',
            lengthMenu:   'Show _MENU_ entries',
            info:         'Showing _START_–_END_ of _TOTAL_ users',
            paginate: { previous: '‹', next: '›' }
        }
    });


    /* ──────────────────────────────────────────────
       3.  DATE FILTER  (filters Registered column)
    ────────────────────────────────────────────── */
    $('#dateFilter').on('change', function () {
        const val = this.value;   // "YYYY-MM-DD"
        if (!val) {
            dt.columns(6).search('').draw();
            return;
        }
        // Match the display format "Jan 01, 2025"
        const d   = new Date(val + 'T00:00:00');
        const fmt = d.toLocaleDateString('en-US', { month:'short', day:'2-digit', year:'numeric' });
        dt.columns(6).search(fmt).draw();
    });


    /* ──────────────────────────────────────────────
       4.  CSV EXPORT  (client-side, all filtered rows)
    ────────────────────────────────────────────── */
    $('#btnCSV').on('click', function () {
        const strip = html => $('<div>').html(html).text().trim();
        const rows  = [['#','Username','Email','Gender','Mobile','Role','Registered']];

        dt.rows({ search: 'applied' }).every(function () {
            const d = this.data();
            rows.push([
                strip(d[0]), strip(d[1]), strip(d[2]),
                strip(d[3]), strip(d[4]), strip(d[5]), strip(d[6])
            ]);
        });

        const csv  = rows.map(r => r.map(c => '"' + String(c).replace(/"/g,'""') + '"').join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url  = URL.createObjectURL(blob);
        const a    = Object.assign(document.createElement('a'), {
            href: url, download: 'users_' + new Date().toISOString().slice(0,10) + '.csv'
        });
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });


    /* ──────────────────────────────────────────────
       5.  PDF EXPORT  (client-side via jsPDF)
    ────────────────────────────────────────────── */
    $('#btnPDF').on('click', function () {
        const { jsPDF } = window.jspdf;
        const doc  = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
        const strip = html => $('<div>').html(html).text().trim();

        // Header text
        doc.setFontSize(15);
        doc.setTextColor(53, 88, 114);
        doc.text('User Directory', 14, 15);
        doc.setFontSize(9);
        doc.setTextColor(140, 140, 140);
        doc.text('Exported: ' + new Date().toLocaleDateString('en-US',{day:'numeric',month:'long',year:'numeric'}), 14, 21);

        const head = [['#','Username','Email','Gender','Mobile','Role','Registered']];
        const body = [];
        dt.rows({ search: 'applied' }).every(function () {
            const d = this.data();
            body.push([
                strip(d[0]), strip(d[1]), strip(d[2]),
                strip(d[3]), strip(d[4]), strip(d[5]), strip(d[6])
            ]);
        });

        doc.autoTable({
            head, body,
            startY: 25,
            headStyles: {
                fillColor:  [156, 213, 255],
                textColor:  [53, 88, 114],
                fontStyle:  'bold',
                fontSize:   9.5
            },
            bodyStyles:           { fontSize: 9, textColor: [55, 55, 55] },
            alternateRowStyles:   { fillColor: [247, 248, 240] },
            margin:               { left: 14, right: 14 }
        });

        doc.save('users_' + new Date().toISOString().slice(0,10) + '.pdf');
    });


    /* ──────────────────────────────────────────────
       6.  EDIT MODAL — pre-fill fields from data-*
    ────────────────────────────────────────────── */
    $('#editModal').on('show.bs.modal', function (e) {
        const b = $(e.relatedTarget);
        $('#eUsername').val(b.data('username'));
        $('#eEmail').val(b.data('email'));
        $('#ePhone').val(b.data('phone'));
        $('#eGender').val(b.data('gender'));
        // Point form to the correct CI4 route
        $('#editForm').attr('action', '<?= base_url('users/update') ?>/' + b.data('id'));
    });


    /* ──────────────────────────────────────────────
       7.  DELETE MODAL — confirm + set action
    ────────────────────────────────────────────── */
    $('#deleteModal').on('show.bs.modal', function (e) {
        const b = $(e.relatedTarget);
        $('#dUsername').text(b.data('username'));
        $('#deleteForm').attr('action', '<?= base_url('users/delete') ?>/' + b.data('id'));
    });


    /* ──────────────────────────────────────────────
       8.  AUTO-DISMISS flash alerts after 4s
    ────────────────────────────────────────────── */
    setTimeout(() => $('.flash-alert').fadeOut(400), 4000);

});
</script>

</body>
</html>