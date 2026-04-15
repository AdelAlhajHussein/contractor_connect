<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">

<div class="auth-container">

    <div class="auth-card">

        <!-- Left side form -->
        <div class="auth-form">

            <div class="auth-title">Create a new account</div>


            <?php $errors = session()->getFlashdata('errors') ?? []; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div style="...">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div style="...">
                    <ul style="...">
                        <?php foreach ($errors as $e): ?>
                            <li><?= esc($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

    <form method="post" action="<?= site_url('register') ?>">
        <?= csrf_field() ?>

        <div class="auth-group">
            <label >Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>" required>
        </div>

        <div class="auth-group">
            <label for="role_id">Account type</label><br>
            <select id="role_id" name="role_id" style="width:100%; padding:10px;">
                <option value="">Select...</option>
                <option value="2" <?= old('role_id') == '2' ? 'selected' : '' ?>>Homeowner</option>
                <option value="3" <?= old('role_id') == '3' ? 'selected' : '' ?>>Contractor</option>
            </select>
        </div>

        <div class="auth-group">
            <label for="password">Password</label><br>
            <input id="password" name="password" type="password" style="width:100%; padding:10px;">
        </div>

        <div class="auth-group">
            <label for="confirm_password">Confirm password</label><br>
            <input id="confirm_password" name="confirm_password" type="password" style="width:100%; padding:10px;">
        </div>

        <button type="submit"  class="auth-btn">Create Account</button>

        <div class="auth-footer">
            Already have an account? <a href="<?= site_url('login') ?>">Sign in</a>
        </div>
    </form>
        </div>
            <!-- Right side image -->
            <div class="auth-image">

                <!-- Put image inside public/img -->
                <img src="<?= base_url('img/auth-image.png') ?>" alt="Login Image">

            </div>

        </div>
    </div>

<?= $this->endSection() ?>
