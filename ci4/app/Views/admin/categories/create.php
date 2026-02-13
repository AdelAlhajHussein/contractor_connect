<h1>Add Category</h1>

<p>
    <a href="<?= site_url('admin/categories') ?>">← Back to Categories</a>
</p>

<form method="post" action="<?= site_url('admin/categories/store') ?>">
    <?= csrf_field() ?>

    <p>
        <label>Name</label><br>
        <input type="text" name="name" required>
    </p>

    <button type="submit">Create</button>
</form>
