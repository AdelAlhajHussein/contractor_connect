<h1>Project Details</h1>

<p><strong>Title:</strong> <?= esc($project['title'] ?? '') ?></p>
<p><strong>Description:</strong> <?= esc($project['description'] ?? '') ?></p>
<p><strong>Status:</strong> <?= esc($project['status'] ?? '') ?></p>
<p><strong>Budget:</strong> <?= esc($project['budget_min'] ?? '') ?> - <?= esc($project['budget_max'] ?? '') ?></p>

<hr>

<a href="<?= site_url('contractor/bids/create/' . $project['id']) ?>">Place Bid</a> |
<a href="<?= site_url('contractor/browse') ?>">Back to Browse</a>
