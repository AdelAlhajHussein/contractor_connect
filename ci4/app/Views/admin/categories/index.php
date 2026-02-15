<link rel="stylesheet" href="<?= base_url('css/admin-index.css') ?>">

<div class="users-container">

    <h1 class="users-title">Categories</h1>

    <p>
        <a class="action-link"
           href="<?= site_url('admin/categories/create') ?>">
            + Add Category
        </a>
    </p>


    <form method="get"
          action="<?= site_url('admin/categories') ?>"
          class="filter-form">

        <input
                type="text"
                name="q"
                placeholder="Search category name..."
                value="<?= esc($q ?? '') ?>"
        />

        <select name="visibility">

            <option value="">All</option>

            <option value="1"
                <?= (($visibility ?? '') === '1') ? 'selected' : '' ?>>
                Visible
            </option>

            <option value="0"
                <?= (($visibility ?? '') === '0') ? 'selected' : '' ?>>
                Hidden
            </option>

        </select>

        <button type="submit">Filter</button>

        <a href="<?= site_url('admin/categories') ?>"
           class="reset-link">
            Reset
        </a>

    </form>


    <table class="users-table">

        <thead>

        <tr>
            <th width="60">ID</th>
            <th>Name</th>
            <th width="120">Visible</th>
            <th width="220">Actions</th>
        </tr>

        </thead>

        <tbody>

        <?php if (empty($categories)): ?>

            <tr>

                <td colspan="4">
                    No categories found.
                </td>

            </tr>

        <?php else: ?>

            <?php foreach ($categories as $c): ?>

                <tr>

                    <td><?= esc($c['id']) ?></td>

                    <td><?= esc($c['name']) ?></td>

                    <td>
                        <?= ((int)$c['is_visible'] === 1) ? 'Yes' : 'No' ?>
                    </td>

                    <td>

                        <a class="action-link"
                           href="<?= site_url('admin/categories/edit/' . $c['id']) ?>">
                            Edit
                        </a>

                        |

                        <a class="action-link"
                           href="<?= site_url('admin/categories/toggle/' . $c['id']) ?>">
                            <?= ((int)$c['is_visible'] === 1) ? 'Hide' : 'Show' ?>
                        </a>

                        |

                        <a class="action-link"
                           href="<?= site_url('admin/categories/delete/' . $c['id']) ?>">
                            Delete
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

        </tbody>

    </table>

</div>
