<h1>Suspicious Rating Activity</h1>

<p>
    <a href="<?= site_url('admin/ratings') ?>">← Back to Ratings</a>
</p>

<h2>Same Homeowner Rated Same Contractor Multiple Times</h2>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Contractor</th>
        <th>Homeowner</th>
        <th>Count</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($repeatPairs)): ?>
        <tr><td colspan="3">No suspicious repeat pairs found.</td></tr>
    <?php else: ?>
        <?php foreach ($repeatPairs as $row): ?>
            <tr>
                <td><?= esc($row['contractor_email'] ?? $row['contractor_id']) ?></td>
                <td><?= esc($row['homeowner_email'] ?? $row['home_owner_id']) ?></td>
                <td><?= esc($row['rating_count']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<h2>Contractors With Rating Burst (Last 7 Days)</h2>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Contractor</th>
        <th>Ratings in last 7 days</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($recentBurst)): ?>
        <tr><td colspan="2">No bursts found.</td></tr>
    <?php else: ?>
        <?php foreach ($recentBurst as $row): ?>
            <tr>
                <td><?= esc($row['contractor_email'] ?? $row['contractor_id']) ?></td>
                <td><?= esc($row['last_7_days']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
