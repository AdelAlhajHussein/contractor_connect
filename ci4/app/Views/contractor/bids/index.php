<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="users-container">

    <h1 class="users-title">My Bids</h1>


    <a href="<?= site_url('contractor/dashboard') ?>" class="btn btn-outline-danger">
        ← Back to Dashboard
    </a>

    <?php if (empty($bids)): ?>

        <div class="users-table" style="padding:20px;">
            <p>No bids found.</p>
        </div>

    <?php else: ?>

        <table class="users-table">
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

</div>

<?= $this->endSection() ?>