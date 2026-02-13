<h1>Edit Category</h1>

<p>
    <a href="<?= site_url('admin/categories') ?>">← Back to Categories</a>
</p>

<?php if (empty($category)): ?>
    <p>Category not found.</p>
<?php else: ?>
    <form method="post" action="<?= site_url('admin/categories/update/' . $category['id']) ?>">
        <?= csrf_field() ?>

        <p>
            <label>Name</label><br>
            <input type="text" name="name" value="<?= esc($category['name']) ?>" required>
        </p>

        <button type="submit">Save</button>
    </form>
<?php endif; ?>
