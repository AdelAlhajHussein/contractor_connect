<div class="dashboard-table-container">
    <?php if ($type === 'categories'): ?>
        <div>
            <a class="action-link" href="<?= site_url('admin/categories/create') ?>">+ Add Category</a>
        </div>
    <?php endif; ?>

    <?php if (isset($headers) && isset($rows)): ?>
        <table class="dashboard-table" >
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
                        <!-- User Types -->
                        <!---- users ---->
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