<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="dashboard-container">

        <div class="dashboard-title">
             Dashboard
        </div>

        <div class="dashboard-grid">

            <div class="dashboard-item">
                <img src="<?= base_url('img/project.png') ?>" alt="Project logo">
                <a href="<?= site_url('homeowner/projects') ?>">Projects</a>
            </div>

        <div class="dashboard-item">
            <img src="<?= base_url('img/bid.png') ?>" alt="bid logo">
            <a href="<?= site_url('homeowner/bids') ?>">Bids</a>
        </div>

        <div class="dashboard-item">
            <img src="<?= base_url('img/browse.png') ?>" alt="browse logo">
            <a href="<?= site_url('homeowner/browse') ?>">Browse</a>
        </div>
        <div class="dashboard-item">
            <img src="<?= base_url('img/user_logo.png') ?>" alt="user logo">
            <a href="<?= site_url('homeowner/profile') ?>">Profile</a>
        </div>

         </div>
    </div>

<?= $this->endSection() ?>