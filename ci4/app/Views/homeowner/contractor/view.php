<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="users-container">

        <h1 class="users-title">Contractor Profile</h1>

        <p>
            <strong>Email:</strong>
            <?= esc($contractor['email']) ?>
        </p>





        <!-- CONNECT BUTTON (opens email app) -->
        <div class="btn-group">
            <a class="btn btn-primary"
               href="mailto:<?= esc($contractor['email']) ?>?subject= Project Inquiry from contractor connect website ">
                Connect via Email
            </a>
        </div>

        <a  class="btn btn-outline-danger" href="<?= site_url('homeowner/bids') ?>">

            <-- Back to Bids

        </a>
    </div>


<?= $this->endSection() ?>