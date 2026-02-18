<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>

<link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="users-container">

    <h1 class="users-title">Ratings</h1>

    <p>
        <a class="action-link"
           href="<?= site_url('admin/ratings/suspicious') ?>">
            View Suspicious Activity
        </a>
    </p>

    <form method="get"
          action="<?= site_url('admin/ratings') ?>"
          class="filter-form">

        <input
                type="text"
                name="q"
                placeholder="Search project title or contractor/homeowner email..."
                value="<?= esc($q ?? '') ?>"
        />

        <select name="score">

            <option value="">All Avg Scores</option>

            <?php for ($i=1; $i<=5; $i++): ?>

                <option value="<?= $i ?>"
                    <?= (($score ?? '') === (string)$i) ? 'selected' : '' ?>>

                    <?= $i ?>+

                </option>

            <?php endfor; ?>

        </select>

        <button type="submit">Filter</button>

        <a href="<?= site_url('admin/ratings') ?>"
           class="reset-link">
            Reset
        </a>

    </form>


    <table class="users-table">

        <thead>

        <tr>
            <th>ID</th>
            <th>Project</th>
            <th>Contractor</th>
            <th>Homeowner</th>
            <th>Avg Score</th>
            <th>Recommend</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>

        </thead>

        <tbody>

        <?php if (empty($ratings)): ?>

            <tr>
                <td colspan="8">
                    No ratings found.
                </td>
            </tr>

        <?php else: ?>

            <?php foreach ($ratings as $r): ?>

                <tr>

                    <td><?= esc($r['id']) ?></td>

                    <td><?= esc($r['project_title'] ?? 'N/A') ?></td>

                    <td><?= esc($r['contractor_email'] ?? 'N/A') ?></td>

                    <td><?= esc($r['homeowner_email'] ?? 'N/A') ?></td>

                    <td><?= esc($r['avg_score'] ?? '') ?></td>

                    <td><?= ((int)($r['recommend'] ?? 0) === 1) ? 'Yes' : 'No' ?></td>

                    <td><?= esc($r['created_at']) ?></td>

                    <td>

                        <a class="action-link"
                           href="<?= site_url('admin/ratings/view/' . $r['id']) ?>">
                            View
                        </a>

                        |

                        <a class="action-link"
                           href="<?= site_url('admin/ratings/remove/' . $r['id']) ?>">
                            Remove
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

        </tbody>

    </table>

</div>
<?= $this->endSection() ?>
