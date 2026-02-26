<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="dashboard-container">

    <div class="dashboard-title">
        Admin Dashboard
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-item">
            <img src="<?= base_url('img/user_logo.png') ?>" alt="user logo">
            <a href="<?= site_url('admin/users') ?>">Users</a>
        </div>
<!-- remove this //just for presentation
        <div class="dashboard-item">
            <img src="<?= base_url('img/contractor.png') ?>" alt="Contractors logo">
            <a href="<?= site_url('admin/contractors') ?>">Contractors</a>
        </div>

        <div class="dashboard-item">
            <img src="<?= base_url('img/homeowner.png') ?>" alt="homeowner logo">
            <a href="<?= site_url('admin/homeowners') ?>">Homeowners</a>
        </div>

-->
        <div class="dashboard-item">

            <img src="<?= base_url('img/homeowner.png') ?>" alt="homeowner logo">
            <a href="<?= site_url('admin/contractors') ?>">Homeowners </a>

        </div>

        <div class="dashboard-item">
            <img src="<?= base_url('img/contractor.png') ?>" alt="Contractors logo">
            <a href="<?= site_url('admin/homeowners') ?>">Contractors</a>

        </div>
<!-- remove this line above for fixing the problem-->

        <div class="dashboard-item">
            <img src="<?= base_url('img/project.png') ?>" alt="Project logo">
            <a href="<?= site_url('admin/projects') ?>">Projects</a>
        </div>

        <div class="dashboard-item">
            <img src="<?= base_url('img/bid.png') ?>" alt="bid logo">
            <a href="<?= site_url('admin/bids') ?>">Bids</a>
        </div>

        <div class="dashboard-item">
            <img src="<?= base_url('img/rate.png') ?>" alt="Ratings & Reviews logo">
            <a href="<?= site_url('admin/ratings') ?>">Ratings & Reviews</a>
        </div>

        <div class="dashboard-item">
            <img src="<?= base_url('img/categories.png') ?>" alt="Categories logo">
            <a href="<?= site_url('admin/categories') ?>">Categories</a>
        </div>

        <div class="dashboard-item">
            <img src="<?= base_url('img/payment.png') ?>" alt="Payments & Subscriptions">
            <a href="<?= site_url('admin/payments') ?>">Payments & Subscriptions</a>
        </div>

        <div class="dashboard-item">
            <img src="<?= base_url('img/report.png') ?>" alt="Admin Reports">
            <a href="<?= site_url('admin/reports') ?>">Admin Reports</a>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
