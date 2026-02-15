<!-- Use main page layout -->
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h1>Home page</h1>
    <ul style="list-style: none">
        <li >
            <a href="<?= site_url('admin/dashboard') ?>">Admin Dashboard</a>
        </li>
        <li>
            <a href="<?= site_url('homeowner/dashboard') ?>">Homeowner Dashboard</a>
        </li>
        <li>
            <a href="<?= site_url('contractor/dashboard') ?>">Contractor Dashboard</a>
        </li>
    </ul>

<?= $this->endSection() ?>