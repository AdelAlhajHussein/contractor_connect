<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="users-container">

    <h1 class="users-title">My Projects</h1>

    <a href="<?= site_url('contractor/dashboard') ?>" class="btn btn-outline-danger">
        ← Back to Dashboard
    </a>

    <?php if (empty($myProjects)): ?>

        <div class="users-table" style="padding:20px;">
            <p>No projects found for this contractor.</p>
        </div>

    <?php else: ?>

        <table class="users-table">
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

</div>


<?= $this->endSection() ?>