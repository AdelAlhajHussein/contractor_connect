<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="max-width: 520px; margin: 40px auto;">
    <h1>Create a new account</h1>

    <?php $errors = session('errors') ?? []; ?>
    <?php if (session('error')): ?>
        <div style="padding:10px; margin: 12px 0; border:1px solid #f5c2c7; background:#f8d7da;">
            <?= esc(session('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div style="padding:10px; margin: 12px 0; border:1px solid #f5c2c7; background:#f8d7da;">
            <ul style="margin:0; padding-left: 18px;">
                <?php foreach ($errors as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('register') ?>">
        <?= csrf_field() ?>

        <div style="margin: 12px 0;">
            <label for="username">Username</label><br>
            <input id="username" name="username" type="text" value="<?= esc(old('username')) ?>" style="width:100%; padding:10px;">
        </div>

        <div style="margin: 12px 0;">
            <label for="role_id">Account type</label><br>
            <select id="role_id" name="role_id" style="width:100%; padding:10px;">
                <option value="">Select...</option>
                <option value="2" <?= old('role_id') == '2' ? 'selected' : '' ?>>Homeowner</option>
                <option value="3" <?= old('role_id') == '3' ? 'selected' : '' ?>>Contractor</option>
            </select>
        </div>

        <div style="margin: 12px 0;">
            <label for="password">Password</label><br>
            <input id="password" name="password" type="password" style="width:100%; padding:10px;">
        </div>

        <div style="margin: 12px 0;">
            <label for="confirm_password">Confirm password</label><br>
            <input id="confirm_password" name="confirm_password" type="password" style="width:100%; padding:10px;">
        </div>

        <button type="submit" style="padding:10px 14px;">Create Account</button>
    </form>

    <p style="margin-top: 14px;">
        Already have an account? <a href="<?= site_url('login') ?>">Sign in</a>
    </p>
</div>

<?= $this->endSection() ?>
