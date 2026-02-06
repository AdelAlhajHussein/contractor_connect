<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<form method="post" action="<?= site_url('login') ?>">
    <?= csrf_field() ?>

    <div>
        <label>Username</label><br>
        <input type="text" name="username" required>
    </div>

    <div style="margin-top:10px;">
        <label>Password</label><br>
        <input type="password" name="password" required>
    </div>

    <div style="margin-top:15px;">
        <button type="submit">Login</button>
    </div>
</form>

</body>
</html>
