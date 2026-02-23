<h1>My Projects</h1>

<?php if (empty($projects)): ?>
    <p>No projects found.</p>
<?php else: ?>
    <p>
        <a href="<?= site_url('homeowner/projects/new') ?>">Add Project</a>
    </p>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>
            <th>Details</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($projects as $p): ?>
            <tr>
                <td><?= esc($p['id']) ?></td>
                <td><?= esc($p['title']) ?></td>
                <td><?= esc($p['status']) ?></td>
                <td><?= esc($p['created_at']) ?></td>
                <td>
                    <a href="<?= site_url('homeowner/bids/' . $p['id']) ?>">View Bids</a>
                </td>
                <td>
                    <a href="<?= site_url('homeowner/projects/' . $p['id']) ?>">View</a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
<?php endif; ?>
