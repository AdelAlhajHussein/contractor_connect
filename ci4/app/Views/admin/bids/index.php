<h1>Bids</h1>

<form method="get" action="<?= site_url('admin/bids') ?>" style="margin-bottom:15px;">
    <input
            type="text"
            name="q"
            placeholder="Search project title or contractor email..."
            value="<?= esc($q ?? '') ?>"
    />

    <select name="status">
        <option value="">All Status</option>
        <?php foreach ($allowedStatuses as $s): ?>
            <option value="<?= esc($s) ?>" <?= (($status ?? '') === $s) ? 'selected' : '' ?>>
                <?= esc(ucfirst($s)) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Filter</button>
    <a href="<?= site_url('admin/bids') ?>">Reset</a>
</form>


<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>ID</th>
        <th>Project</th>
        <th>Contractor</th>
        <th>Status</th>
        <th>Bid Amount</th>
        <th>Total Cost</th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($bids)): ?>
        <tr><td colspan="7">No bids found.</td></tr>
    <?php else: ?>
        <?php foreach ($bids as $b): ?>
            <tr>
                <td><?= esc($b['id']) ?></td>
                <td><?= esc($b['project_title'] ?? 'N/A') ?></td>
                <td><?= esc($b['contractor_email'] ?? 'N/A') ?></td>
                <td><?= esc($b['status']) ?></td>
                <td><?= esc($b['bid_amount']) ?></td>
                <td><?= esc($b['total_cost']) ?></td>
                <td><?= esc($b['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
