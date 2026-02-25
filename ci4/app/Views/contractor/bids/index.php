<h1>My Bids</h1>

<?php if (empty($bids)): ?>
    <p>No bids found.</p>
<?php else: ?>
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th>Bid ID</th>
            <th>Project</th>
            <th>Bid Amount</th>
            <th>Bid Status</th>
            <th>Project Status</th>
        </tr>

        <?php foreach ($bids as $row): ?>
            <tr>
                <td><?= esc($row['bid_id']) ?></td>
                <td><?= esc($row['project_title']) ?></td>
                <td>$<?= esc($row['bid_amount']) ?></td>
                <td><?= esc($row['bid_status']) ?></td>
                <td><?= esc($row['project_status']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
