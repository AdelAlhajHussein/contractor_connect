<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">

<div class="auth-container">

    <div class="auth-card">

        <!-- Left side form -->
        <div class="auth-form">

            <h1 class="auth-title">Create an account</h1>

            <section class="registration-section">

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="auth-error">
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= site_url('register') ?>">

                    <?= csrf_field() ?>

                    <div class="auth-group">
                        <label>Username</label>
                        <input type="text"
                               name="username"
                               value="<?= old('username') ?>"
                               required>
                    </div>

                    <div class="auth-group">
                        <label>Password</label>
                        <input type="password"
                               name="password"
                               required>
                    </div>

                    <div class="auth-group">
                        <label>Confirm Password</label>
                        <input type="password"
                               name="confirm_password"
                               required>
                    </div>

                    <div class="auth-group">
                        <label>I am a:</label>

                        <select name="role_id"
                                required
                                class="auth-group input">

                            <option value="">-- Select Role --</option>

                            <option value="2"
                                <?= old('role_id') == '2' ? 'selected' : '' ?>>
                                Homeowner
                            </option>

                            <option value="3"
                                <?= old('role_id') == '3' ? 'selected' : '' ?>>
                                Contractor
                            </option>

                        </select>

                    </div>

                    <button class="auth-btn" type="submit">
                        Create Account
                    </button>

                </form>

                <div class="auth-footer">
                    Already have an account?
                    <a href="<?= site_url('login') ?>">Login here</a>
                </div>

            </section>

        </div>


        <!-- Right side image -->
        <div class="auth-image">

            <img src="<?= base_url('img/auth-image.png') ?>" alt="Register Image">

        </div>

    </div>

</div>

<?= $this->endSection() ?>
