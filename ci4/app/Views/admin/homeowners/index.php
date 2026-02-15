<link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">


<div class="users-container">

    <h1 class="users-title">Homeowners</h1>

    <form method="get"
          action="<?= site_url('admin/homeowners') ?>"
          class="filter-form">

        <input
                type="text"
                name="q"
                placeholder="Search username, name, email, city..."
                value="<?= esc($_GET['q'] ?? '') ?>"
        />

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

        <a href="<?= site_url('admin/homeowners') ?>"
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
        </tr>

        </thead>


        <tbody>

        <?php if (!empty($homeowners)) : ?>

            <?php foreach ($homeowners as $h) : ?>

                <tr>

                    <td><?= esc($h['id']) ?></td>

                    <td><?= esc($h['username']) ?></td>

                    <td><?= esc(trim(($h['first_name'] ?? '') . ' ' . ($h['last_name'] ?? ''))) ?></td>

                    <td><?= esc($h['email']) ?></td>

                    <td><?= esc($h['phone'] ?? '') ?></td>

                    <td><?= esc($h['city'] ?? '') ?></td>

                    <td><?= esc($h['province'] ?? '') ?></td>

                    <td><?= ((int)$h['is_active'] === 1) ? 'Active' : 'Inactive' ?></td>

                    <td><?= esc($h['created_at'] ?? '') ?></td>

                    <td>

                        <?php if ((int)$h['is_active'] === 1): ?>

                            <a class="action-link"
                               href="<?= site_url('admin/homeowners/toggle/' . $h['id']) ?>">
                                Deactivate
                            </a>

                        <?php else: ?>

                            <a class="action-link"
                               href="<?= site_url('admin/homeowners/toggle/' . $h['id']) ?>">
                                Activate
                            </a>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else : ?>

            <tr>
                <td colspan="10">
                    No homeowners found.
                </td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

