<?php header('Cache-Control: private, no-store, max-age=0, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

    <h1>Welcome, <?= esc(session()->get('username')) ?>!</h1>
    <p>You are logged in as: <?= esc(session()->get('email')) ?></p>
    <p>Role: <?= esc(session()->get('user_type')) ?></p>

    <!-- Logout form (POST is best practice) -->
    <form action="<?= base_url('logout') ?>" method="POST">
        <?= csrf_field() ?>
        <button type="submit">Logout</button>
    </form>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>User Directory</h2>
        <span class="badge bg-primary text-uppercase">Logged in as: <?= session()->get('username') ?></span>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Registered On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users) && is_array($users)): ?>
                        <?php foreach ($users as $index => $user): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><strong><?= esc($user['username']) ?></strong></td>
                                <td><?= esc($user['email']) ?></td>
                                <td><?= esc($user['phone_number']) ?></td>
                                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No other registered users found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
</div>
<script>
</script>
</body>
</html>
