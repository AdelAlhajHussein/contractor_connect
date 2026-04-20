<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="users-container">

    <h1 class="users-title">Project Details</h1>

    <div class="users-table" style="padding:20px;">

        <p><strong>Title:</strong> <?= esc($project['title'] ?? '') ?></p>
        <p><strong>Description:</strong> <?= esc($project['description'] ?? '') ?></p>
        <p><strong>Status:</strong> <?= esc($project['status'] ?? '') ?></p>
        <p><strong>Budget:</strong>
            <?= esc($project['budget_min'] ?? '') ?> - <?= esc($project['budget_max'] ?? '') ?>
        </p>

        <hr>

        <a  href="<?= site_url('contractor/bids/create/' . $project['id']) ?>" class="btn btn-primary">
            Place Bid
        </a>

        <a class="btn btn-outline-danger" href="<?= site_url('contractor/browse') ?>">
            Back to Browse
        </a>

    </div>

</div>
<?= $this->endSection() ?>