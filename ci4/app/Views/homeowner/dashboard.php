<?= $this->extend('layouts/dashboard'); ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/homeowner-dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="dashboard-container">

    <div class="dashboard-title">
        Homeowner Dashboard
    </div>


    <ul>
        <li><a href="<?= site_url('homeowner/projects/create') ?>"><strong>+ Post a New Project</strong></a></li>

        <li><a href="<?= site_url('homeowner/projects') ?>">My Projects (Track Progress)</a></li>
        <li><a href="<?= site_url('homeowner/bids') ?>">View Bids Received</a></li>

        <li><a href="<?= site_url('homeowner/search-contractors') ?>">Browse Contractors</a></li>

        <li><a href="<?= site_url('homeowner/payments') ?>">Payment History</a></li>
        <li><a href="<?= site_url('homeowner/reviews') ?>">My Reviews</a></li>

        <li><a href="<?= site_url('homeowner/profile') ?>">Account Settings</a></li>
    </ul>

    <!-- Dashboard data table -->
    <?= $this->section('content') ?>
    <h2>My Projects</h2>

    <?= view('components/data_table', [
        'headers' => ['Project Name', 'Date Posted', 'Status', 'Actions'],
        'rows'    => $project_rows
    ]) ?>


</div>

<?= $this->endSection() ?>
