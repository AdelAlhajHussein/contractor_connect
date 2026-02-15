<link rel="stylesheet" href="<?= base_url('css/admin-form.css') ?>">

<div class="form-container">

    <h1 class="form-title">Add Category</h1>

    <a class="form-back"
       href="<?= site_url('admin/categories') ?>">
        ← Back to Categories
    </a>

    <form method="post"
          action="<?= site_url('admin/categories/store') ?>">

        <?= csrf_field() ?>

        <div class="form-group">

            <label>Name</label>

            <input type="text"
                   name="name"
                   required>

        </div>

        <button class="form-btn"
                type="submit">
            Create
        </button>

    </form>

</div>
