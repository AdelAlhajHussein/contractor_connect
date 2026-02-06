<h1>Users</h1>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role ID</th>
        <th>Status</th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($users)) : ?>
        <?php foreach ($users as $u) : ?>
            <tr>
                <td><?= esc($u['id']) ?></td>
                <td><?= esc($u['username']) ?></td>
                <td><?= esc(trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? ''))) ?></td>
                <td><?= esc($u['email']) ?></td>
                <td><?= esc($u['role_id']) ?></td>
                <td><?= ($u['is_active'] == 1) ? 'Active' : 'Inactive' ?></td>
                <td><?= esc($u['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="7">No users found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
