
<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="users-container">

    <h1 class="users-title">Bids</h1>

    <form method="get"
          action="<?= site_url('admin/bids') ?>"
          class="filter-form">

        <input
                type="text"
                name="q"
                placeholder="Search project title or contractor email..."
                value="<?= esc($q ?? '') ?>"
        />

        <select name="status">

            <option value="">All Status</option>

            <?php foreach ($allowedStatuses as $s): ?>

                <option value="<?= esc($s) ?>"
                    <?= (($status ?? '') === $s) ? 'selected' : '' ?>>

                    <?= esc(ucfirst($s)) ?>

                </option>

            <?php endforeach; ?>

        </select>

        <button type="submit">Filter</button>

        <a href="<?= site_url('admin/bids') ?>"
           class="reset-link">
            Reset
        </a>
        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-danger">
            ← Back to Dashboard
        </a>

    </form>


    <table class="users-table">

        <thead>

        <tr>
            <th>ID</th>
            <th>Project</th>
            <th>Contractor</th>
            <th>Status</th>
            <th>Bid Amount</th>
            <th>Total Cost</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>

        </thead>


        <tbody>

        <?php if (empty($bids)): ?>

            <tr>

                <td colspan="8">
                    No bids found.
                </td>

            </tr>

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

                    <td>

                        <a class="action-link"
                           href="<?= site_url('admin/bids/view/' . $b['id']) ?>">
                            View
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

        </tbody>

    </table>

</div>
<?= $this->endSection() ?>