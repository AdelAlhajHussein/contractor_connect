<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-form.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="form-container">

    <h1 class="form-title">Edit Category</h1>

    <a class="form-back"
       href="<?= site_url('admin/categories') ?>">
        ← Back to Categories
    </a>

    <?php if (empty($category)): ?>

        <p>Category not found.</p>

    <?php else: ?>

        <form method="post"
              action="<?= site_url('admin/categories/update/' . $category['id']) ?>">

            <?= csrf_field() ?>

            <div class="form-group">

                <label>Name</label>

                <input type="text"
                       name="name"
                       value="<?= esc($category['name']) ?>"
                       required>

            </div>

            <button class="form-btn"
                    type="submit">
                Save
            </button>

        </form>

    <?php endif; ?>

</div>
<?= $this->endSection() ?>
