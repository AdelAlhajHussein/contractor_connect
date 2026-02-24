<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="users-container">

        <h1 class="users-title">Homeowner - Bids</h1>

        <?php if (empty($bids)): ?>
            <p>No bids yet for your projects.</p>
        <?php else: ?>
            <table class="users-table" border="1" cellpadding="8" cellspacing="0">
                <thead>
                <tr>
                    <th>Project ID</th>
                    <th>Title</th>
                    <th>Bid</th>
                    <th>Bid ID</th>
                    <th>Contractor</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($bids as $row): ?>
                    <tr>
                        <td><?= esc($row['project_id']) ?></td>
                        <td><?= esc($row['title']) ?></td>
                        <td>$<?= esc(number_format((float)$row['bid_amount'], 2)) ?></td>
                        <td><?= esc($row['bid_id']) ?></td>
                        <td><?= esc(trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''))) ?></td>
                        <td><?= esc($row['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>

<?= $this->endSection() ?>