<link rel="stylesheet" href="<?= base_url('css/admin-reports.css') ?>">

<div class="reports-container">

    <h1 class="reports-title">Admin Reports</h1>


    <h2 class="report-section-title">Users</h2>

    <p class="report-summary">
        <b>Total Users:</b> <?= esc($totalUsers) ?>
    </p>


    <table class="report-table">

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


    <hr class="report-divider">


    <h2 class="report-section-title">Projects</h2>

    <p class="report-summary">
        <b>Total Projects:</b> <?= esc($totalProjects) ?>
    </p>


    <table class="report-table">

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


    <hr class="report-divider">


    <h2 class="report-section-title">Bids</h2>

    <p class="report-summary">
        <b>Total Bids:</b> <?= esc($totalBids) ?>
    </p>


    <table class="report-table">

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

</div>
