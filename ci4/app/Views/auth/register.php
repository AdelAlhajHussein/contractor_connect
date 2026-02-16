<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1>Create an account</h1>

<section class='registration-section'>
    <h2 class="sub-title">Create a new account</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= site_url('register') ?>">
        <?= csrf_field() ?>

        <div>
            <label>Username</label><br>
            <input type="text" name="username"  value="<?= old('username') ?>" required>
        </div>

        <div>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </div>

        <div>
            <label>Confirm Password</label><br>
            <input type="password" name="confirm_password" required>
        </div>

        <div>
            <label>I am a:</label><br>
            <select name="role_id" required>
                <option value="">-- Select Role --</option>
                <option value="2" <?= old('role_id') == '2' ? 'selected' : '' ?>>Homeowner</option>
                <option value="3" <?= old('role_id') == '3' ? 'selected' : '' ?>>Contractor</option>
            </select>
        </div>
        <div>
            <button type="submit">Create Account</button>
        </div>
    </form>
    <p>
        Already have an account? <a href="<?= site_url('login') ?>">Login here</a>
    </p>



</section>
<?= $this->endSection() ?>