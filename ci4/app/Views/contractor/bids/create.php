<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/admin-form.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="form-container">

    <h1 class="form-title">Place Bid</h1>

    <a class="form-back" href="<?= site_url('contractor/browse/' . $project['id']) ?>">
        ← Back to Project
    </a>

    <p><strong>Project:</strong> <?= esc($project['title'] ?? '') ?></p>

    <form method="post" action="<?= site_url('contractor/bids/store/' . $project['id']) ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Bid Amount</label>
            <input type="number" step="0.01" name="bid_amount" required>
        </div>

        <div class="form-group">
            <label>Details (optional)</label>
            <textarea name="details" rows="4"></textarea>
        </div>

        <button type="submit" class="form-btn">Submit Bid</button>
    </form>

</div>
<?= $this->endSection() ?>