<<<<<<< HEAD
<?php echo "<h1> login successfull </h1>"?>
<a href="<?= base_url('logout') ?>" class="btn btn-danger">Logout</a>
=======
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

</body>
</html>
>>>>>>> 29eee12bec008c94d52abe33b8833c7de7ff61a2
