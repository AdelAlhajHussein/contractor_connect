<h1>Place Bid</h1>

<p><strong>Project:</strong> <?= esc($project['title'] ?? '') ?></p>

<form method="post" action="<?= site_url('contractor/bids/store/' . $project['id']) ?>">
    <?= csrf_field() ?>

    <div style="margin-bottom: 10px;">
        <label>Bid Amount</label><br>
        <input type="number" step="0.01" name="bid_amount" required>
    </div>

    <div style="margin-bottom: 10px;">
        <label>Details (optional)</label><br>
        <textarea name="details" rows="4" cols="60"></textarea>
    </div>

    <button type="submit">Submit Bid</button>
</form>

<hr>

<a href="<?= site_url('contractor/browse/' . $project['id']) ?>">Back to Project</a>
