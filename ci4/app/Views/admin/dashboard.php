<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/admin-dashboard.css') ?>">
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="dashboard-container">

    <div class="dashboard-title">
        <?= $title ?? 'Dashboard' ?>
    </div>

    <div class="dashboard-grid">
        <!-- Default to user data on load -->
        <div class="dashboard-item">
            <img src="<?= base_url('img/user_logo.png') ?>" alt="user logo">
            <a href="#" class="ajax-link" data-target="users">Users</a>
        </div>
        <div class="dashboard-item">
            <img src="<?= base_url('img/contractor.png') ?>" alt="contractors logo">
            <a href="#" class="ajax-link" data-target="contractors">Contractors</a>
        </div>
        <div class="dashboard-item">
            <img src="<?= base_url('img/report.png') ?>" alt="report logo">
            <a href="#" class="ajax-link" data-target="reports">Admin Reports</a>
        </div>

    </div>
    <div id="table-content">
        <p>Select a view</p>
    </div>
<!--
    <div class="dashboard-item">
        <img src="<?= base_url('img/contractor.png') ?>" alt="Contractors logo">
        <a href="/index.php/admin/contractors">Contractors</a>
    </div>
    <div class="dashboard-item">
        <img src="<?= base_url('img/homeowner.png') ?>" alt="homeowner logo">
        <a href="<?= site_url('admin/homeowners') ?>">Homeowners</a>
    </div>
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
        <img src="<?= base_url('img/payment.png') ?>" alt="">
        <a href="<?= site_url('admin/payments') ?>">Payments & Subscriptions</a>
    </div>
    <div class="dashboard-item">
        <img src="<?= base_url('img/report.png') ?>" alt="">
        <a href="<?= site_url('admin/reports') ?>">Admin Reports</a>
    </div>
-->
</div>

<script>

    document.querySelectorAll('.ajax-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('data-target');
            const resultDiv = document.getElementById('table-content');

            fetch(`<?= site_url('admin/dashboard/get_table/') ?>${target}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error: Route not found');
                    return response.text();
                })
                .then(html => {
                    resultDiv.innerHTML = html;
                })
                .catch(err => {
                    resultDiv.innerHTML = `<p>Error: ${err.message}</p>`;
                });
        });
    });
</script>

<?= $this->endSection() ?>



