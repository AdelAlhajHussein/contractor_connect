<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="users-container">

    <div class="users-title">Users</div>


    <form method="get" action="<?= site_url('admin/users') ?>" class="filter-form">

        <input
                type="text"
                name="q"
                placeholder="Search username, name, or email..."
                value="<?= esc($_GET['q'] ?? '') ?>"
        >

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

        <a href="<?= site_url('admin/users') ?>" class="reset-link">Reset</a>

    </form>


    <table class="users-table">

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

        <?php foreach ($users as $u): ?>

            <tr>

                <td><?= esc($u['id']) ?></td>

                <td><?= esc($u['username']) ?></td>

                <td><?= esc(trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? ''))) ?></td>

                <td><?= esc($u['email']) ?></td>

                <td><?= esc($u['role_id']) ?></td>

                <td><?= ($u['is_active'] == 1) ? 'Active' : 'Inactive' ?></td>

                <td><?= esc($u['created_at']) ?></td>

                <td>

                    <?php if ($u['is_active']): ?>

                        <a class="action-link"
                           href="<?= site_url('admin/users/toggle/'.$u['id']) ?>">
                            Deactivate
                        </a>

                    <?php else: ?>

                        <a class="action-link"
                           href="<?= site_url('admin/users/toggle/'.$u['id']) ?>">
                            Activate
                        </a>

                    <?php endif; ?>

                </td>

                <td>

                    <form method="post"
                          action="<?= site_url('admin/users/role/'.$u['id']) ?>">

                        <?= csrf_field() ?>

                        <select name="role_id">

                            <option value="1" <?= ($u['role_id']==1)?'selected':'' ?>>Admin</option>

                            <option value="2" <?= ($u['role_id']==2)?'selected':'' ?>>Homeowner</option>

                            <option value="3" <?= ($u['role_id']==3)?'selected':'' ?>>Contractor</option>

                        </select>

                        <button class="update-btn">Update</button>

                    </form>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>
<?= $this->endSection() ?>