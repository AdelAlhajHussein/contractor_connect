<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="users-container">

        <h1 class="users-title">My Profile</h1>

        <p>
            <a class="action-link" href="<?= site_url('Homeowner/profile/edit') ?>">Edit Profile</a>
        </p>

        <table class="users-table" border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th width="200">Username</th>
                <td><?= esc($user['username'] ?? '') ?></td>
            </tr>
            <tr>
                <th>First Name</th>
                <td><?= esc($profile['first_name'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?= esc($profile['last_name'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?= esc($profile['address'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Payment Info</th>
                <td><?= esc($profile['payment_info'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= esc($user['email']) ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?= esc($user['phone'] ?? '') ?></td>
            </tr>
        </table>

        <p style="margin-top:16px;">
            <a class="action-link" href="<?= site_url('homeowner/dashboard') ?>">← Back to Dashboard</a>
        </p>

    </div>

<?= $this->endSection() ?>