<?= $this->extend('layouts/dashboard'); ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/homeowner-dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="dashboard-container">

    <div class="dashboard-title">
        Homeowner Dashboard
    </div>


    <!-- Homeowner data table -->
    <div id="table-content">
        <?= view('components/dashboard-table', [
            'headers' => $headers ?? ['Title', 'Budget', 'Deadline', 'Status', 'Actions'], // default headers
            'rows'    => $project_rows ?? []
        ]) ?>
    </div>


    <ul>
        <li>
            <a href="<?= site_url('homeowner/projects/create') ?>"><strong>+ Post a New Project</strong></a></li>
        <li>
            <a href="<?= site_url('homeowner/projects') ?>" class="ajax-link" data-target="projects">My Projects (Track Progress)</a></li>
        <li>
            <a href="<?= site_url('homeowner/bids') ?>">View Bids Received</a>
        </li>

        <li><a href="<?= site_url('homeowner/search-contractors') ?>">Browse Contractors</a></li>

        <li><a href="<?= site_url('homeowner/payments') ?>">Payment History</a></li>
        <li><a href="<?= site_url('homeowner/reviews') ?>">My Reviews</a></li>

        <li><a href="<?= site_url('homeowner/profile') ?>">Account Settings</a></li>
    </ul>

</div>

<?= $this->endSection() ?>

<script>
    // Ajax link to generate content
    document.querySelectorAll('.ajax-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('data-target');

            fetch(`<?= site_url('homeowner/dashboard/get_table/') ?>${target}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('table-content').innerHTML = html;
                });
        });
    });

</script>
