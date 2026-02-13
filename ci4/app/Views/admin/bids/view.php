<h1>Bid Details</h1>

<p>
    <a href="<?= site_url('admin/bids') ?>">← Back to Bids</a>
</p>

<table border="1" cellpadding="8" cellspacing="0" width="100%" style="margin-bottom:15px;">
    <tr><th width="200">Bid ID</th><td><?= esc($bid['id']) ?></td></tr>
    <tr><th>Project</th><td><?= esc($bid['project_title'] ?? 'N/A') ?></td></tr>
    <tr><th>Contractor</th><td><?= esc($bid['contractor_email'] ?? 'N/A') ?></td></tr>
    <tr><th>Status</th><td><?= esc($bid['status']) ?></td></tr>
    <tr><th>Bid Amount</th><td><?= esc($bid['bid_amount']) ?></td></tr>
    <tr><th>Total Cost (stored)</th><td><?= esc($bid['total_cost']) ?></td></tr>
</table>

<h2>Tasks</h2>

<?php if (empty($tasks)): ?>
    <p>No tasks for this bid.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th>Description</th>
            <th>Est Minutes</th>
            <th>Materials</th>
            <th>Labour</th>
            <th>HST</th>
            <th>Task Total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tasks as $t): ?>
            <?php
            $m = (float)($t['materials_cost'] ?? 0);
            $l = (float)($t['labour_cost'] ?? 0);
            $h = (float)($t['hst_cost'] ?? 0);
            $tt = $m + $l + $h;
            ?>
            <tr>
                <td><?= esc($t['task_order']) ?></td>
                <td><?= esc($t['description']) ?></td>
                <td><?= esc($t['est_minutes']) ?></td>
                <td><?= esc(number_format($m, 2)) ?></td>
                <td><?= esc(number_format($l, 2)) ?></td>
                <td><?= esc(number_format($h, 2)) ?></td>
                <td><?= esc(number_format($tt, 2)) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="2">Totals (from tasks)</th>
            <th><?= esc($taskTotals['minutes'] ?? 0) ?></th>
            <th><?= esc(number_format((float)($taskTotals['materials'] ?? 0), 2)) ?></th>
            <th><?= esc(number_format((float)($taskTotals['labour'] ?? 0), 2)) ?></th>
            <th><?= esc(number_format((float)($taskTotals['hst'] ?? 0), 2)) ?></th>
            <th><?= esc(number_format((float)($taskTotals['total'] ?? 0), 2)) ?></th>
        </tr>
        </tfoot>
    </table>
<?php endif; ?>

<hr>

<?php if (!in_array($bid['status'], ['accepted', 'rejected', 'withdrawn'], true)): ?>
    <a href="<?= site_url('admin/bids/withdraw/' . $bid['id']) ?>"
       onclick="return confirm('Withdraw this bid?')">
        Mark as Withdrawn
    </a>
<?php else: ?>
    <p><b>Action locked:</b> bid is <?= esc($bid['status']) ?>.</p>
<?php endif; ?>
