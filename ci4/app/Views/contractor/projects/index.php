<h1>My Projects</h1>

<?php if (empty($myProjects)): ?>
    <p>No projects found for this contractor.</p>
<?php else: ?>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th>Title</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Bid</th>
            <th>Status</th>
        </tr>

        <?php foreach ($myProjects as $row): ?>
            <tr>
                <td><?= esc($row['title']) ?></td>
                <td><?= esc($row['start_date'] ?? '') ?></td>
                <td><?= esc($row['end_date'] ?? '') ?></td>
                <td>$<?= esc($row['bid_amount']) ?></td>
                <td><?= esc($row['project_status']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
