<div class="dashboard-table-container">
    <?php if ($type === 'categories'): ?>
        <div>
            <a class="action-link" href="<?= site_url('admin/categories/create') ?>">+ Add Category</a>
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
                                <div>
                                    <a class="action-link" href="<?= site_url('admin/users/toggle/'.$row['id']) ?>"
                                       onclick="return confirm('Change status?')">
                                        <?= ($row['is_active'] == 1) ? 'Deactivate' : 'Activate' ?>
                                    </a>
                                    <form method="post" action="<?= site_url('admin/users/role/'.$row['id']) ?>">
                                        <?= csrf_field() ?>
                                        <select name="role_id">
                                            <option value="1" <?= ($row['role_id']==1)?'selected':'' ?>>Admin</option>
                                            <option value="2" <?= ($row['role_id']==2)?'selected':'' ?>>Homeowner</option>
                                            <option value="3" <?= ($row['role_id']==3)?'selected':'' ?>>Contractor</option>
                                        </select>
                                        <button type="submit" class="update-btn">Update</button>
                                    </form>
                                </div>
                            </td>

                        <!-- Categories -->
                        <?php elseif ($type === 'categories'): ?>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= ($row['is_visible'] == 1) ? 'Yes' : 'No' ?></td>
                            <td>
                                <a class="action-link" href="<?= site_url('admin/categories/edit/'.$row['id']) ?>">Edit</a> |
                                <a class="action-link" href="<?= site_url('admin/categories/toggle/'.$row['id']) ?>">
                                    <?= ($row['is_visible'] == 1) ? 'Hide' : 'Show' ?>
                                </a> |
                                <a class="action-link" href="<?= site_url('admin/categories/delete/'.$row['id']) ?>"
                                   onclick="return confirm('Delete this category?')">Delete</a>
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

                        <!-- Admin Reports -->
                        <?php elseif ($type === 'reports'): ?>
                            <td><?= esc($row['cat']) ?></td>

                            <td><?= esc($row['metric']) ?></td>

                            <td><span class="update-btn"><?= esc($row['val']) ?></span></td>

                            <td>
                                <span>
                                    <?= esc($row['stat']) ?>
                                </span>
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









                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= count($headers) ?>">No data found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>