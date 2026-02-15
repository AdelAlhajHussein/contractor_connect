<?php $this->extend('layouts/dashboard') ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/admin-dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div>
    <div>Admin Reports</div>

    <div class="filter-form" style="display: flex; gap: 10px; margin-bottom: 20px;">
        <button class="ajax-report" data-target="users">User Report</button>
        <button class="ajax-report" data-target="projects">Project Report</button>
        <button class="ajax-report" data-target="bids">Bid Report</button>
    </div>

    <div id="report-content">
        <p style="padding: 20px; color: #666;">Select a report type above to generate data.</p>
    </div>
</div>

<script>
    document.querySelectorAll('.ajax-report').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const target = this.getAttribute('data-target');
            const container = document.getElementById('report-content');

            container.innerHTML = "<p>Generating report...</p>";

            fetch(`<?= site_url('admin/reports/get_report/') ?>${target}`)
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;
                })
                .catch(err => {
                    container.innerHTML = "<p>Error loading report.</p>";
                });
        });
    });
</script>
<?= $this->endSection() ?>

<?php /*
    <div>
        <h2>User Summary</h2>
        <table class="users-table">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td>
                            <?php
                                $role = (int)($row['role_id'] ?? 0);
                                echo match( $role ){
                                    1=> 'Admin',
                                    2=> 'Homeowner',
                                    3=> 'Contractor',
                                    default => 'Unknown'

                                };
                            ?>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
</div>
<div>



</div>

<h2>Users</h2>
<p><b>Total Users:</b> <?= esc($totalUsers) ?></p>

<table border="1" cellpadding="8" cellspacing="0" width="500">
    <thead>
    <tr>
        <th>Role</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($usersByRole as $row): ?>
        <tr>
            <td>
                <?php
                $role = (int)($row['role_id'] ?? 0);
                echo match ($role) {
                    1 => 'Admin',
                    2 => 'Homeowner',
                    3 => 'Contractor',
                    default => 'Unknown',
                };
                ?>
            </td>
            <td><?= esc($row['total']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<hr>

<h2>Projects</h2>
<p><b>Total Projects:</b> <?= esc($totalProjects) ?></p>

<table border="1" cellpadding="8" cellspacing="0" width="500">
    <thead>
    <tr>
        <th>Status</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($projectsByStatus as $row): ?>
        <tr>
            <td><?= esc($row['status'] ?? 'N/A') ?></td>
            <td><?= esc($row['total']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<hr>

<h2>Bids</h2>
<p><b>Total Bids:</b> <?= esc($totalBids) ?></p>

<table border="1" cellpadding="8" cellspacing="0" width="500">
    <thead>
    <tr>
        <th>Status</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($bidsByStatus as $row): ?>
        <tr>
            <td><?= esc($row['status'] ?? 'N/A') ?></td>
            <td><?= esc($row['total']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
*/?>