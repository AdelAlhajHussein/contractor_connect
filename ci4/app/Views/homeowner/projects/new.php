<h2>New Project</h2>

<form method="post" action="/index.php/homeowner/projects/create">

    <label>Category</label><br>
    <select name="category_id" required>
        <option value="">Select category</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?= esc($c['id']) ?>"><?= esc($c['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Title</label><br>
    <input type="text" name="title"><br><br>

    <label>Description</label><br>
    <textarea name="description"></textarea><br><br>

    <label>Budget Min</label><br>
    <input type="text" name="budget_min"><br><br>

    <label>Budget Max</label><br>
    <input type="text" name="budget_max"><br><br>

    <button type="submit">Create Project</button>

</form>
