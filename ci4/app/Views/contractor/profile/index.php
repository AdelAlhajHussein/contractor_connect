<h2>My Profile</h2>

<?php if ($profile): ?>

    <p><strong>Username:</strong> <?= esc($profile->username) ?></p>
    <p><strong>First Name:</strong> <?= esc($profile->first_name) ?></p>
    <p><strong>Last Name:</strong> <?= esc($profile->last_name) ?></p>
    <p><strong>Email:</strong> <?= esc($profile->email) ?></p>
    <p><strong>Phone:</strong> <?= esc($profile->phone ?? 'N/A') ?></p>

    <hr>

    <p><strong>Address:</strong> <?= esc($profile->address ?? 'N/A') ?></p>
    <p><strong>City:</strong> <?= esc($profile->city ?? 'N/A') ?></p>
    <p><strong>Province:</strong> <?= esc($profile->province ?? 'N/A') ?></p>
    <p><strong>Postal Code:</strong> <?= esc($profile->postal_code ?? 'N/A') ?></p>
    <p><strong>Approval Status:</strong> <?= esc($profile->approval_status ?? 'N/A') ?></p>

<?php else: ?>

    <p>No profile data found.</p>

<?php endif; ?>