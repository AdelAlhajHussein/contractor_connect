<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin - Users</title>
</head>
<body>
<h1>Admin - Users</h1>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($users)): ?>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= esc($u['id']) ?></td>
                <td><?= esc($u['username'] ?? '') ?></td>
                <td><?= esc(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? '')) ?></td>
                <td><?= esc($u['email'] ?? '') ?></td>
                <td><?= esc($u['role'] ?? '') ?></td>
                <td><?= (!empty($u['is_active']) ? 'Active' : 'Inactive') ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">No users found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</body>
</html>
