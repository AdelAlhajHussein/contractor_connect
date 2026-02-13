<h1>Project Details</h1>

<p><a href="<?= site_url('admin/projects') ?>">← Back to Projects</a></p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr><th>ID</th><td><?= esc($project['id']) ?></td></tr>
    <tr><th>Title</th><td><?= esc($project['title']) ?></td></tr>
    <tr><th>Status</th><td><?= esc($project['status']) ?></td></tr>
    <tr><th>Category</th><td><?= esc($project['category_name'] ?? '') ?></td></tr>

    <tr><th>Description</th><td><?= esc($project['description']) ?></td></tr>

    <tr><th>Address</th><td><?= esc($project['address'] ?? '') ?></td></tr>
    <tr><th>Contact Phone</th><td><?= esc($project['contact_phone'] ?? '') ?></td></tr>

    <tr><th>Budget</th><td><?= esc($project['budget_min']) ?> - <?= esc($project['budget_max']) ?></td></tr>

    <tr><th>Start Date</th><td><?= esc($project['start_date'] ?? '') ?></td></tr>
    <tr><th>End Date</th><td><?= esc($project['end_date'] ?? '') ?></td></tr>
    <tr><th>Deadline</th><td><?= esc($project['deadline_date'] ?? '') ?></td></tr>

    <tr><th>Completed At</th><td><?= esc($project['completed_at'] ?? '') ?></td></tr>

    <tr><th>Created</th><td><?= esc($project['created_at'] ?? '') ?></td></tr>
    <tr><th>Updated</th><td><?= esc($project['updated_at'] ?? '') ?></td></tr>

    <tr>
        <th>Homeowner</th>
        <td>
            <?= esc(trim(($project['homeowner_first_name'] ?? '') . ' ' . ($project['homeowner_last_name'] ?? ''))) ?>
            (<?= esc($project['homeowner_username'] ?? '') ?>)
            <br>
            Email: <?= esc($project['homeowner_email'] ?? '') ?><br>
            Phone: <?= esc($project['homeowner_phone'] ?? '') ?>
        </td>
    </tr>
</table>

<p style="margin-top:15px;">
    <?php if ($project['status'] !== 'cancelled' && $project['status'] !== 'completed'): ?>
        <a href="<?= site_url('admin/projects/cancel/' . $project['id']) ?>"
           onclick="return confirm('Cancel this project?')">Cancel Project</a>
    <?php endif; ?>

    <?php if ($project['status'] === 'bidding_open'): ?>
        | <a href="<?= site_url('admin/projects/close-bidding/' . $project['id']) ?>"
             onclick="return confirm('Force close bidding for this project?')">Close Bidding</a>
    <?php endif; ?>
</p>
