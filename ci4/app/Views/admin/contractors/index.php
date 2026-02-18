
<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="users-container">

    <h1 class="users-title">Contractors</h1>

    <form method="get"
          action="<?= site_url('admin/contractors') ?>"
          class="filter-form">

        <input
                type="text"
                name="q"
                placeholder="Search username, name, email, city..."
                value="<?= esc($_GET['q'] ?? '') ?>"
        >

        <select name="status">

            <option value="">All Status</option>

            <option value="1"
                <?= (($_GET['status'] ?? '') === '1') ? 'selected' : '' ?>>
                Active
            </option>

            <option value="0"
                <?= (($_GET['status'] ?? '') === '0') ? 'selected' : '' ?>>
                Inactive
            </option>

        </select>

        <button type="submit">Filter</button>

        <a href="<?= site_url('admin/contractors') ?>"
           class="reset-link">
            Reset
        </a>

    </form>


    <table class="users-table">

        <thead>

        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>City</th>
            <th>Province</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
            <th>Approval</th>
            <th>Approve/Reject</th>
        </tr>

        </thead>

        <tbody>

        <?php if (!empty($contractors)) : ?>

            <?php foreach ($contractors as $c) : ?>

                <tr>

                    <td><?= esc($c['id']) ?></td>

                    <td><?= esc($c['username']) ?></td>

                    <td><?= esc(trim(($c['first_name'] ?? '') . ' ' . ($c['last_name'] ?? ''))) ?></td>

                    <td><?= esc($c['email']) ?></td>

                    <td><?= esc($c['phone'] ?? '') ?></td>

                    <td><?= esc($c['city'] ?? '') ?></td>

                    <td><?= esc($c['province'] ?? '') ?></td>

                    <td><?= ((int)$c['is_active'] === 1) ? 'Active' : 'Inactive' ?></td>

                    <td><?= esc($c['created_at'] ?? '') ?></td>

                    <td>

                        <?php if ((int)$c['is_active'] === 1): ?>

                            <a class="action-link"
                               href="<?= site_url('admin/contractors/toggle/' . $c['id']) ?>">
                                Suspend
                            </a>

                        <?php else: ?>

                            <a class="action-link"
                               href="<?= site_url('admin/contractors/toggle/' . $c['id']) ?>">
                                Activate
                            </a>

                        <?php endif; ?>

                    </td>


                    <td><?= esc($c['approval_status'] ?? 'pending') ?></td>


                    <td>

                        <a class="action-link"
                           href="<?= site_url('admin/contractors/approve/' . $c['id']) ?>">
                            Approve
                        </a>

                        |

                        <a class="action-link"
                           href="<?= site_url('admin/contractors/reject/' . $c['id']) ?>">
                            Reject
                        </a>

                    </td>


                </tr>

            <?php endforeach; ?>

        <?php else : ?>

            <tr>

                <td colspan="12">
                    No contractors found.
                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

<?= $this->endSection() ?>