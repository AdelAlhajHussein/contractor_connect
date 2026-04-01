<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">

<div class="auth-container">

    <div class="auth-card">

        <!-- Left side form -->
        <div class="auth-form">

            <div class="auth-title">Login</div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="auth-error">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('login') ?>">

                <?= csrf_field() ?>

                <div class="auth-group">

                    <label>Email</label>

                    <input type="email"
                           name="email"
                           required>

                </div>

                <div class="auth-group">

                    <label>Password</label>

                    <input type="password"
                           name="password"
                           required>

                </div>

                <button class="auth-btn" type="submit">
                    Login
                </button>

                <div class="auth-footer">
                    Don't have an account?
                    <a href="<?= site_url('register') ?>">Register here</a>
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
