<div class="dashboard-table-container">
    <?php if ($type === 'categories'): ?>
        <div>
            <a class="action-link" href="<?= site_url('admin/categories/create') ?>">+ Add Category</a>
        </div>
    <?php endif; ?>

    <?php if ($type === 'suspicious_ratings'): ?>
        <div class="reports-container">
            <h2 class="report-section-title">Repeat Homeowner-Contractor Pairs</h2>
            <table class="dashboard-table" style="margin-bottom: 30px;">
                <thead>
                <tr><th>Contractor Email</th><th>Homeowner Email</th><th>Count</th></tr>
                </thead>
                <tbody>
                <?php if (!empty($repeatPairs)): foreach ($repeatPairs as $pair): ?>
                    <tr>
                        <td><?= esc($pair['contractor_email']) ?></td>
                        <td><?= esc($pair['homeowner_email']) ?></td>
                        <td><strong><?= $pair['rating_count'] ?></strong></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="3">No suspicious pairs found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <h2 class="report-section-title">Rating Bursts (Last 7 Days)</h2>
            <table class="dashboard-table">
                <thead>
                <tr><th>Contractor Email</th><th>Ratings</th></tr>
                </thead>
                <tbody>
                <?php if (!empty($recentBurst)): foreach ($recentBurst as $burst): ?>
                    <tr>
                        <td><?= esc($burst['contractor_email']) ?></td>
                        <td><?= $burst['last_7_days'] ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="2">No bursts detected.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if (isset($headers) && isset($rows)): ?>
        <table class="dashboard-table">
            <thead>
            <tr>
                <?php foreach ($headers as $header): ?>
                    <th><?= esc($header) ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>

            <tbody>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <?php if ($type === 'users'): ?>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['role_id'] ?></td>
                            <td><?= ($row['is_active'] == 1) ? 'Active' : 'Inactive' ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <a class="action-link" href="<?= site_url('admin/users/toggle/'.$row['id']) ?>">
                                    <?= ($row['is_active'] == 1) ? 'Deactivate' : 'Activate' ?>
                                </a>
                            </td>

                        <!-- Categories -->
                        <?php elseif ($type === 'categories'): ?>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= ($row['is_visible'] == 1) ? 'Yes' : 'No' ?></td>
                            <td>
                                <a class="action-link" href="<?= site_url('admin/categories/edit/'.$row['id']) ?>">Edit</a> |
                                <a class="action-link" href="<?= site_url('admin/categories/toggle/'.$row['id']) ?>">Toggle</a>
                            </td>

                        <!-- Homeowners -->
                        <?php elseif ($type === 'homeowners'): ?>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['city'] ?></td>
                            <td>
                                <span class="<?= ($row['is_active'] == 1) ? 'status-active' : 'status-inactive' ?>">
                                    <?= ($row['is_active'] == 1) ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <a class="action-link" href="<?= site_url('admin/homeowners/toggle/' . $row['id']) ?>"
                                   onclick="return confirm('Toggle homeowner status?')">
                                    <?= ($row['is_active'] == 1) ? 'Deactivate' : 'Activate' ?>
                                </a>
                            </td>

                        <!-- Contractors -->
                        <?php elseif ($type === 'contractors'): ?>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= ($row['is_active'] == 1) ? 'Active' : 'Inactive' ?></td>
                            <td>
                                <div>
                                    <a class="action-link" href="<?= site_url('admin/users/toggle/'.$row['id']) ?>"
                                       onclick="return confirm('Change status?')">
                                        <?= ($row['is_active'] == 1) ? 'Deactivate' : 'Activate' ?>
                                    </a>
                                    <a class="action-link" href="<?= site_url('admin/contractors/view/'.$row['id']) ?>">View Profile</a>
                                </div>
                            </td>


                        <!-- Projects -->
                        <?php elseif ($type === 'projects'): ?>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['title'] ?></td>
                            <td><?= $row['homeowner'] ?></td>
                            <td>
                                <span class="status-pill status-<?= $row['status'] ?>" >
                                    <?= str_replace('_', ' ', $row['status']) ?>
                                </span>
                            </td>
                            <td><?= $row['budget'] ?></td>
                            <td><?= $row['deadline'] ?></td>
                            <td>
                                <div>
                                    <a class="action-link" href="<?= site_url('admin/projects/view/' . $row['id']) ?>">View</a>
                                    <?php if ($row['status'] !== 'cancelled' && $row['status'] !== 'completed'): ?>
                                        | <a class="action-link" href="<?= site_url('admin/projects/cancel/' . $row['id']) ?>"
                                             onclick="return confirm('Cancel this project?')">Cancel</a>
                                    <?php endif; ?>

                                    <?php if ($row['status'] === 'bidding_open'): ?>
                                        | <a class="action-link" href="<?= site_url('admin/projects/close-bidding/' . $row['id']) ?>">Close Bidding</a>
                                    <?php endif; ?>
                                </div>
                            </td>

                        <!-- Bids -->
                        <?php elseif ($type === 'bids'): ?>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['project_title'] ?></td>
                            <td><?= $row['contractor_email'] ?></td>
                            <td>
                                <span class="status-pill status-<?= $row['status'] ?>">
                                    <?= esc(ucfirst($row['status'])) ?>
                                </span>
                            </td>
                            <td>$<?= number_format($row['bid_amount'], 2) ?></td>
                            <td>$<?= number_format($row['total_cost'], 2) ?></td>
                            <td>
                                <a class="action-link" href="<?= site_url('admin/bids/view/' . $row['id']) ?>">View</a>
                                <?php if (!in_array($row['status'], ['accepted', 'rejected', 'withdrawn'])): ?>
                                    | <a class="action-link" href="<?= site_url('admin/bids/withdraw/' . $row['id']) ?>"
                                         onclick="return confirm('Withdraw this bid?')">Withdraw</a>
                                <?php endif; ?>
                            </td>

                        <!-- Ratings -->
                        <?php elseif ($type === 'ratings'): ?>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['project_title'] ?></td>
                            <td><?= $row['contractor_email'] ?></td>
                            <td><?= $row['homeowner_email'] ?></td>
                            <td><strong><?= $row['avg_score'] ?></strong></td>
                            <td><?= ($row['recommend'] == 1) ? 'Yes' : 'No' ?></td>
                            <td>
                                <a class="action-link" href="<?= site_url('admin/ratings/view/' . $row['id']) ?>">View</a> |
                                <a class="action-link" href="<?= site_url('admin/ratings/remove/' . $row['id']) ?>"
                                   onclick="return confirm('Permanently remove this rating?')">Remove</a>
                            </td>

                        <!-- Admin Reports -->
                        <?php elseif ($type === 'reports'): ?>
                            <td><?= esc($row['cat']) ?></td>
                            <td><?= esc($row['metric']) ?></td>
                            <td><span class="update-btn"><?= esc($row['val']) ?></span></td>
                            <td><span><?= esc($row['stat']) ?></span></td>

                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="<?= count($headers) ?>">No data found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>