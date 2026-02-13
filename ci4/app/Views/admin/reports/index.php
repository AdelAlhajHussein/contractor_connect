<h1>Admin Reports</h1>

<h2>Users</h2>
<p><b>Total Users:</b> <?= esc($totalUsers) ?></p>

<table border="1" cellpadding="8" cellspacing="0" width="500">
    <thead>
    <tr>
        <th>Role</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($usersByRole as $row): ?>
        <tr>
            <td>
                <?php
                $role = (int)($row['role_id'] ?? 0);
                echo match ($role) {
                    1 => 'Admin',
                    2 => 'Homeowner',
                    3 => 'Contractor',
                    default => 'Unknown',
                };
                ?>
            </td>
            <td><?= esc($row['total']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<hr>

<h2>Projects</h2>
<p><b>Total Projects:</b> <?= esc($totalProjects) ?></p>

<table border="1" cellpadding="8" cellspacing="0" width="500">
    <thead>
    <tr>
        <th>Status</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($projectsByStatus as $row): ?>
        <tr>
            <td><?= esc($row['status'] ?? 'N/A') ?></td>
            <td><?= esc($row['total']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<hr>

<h2>Bids</h2>
<p><b>Total Bids:</b> <?= esc($totalBids) ?></p>

<table border="1" cellpadding="8" cellspacing="0" width="500">
    <thead>
    <tr>
        <th>Status</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($bidsByStatus as $row): ?>
        <tr>
            <td><?= esc($row['status'] ?? 'N/A') ?></td>
            <td><?= esc($row['total']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
