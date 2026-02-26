
<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="users-container">

    <h1 class="users-title">Projects</h1>

    <form method="get"
          action="<?= site_url('admin/projects') ?>"
          class="filter-form">

        <input
                type="text"
                name="q"
                placeholder="Search project title..."
                value="<?= esc($_GET['q'] ?? '') ?>"
        />

        <select name="status">

            <option value="">All Status</option>

            <option value="bidding_open"
                <?= (($_GET['status'] ?? '') === 'bidding_open') ? 'selected' : '' ?>>
                Bidding
            </option>

            <option value="in_progress"
                <?= (($_GET['status'] ?? '') === 'in_progress') ? 'selected' : '' ?>>
                In Progress
            </option>

            <option value="completed"
                <?= (($_GET['status'] ?? '') === 'completed') ? 'selected' : '' ?>>
                Completed
            </option>

            <option value="cancelled"
                <?= (($_GET['status'] ?? '') === 'cancelled') ? 'selected' : '' ?>>
                Cancelled
            </option>

        </select>

        <button type="submit">Filter</button>

        <a href="<?= site_url('admin/projects') ?>"
           class="reset-link">
            Reset
        </a>

        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-danger">
            ← Back to Dashboard
        </a>

    </form>


    <table class="users-table">

        <thead>

        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Homeowner</th>
            <th>Category</th>
            <th>Status</th>
            <th>Budget</th>
            <th>Deadline</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>

        </thead>

        <tbody>

        <?php if (!empty($projects)) : ?>

            <?php foreach ($projects as $p) : ?>

                <tr>

                    <td><?= esc($p['id']) ?></td>

                    <td><?= esc($p['title']) ?></td>

                    <td><?= esc(trim(($p['homeowner_first_name'] ?? '') . ' ' . ($p['homeowner_last_name'] ?? ''))) ?></td>

                    <td><?= esc($p['category_name'] ?? '') ?></td>

                    <td><?= esc($p['status']) ?></td>

                    <td><?= esc($p['budget_min']) ?> - <?= esc($p['budget_max']) ?></td>

                    <td><?= esc($p['deadline_date'] ?? '') ?></td>

                    <td><?= esc($p['created_at']) ?></td>

                    <td>

                        <a class="action-link"
                           href="<?= site_url('admin/projects/view/' . $p['id']) ?>">
                            View
                        </a>


                        <?php if ($p['status'] !== 'cancelled' && $p['status'] !== 'completed'): ?>

                            |

                            <a class="action-link"
                               href="<?= site_url('admin/projects/cancel/' . $p['id']) ?>">
                                Cancel
                            </a>

                        <?php endif; ?>


                        <?php if ($p['status'] === 'bidding_open'): ?>

                            |

                            <a class="action-link"
                               href="<?= site_url('admin/projects/close-bidding/' . $p['id']) ?>">
                                Close Bidding
                            </a>

                        <?php endif; ?>


                    </td>

                </tr>

            <?php endforeach; ?>


        <?php else : ?>

            <tr>

                <td colspan="9">
                    No projects found.
                </td>

            </tr>

        <?php endif; ?>


        </tbody>

    </table>

</div>
<?= $this->endSection() ?>
