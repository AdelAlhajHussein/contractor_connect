<h1>Homeowners</h1>

<form method="get" action="<?= site_url('admin/homeowners') ?>" style="margin-bottom:15px;">
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
    <a href="<?= site_url('admin/homeowners') ?>">Reset</a>
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
    <?php if (!empty($homeowners)) : ?>
        <?php foreach ($homeowners as $h) : ?>
            <tr>
                <td><?= esc($h['id']) ?></td>
                <td><?= esc($h['username']) ?></td>
                <td><?= esc(trim(($h['first_name'] ?? '') . ' ' . ($h['last_name'] ?? ''))) ?></td>
                <td><?= esc($h['email']) ?></td>
                <td><?= esc($h['phone'] ?? '') ?></td>
                <td><?= esc($h['city'] ?? '') ?></td>
                <td><?= esc($h['province'] ?? '') ?></td>
                <td><?= ((int)$h['is_active'] === 1) ? 'Active' : 'Inactive' ?></td>
                <td><?= esc($h['created_at'] ?? '') ?></td>
                <td>
                    <?php if ((int)$h['is_active'] === 1): ?>
                        <a href="<?= site_url('admin/homeowners/toggle/' . $h['id']) ?>"
                           onclick="return confirm('Deactivate this homeowner?')">Deactivate</a>
                    <?php else: ?>
                        <a href="<?= site_url('admin/homeowners/toggle/' . $h['id']) ?>"
                           onclick="return confirm('Activate this homeowner?')">Activate</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="10">No homeowners found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
