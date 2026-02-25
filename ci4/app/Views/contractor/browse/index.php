<h1>Browse Projects</h1>

<?php if (empty($projects)): ?>
    <p>No available projects to bid on right now.</p>
<?php else: ?>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Budget</th>
            <th>Description</th>
        </tr>

        <?php foreach ($projects as $p): ?>
            <tr>
                <td>
                    <a href="<?= site_url('contractor/browse/' . $p['project_id']) ?>">
                        <?= esc($p['title']) ?>
                    </a>
                </td>
                <td><?= esc($p['status']) ?></td>
                <td>
                    <?= esc($p['budget_min'] ?? '') ?> - <?= esc($p['budget_max'] ?? '') ?>
                </td>
                <td><?= esc($p['description']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
