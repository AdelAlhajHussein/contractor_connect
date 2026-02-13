<h1>Categories</h1>

<p>
    <a href="<?= site_url('admin/categories/create') ?>">+ Add Category</a>
</p>

<form method="get" action="<?= site_url('admin/categories') ?>" style="margin-bottom:15px;">
    <input
            type="text"
            name="q"
            placeholder="Search category name..."
            value="<?= esc($q ?? '') ?>"
    />

    <select name="visibility">
        <option value="">All</option>
        <option value="1" <?= (($visibility ?? '') === '1') ? 'selected' : '' ?>>Visible</option>
        <option value="0" <?= (($visibility ?? '') === '0') ? 'selected' : '' ?>>Hidden</option>
    </select>

    <button type="submit">Filter</button>
    <a href="<?= site_url('admin/categories') ?>">Reset</a>
</form>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th width="60">ID</th>
        <th>Name</th>
        <th width="120">Visible</th>
        <th width="220">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($categories)): ?>
        <tr>
            <td colspan="4">No categories found.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($categories as $c): ?>
            <tr>
                <td><?= esc($c['id']) ?></td>
                <td><?= esc($c['name']) ?></td>
                <td><?= ((int)$c['is_visible'] === 1) ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="<?= site_url('admin/categories/edit/' . $c['id']) ?>">Edit</a>
                    |
                    <a href="<?= site_url('admin/categories/toggle/' . $c['id']) ?>">
                        <?= ((int)$c['is_visible'] === 1) ? 'Hide' : 'Show' ?>
                    </a>
                    |
                    <a href="<?= site_url('admin/categories/delete/' . $c['id']) ?>"
                       onclick="return confirm('Delete this category?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
