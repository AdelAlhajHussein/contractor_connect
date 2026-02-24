<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="users-container">

        <h1 class="users-title">Browse Contractors</h1>

        <form class="filter-form" method="get" action="<?= site_url('homeowner/browse') ?>">
            <label>City:</label>
            <input type="text" name="city" value="<?= esc($filters['city'] ?? '') ?>">

            <label>Province:</label>
            <input type="text" name="province" value="<?= esc($filters['province'] ?? '') ?>">

            <label>Specialty:</label>
            <select name="specialty_id">
                <option value="">All</option>
                <?php foreach ($specialties as $s): ?>
                    <option value="<?= (int)$s['id'] ?>"
                        <?= ((string)$s['id'] === (string)($filters['specialty_id'] ?? '')) ? 'selected' : '' ?>>
                        <?= esc($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Min Rating:</label>
            <select name="min_rating">
                <option value="">Any</option>
                <?php foreach ([1,2,3,4,5] as $r): ?>
                    <option value="<?= $r ?>" <?= ((string)$r === (string)($filters['min_rating'] ?? '')) ? 'selected' : '' ?>>
                        <?= $r ?>+
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Filter</button>
            <a class="reset-link" href="<?= site_url('homeowner/browse') ?>">Reset</a>
        </form>

        <hr>

        <?php if (empty($contractors)): ?>
            <p>No contractors found.</p>
        <?php else: ?>
            <table class="users-table" border="1" cellpadding="8" cellspacing="0">
                <thead>
                <tr>
                    <th>Contractor</th>
                    <th>Location</th>
                    <th>Specialties</th>
                    <th>Avg Rating</th>
                    <th># Reviews</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($contractors as $c): ?>
                    <tr>
                        <td><?= esc(trim(($c['first_name'] ?? '') . ' ' . ($c['last_name'] ?? ''))) ?></td>
                        <td><?= esc(($c['city'] ?? '') . ', ' . ($c['province'] ?? '')) ?></td>
                        <td><?= esc($c['specialties'] ?? '') ?></td>
                        <td><?= esc($c['avg_rating'] ?? 'N/A') ?></td>
                        <td><?= esc($c['rating_count'] ?? 0) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>

<?= $this->endSection() ?>