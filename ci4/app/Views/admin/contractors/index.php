<h1>Contractors</h1>

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
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="9">No contractors found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
