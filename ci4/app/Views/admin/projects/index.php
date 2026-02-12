<h1>Projects</h1>

<form method="get" action="<?= site_url('admin/projects') ?>" style="margin-bottom:15px;">

    <input
            type="text"
            name="q"
            placeholder="Search project title..."
            value="<?= esc($_GET['q'] ?? '') ?>"
    />

    <select name="status">
        <option value="">All Status</option>
        <option value="bidding_open" <?= (($_GET['status'] ?? '') === 'bidding_open') ? 'selected' : '' ?>>Bidding</option>
        <option value="in_progress" <?= (($_GET['status'] ?? '') === 'in_progress') ? 'selected' : '' ?>>In Progress</option>
        <option value="completed" <?= (($_GET['status'] ?? '') === 'completed') ? 'selected' : '' ?>>Completed</option>
        <option value="cancelled" <?= (($_GET['status'] ?? '') === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
    </select>

    <button type="submit">Filter</button>
    <a href="<?= site_url('admin/projects') ?>">Reset</a>
</form>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Homeowner</th>
        <th>Category</th>
        <th>Status</th>
        <th>Budget</th>
        <th>Deadline</th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>

    <?php if (!empty($projects)) : ?>
        <?php foreach ($projects as $p) : ?>
            <tr>
                <td><?= esc($p['id']) ?></td>
                <td><?= esc($p['title']) ?></td>
                <td><?= esc(trim(($p['homeowner_first_name'] ?? '') . ' ' . ($p['homeowner_last_name'] ?? ''))) ?></td>
                <td><?= esc($p['category_name'] ?? '') ?></td>
                <td><?= esc($p['status']) ?></td>
                <td><?= esc($p['budget_min']) ?> - <?= esc($p['budget_max']) ?></td>
                <td><?= esc($p['deadline_date'] ?? '') ?></td>
                <td><?= esc($p['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="8">No projects found.</td>
        </tr>
    <?php endif; ?>

    </tbody>
</table>
