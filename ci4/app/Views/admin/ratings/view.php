<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-reports.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<div class="reports-container">

    <h1 class="reports-title">Rating Details</h1>

    <p>
        <a class="action-link"
           href="<?= site_url('admin/ratings') ?>">
            ← Back to Ratings
        </a>
    </p>

    <table class="report-table" style="width:100%;">

        <tr>
            <th width="220">Rating ID</th>
            <td><?= esc($rating['id']) ?></td>
        </tr>

        <tr>
            <th>Project</th>
            <td><?= esc($rating['project_title'] ?? 'N/A') ?></td>
        </tr>

        <tr>
            <th>Contractor</th>
            <td><?= esc($rating['contractor_email'] ?? 'N/A') ?></td>
        </tr>

        <tr>
            <th>Homeowner</th>
            <td><?= esc($rating['homeowner_email'] ?? 'N/A') ?></td>
        </tr>

        <tr>
            <th>Quality</th>
            <td><?= esc($rating['quality']) ?></td>
        </tr>

        <tr>
            <th>Timeliness</th>
            <td><?= esc($rating['timeliness']) ?></td>
        </tr>

        <tr>
            <th>Communication</th>
            <td><?= esc($rating['communication']) ?></td>
        </tr>

        <tr>
            <th>Pricing</th>
            <td><?= esc($rating['pricing']) ?></td>
        </tr>

        <tr>
            <th>Average Score</th>
            <td><?= esc($rating['avg_score'] ?? '') ?></td>
        </tr>

        <tr>
            <th>Recommend</th>
            <td><?= ((int)($rating['recommend'] ?? 0) === 1) ? 'Yes' : 'No' ?></td>
        </tr>

        <tr>
            <th>Notes</th>
            <td><?= esc($rating['notes'] ?? '') ?></td>
        </tr>

        <tr>
            <th>Created</th>
            <td><?= esc($rating['created_at']) ?></td>
        </tr>

    </table>

    <hr class="report-divider">

    <p class="report-summary">

        <a class="action-link"
           href="<?= site_url('admin/ratings/remove/' . $rating['id']) ?>"
           onclick="return confirm('Remove this rating?')">

            Remove Rating

        </a>

    </p>

</div>
<?= $this->endSection() ?>
