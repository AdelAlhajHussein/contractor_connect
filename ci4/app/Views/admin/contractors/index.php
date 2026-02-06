<h1>Admin - Contractors</h1>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Created</th>
    </tr>

    <?php if (empty($contractors)): ?>
        <tr><td colspan="7">No contractors found.</td></tr>
    <?php else: ?>
        <?php foreach ($contractors as $c): ?>
            <tr>
                <td><?= esc($c['id']) ?></td>
                <td><?= esc($c['username']) ?></td>
                <td><?= esc(trim($c['first_name'].' '.$c['last_name'])) ?></td>
                <td><?= esc($c['email']) ?></td>
                <td><?= esc($c['phone'] ?? '') ?></td>
                <td><?= ((int)$c['is_active'] === 1) ? 'Active' : 'Suspended' ?></td>
                <td><?= esc($c['created_at'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
