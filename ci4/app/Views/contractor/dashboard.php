<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/admin-dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>


    <div class="dashboard-container">

        <div class="dashboard-title">
            Homeowner Dashboard
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





    <hr>
        <div style="margin: 15px 0;">
            <strong>PROFILE</strong>
        </div>

        <table class="users-table" border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th style="width: 200px;">Username</th>
                <td><?= esc($user['username'] ?? '—') ?></td>
            </tr>
            <tr>
                <th>First Name</th>
                <td><?= esc($user['first_name'] ?? '—') ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?= esc($user['last_name'] ?? '—') ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?= esc($profileAddress ?? '—') ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?= esc($user['phone'] ?? '—') ?></td>
            </tr>
        </table>

        <div style="margin-top: 25px;">
            <h3 style="margin-bottom:10px;">CERTIFICATIONS / LICENSES</h3>

            <table class="users-table" border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <th style="width: 300px;">Title</th>
                    <th style="width: 200px;">Issued By</th>
                    <th style="width: 150px;">Date Issued</th>
                    <th>Attachment</th>
                </tr>

                <tr>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                </tr>
            </table>
        </div>

    </div>

<?= $this->endSection() ?>