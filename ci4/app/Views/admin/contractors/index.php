<h1>Contractors</h1>
<form method="get" action="<?= site_url('admin/contractors') ?>" style="margin-bottom:15px;">
    <input
            type="text"
            name="q"
            placeholder="Search username, name, email, city..."
            value="<?= esc($_GET['q'] ?? '') ?>"
    />

    <select name="status">
        <option value="">All Status</option>
        <option value="1" <?= (($_GET['status'] ?? '') === '1') ? 'selected' : '' ?>>Active</option>
        <option value="0" <?= (($_GET['status'] ?? '') === '0') ? 'selected' : '' ?>>Inactive</option>
    </select>

    <button type="submit">Filter</button>
    <a href="<?= site_url('admin/contractors') ?>">Reset</a>
</form>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>City</th>
        <th>Province</th>
        <th>Status</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($contractors)) : ?>
        <?php foreach ($contractors as $c) : ?>
            <tr>
                <td><?= esc($c['id']) ?></td>
                <td><?= esc($c['username']) ?></td>
                <td><?= esc(trim(($c['first_name'] ?? '') . ' ' . ($c['last_name'] ?? ''))) ?></td>
                <td><?= esc($c['email']) ?></td>
                <td><?= esc($c['phone'] ?? '') ?></td>
                <td><?= esc($c['city'] ?? '') ?></td>
                <td><?= esc($c['province'] ?? '') ?></td>
                <td><?= ((int)$c['is_active'] === 1) ? 'Active' : 'Inactive' ?></td>
                <td><?= esc($c['created_at'] ?? '') ?></td>
                <td>
                    <?php if ((int)$c['is_active'] === 1): ?>
                        <a href="<?= site_url('admin/contractors/toggle/' . $c['id']) ?>"
                           onclick="return confirm('Suspend (deactivate) this contractor?')">Suspend</a>
                    <?php else: ?>
                        <a href="<?= site_url('admin/contractors/toggle/' . $c['id']) ?>"
                           onclick="return confirm('Activate this contractor?')">Activate</a>
                    <?php endif; ?>
                </td>

            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="9">No contractors found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
