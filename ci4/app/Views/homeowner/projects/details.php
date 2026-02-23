<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Project Details</title>
</head>
<body>

<h1>Project Details</h1>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <td><?= esc($project['id']) ?></td>
    </tr>

    <tr>
        <th>Title</th>
        <td><?= esc($project['title']) ?></td>
    </tr>

    <tr>
        <th>Type (Category)</th>
        <td><?= esc($project['category_id']) ?></td>
    </tr>

    <tr>
        <th>Description</th>
        <td><?= esc($project['description']) ?></td>
    </tr>

    <tr>
        <th>Address</th>
        <td><?= esc($project['address'] ?? '') ?></td>
    </tr>

    <tr>
        <th>Contact Phone</th>
        <td><?= esc($project['contact_phone'] ?? '') ?></td>
    </tr>

    <tr>
        <th>Start Date</th>
        <td><?= esc($project['start_date'] ?? '') ?></td>
    </tr>

    <tr>
        <th>End Date</th>
        <td><?= esc($project['end_date'] ?? '') ?></td>
    </tr>

    <tr>
        <th>Deadline Date</th>
        <td><?= esc($project['deadline_date'] ?? '') ?></td>
    </tr>

    <tr>
        <th>Budget Range</th>
        <td>
            $<?= esc(number_format((float)($project['budget_min'] ?? 0), 2)) ?>
            -
            $<?= esc(number_format((float)($project['budget_max'] ?? 0), 2)) ?>
        </td>
    </tr>

    <tr>
        <th>Status</th>
        <td><?= esc($project['status']) ?></td>
    </tr>
</table>

<p style="margin-top:16px;">
    <a href="<?= site_url('homeowner/projects') ?>">← Back to My Projects</a>
</p>

</body>
</html>
