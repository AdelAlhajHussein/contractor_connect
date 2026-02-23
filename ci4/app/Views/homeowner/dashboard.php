<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1>Dashboard</h1>


<hr>

<!-- Top nav buttons -->
<div style="margin-bottom: 15px;">
    <a href="<?= site_url('homeowner/projects') ?>">Projects</a> |
    <a href="<?= site_url('homeowner/bids') ?>">Bids</a> |
    <a href="<?= site_url('homeowner/browse') ?>">Browse</a> |
    <a href="<?= site_url('homeowner/profile') ?>">Profile</a>

</div>

<hr>

<h2>Profile</h2>

<p>
    <a href="<?= site_url('Homeowner/profile/edit') ?>">Edit Profile</a>
</p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
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
</table>
<?= $this->endSection() ?>