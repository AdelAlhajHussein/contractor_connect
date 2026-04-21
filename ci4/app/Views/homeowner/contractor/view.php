<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="users-container">

        <h1>Contractor Profile</h1>

        <p>
            <strong>Name:</strong>
            <?= esc($contractor['username']) ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?= esc($contractor['email']) ?>
        </p>


        <a href="<?= site_url('homeowner/bids') ?>"
           class="btn btn-secondary">

            Back to Bids

        </a>

    </div>

<?= $this->endSection() ?>