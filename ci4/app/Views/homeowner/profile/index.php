<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My Profile</title>
</head>
<body>

<h1>My Profile</h1>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Username</th>
        <td><?= esc($user['username']) ?></td>
    </tr>

    <tr>
        <th>First Name</th>
        <td><?= esc($user['first_name']) ?></td>
    </tr>

    <tr>
        <th>Last Name</th>
        <td><?= esc($user['last_name']) ?></td>
    </tr>

    <tr>
        <th>Email</th>
        <td><?= esc($user['email']) ?></td>
    </tr>

    <tr>
        <th>Phone</th>
        <td><?= esc($user['phone'] ?? '') ?></td>
    </tr>
</table>

<p style="margin-top:16px;">
    <a href="<?= site_url('homeowner/dashboard') ?>">← Back to Dashboard</a>
</p>

</body>
</html>
