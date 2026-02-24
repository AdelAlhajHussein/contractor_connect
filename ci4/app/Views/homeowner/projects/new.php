<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/admin-form.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="form-container">

        <h2 class="form-title">New Project</h2>

        <a class="form-back" href="<?= site_url('homeowner/projects') ?>">← Back to My Projects</a>

        <form method="post" action="<?= site_url('homeowner/projects/create') ?>">

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">Select category</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= esc($c['id']) ?>"><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc;"></textarea>
            </div>

            <div class="form-group">
                <label>Budget Min</label>
                <input type="text" name="budget_min">
            </div>

            <div class="form-group">
                <label>Budget Max</label>
                <input type="text" name="budget_max">
            </div>

            <button class="form-btn" type="submit">Create Project</button>

        </form>

    </div>

<?= $this->endSection() ?>