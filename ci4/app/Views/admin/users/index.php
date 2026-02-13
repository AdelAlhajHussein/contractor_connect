<h1>Users</h1>

<form method="get" action="<?= site_url('admin/users') ?>" style="margin-bottom:15px;">
    <input
            type="text"
            name="q"
            placeholder="Search username, name, or email..."
            value="<?= esc($_GET['q'] ?? '') ?>"
    />

    <select name="role_id">
        <option value="">All Roles</option>
        <option value="1" <?= (($_GET['role_id'] ?? '') === '1') ? 'selected' : '' ?>>Admin</option>
        <option value="2" <?= (($_GET['role_id'] ?? '') === '2') ? 'selected' : '' ?>>Homeowner</option>
        <option value="3" <?= (($_GET['role_id'] ?? '') === '3') ? 'selected' : '' ?>>Contractor</option>
    </select>

    <select name="status">
        <option value="">All Status</option>
        <option value="1" <?= (($_GET['status'] ?? '') === '1') ? 'selected' : '' ?>>Active</option>
        <option value="0" <?= (($_GET['status'] ?? '') === '0') ? 'selected' : '' ?>>Inactive</option>
    </select>

    <button type="submit">Filter</button>
    <a href="<?= site_url('admin/users') ?>">Reset</a>
</form>

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
        <th>Actions</th>
        <th>Change Role</th>
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
                <td>
                    <?php if ((int)$u['is_active'] === 1): ?>
                        <a href="<?= site_url('admin/users/toggle/' . $u['id']) ?>"
                           onclick="return confirm('Deactivate this user?')">Deactivate</a>
                    <?php else: ?>
                        <a href="<?= site_url('admin/users/toggle/' . $u['id']) ?>"
                           onclick="return confirm('Activate this user?')">Activate</a>
                    <?php endif; ?>
                </td>
                <td>
                    <form method="post" action="<?= site_url('admin/users/role/' . $u['id']) ?>">
                        <?= csrf_field() ?>
                        <select name="role_id">
                            <option value="1" <?= ((int)$u['role_id'] === 1) ? 'selected' : '' ?>>Admin</option>
                            <option value="2" <?= ((int)$u['role_id'] === 2) ? 'selected' : '' ?>>Homeowner</option>
                            <option value="3" <?= ((int)$u['role_id'] === 3) ? 'selected' : '' ?>>Contractor</option>
                        </select>
                        <button type="submit" onclick="return confirm('Change this user role?')">Update</button>
                    </form>
                </td>


            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="7">No users found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
