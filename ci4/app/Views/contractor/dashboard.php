<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/admin-dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>


    <div class="dashboard-container">

        <div class="dashboard-title">
            Contractor Dashboard
        </div>

        <div class="dashboard-grid">


                <div class="dashboard-item">
                    <img src="<?= base_url('img/project.png') ?>" alt="Project logo">
                    <a class="action-link" href="<?= site_url('contractor/projects') ?>">Projects</a>
                </div>

                <div class="dashboard-item">
                    <img src="<?= base_url('img/bid.png') ?>" alt="bid logo">
                    <a class="action-link" href="<?= site_url('contractor/bids') ?>">Bids</a>
                </div>
                <div class="dashboard-item">
                    <img src="<?= base_url('img/browse.png') ?>" alt="browse logo">
                    <a class="action-link" href="<?= site_url('contractor/browse') ?>">Browse</a>
                </div>
                <div class="dashboard-item">
                    <img src="<?= base_url('img/user_logo.png') ?>" alt="user logo">
                    <a class="action-link" href="<?= site_url('contractor/profile') ?>">Profile</a>
                </div>

    </div>



<?= $this->endSection() ?>