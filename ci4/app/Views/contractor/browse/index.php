<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="users-container">

    <h1 class="users-title">Browse Projects</h1>

    <a href="<?= site_url('contractor/dashboard') ?>" class="btn btn-outline-danger">
        ← Back to Dashboard
    </a>


    <?php if (empty($projects)): ?>

        <div class="users-table" style="padding:20px;">
            <p>No available projects to bid on right now.</p>
        </div>

    <?php else: ?>

        <table class="users-table">
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Budget</th>
                <th>Description</th>
            </tr>

            <?php foreach ($projects as $p): ?>
                <tr>
                    <td>
                        <a class="action-link" href="<?= site_url('contractor/browse/' . $p['project_id']) ?>">
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

</div>
<?= $this->endSection() ?>