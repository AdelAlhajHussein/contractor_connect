<div class="dashboard-table-container">
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
                        <?php foreach ($row as $cell): ?>
                            <td><?= $cell ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= count($headers) ?>" style="text-align: center;">
                        No project data available.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?> </div>