<div class="dashboard-table-container">

    <?php if (isset($headers) && isset($rows)): ?>
        <table class="dasboard-table">
            <thead>
                <tr>
                    <?php foreach ($headers as $header): ?>
                        <th><?= esc($header) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <?php foreach ($row as $cell): ?>
                            <td><?= $cell ?></td> <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>No data available.</p>
    <?php endif; ?>

</div>