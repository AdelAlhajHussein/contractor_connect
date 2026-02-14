<header class="navbar">

    <div class="nav-left">
        <a href="<?= base_url('/') ?>" class="logo">
            Contractor Connect
        </a>
    </div>

    <div class="nav-right">

        <a class="nav-link" href="<?= base_url('/') ?>">Home</a>

       <!-- <a class="nav-link" href="<?= base_url('about') ?>">About</a>-->
        <a class="nav-link" href="<?= base_url('about') ?>">About</a>

        <?php if (session()->get('logged_in')): ?>

            <span class="username">
                Welcome, <?= esc(session()->get('username')) ?>
            </span>

            <a class="nav-link logout-btn" href="<?= base_url('/logout') ?>">
                Logout
            </a>

        <?php else: ?>

            <a class="nav-link" href="<?= base_url('/login') ?>">
                Sign In
            </a>

            <a class="nav-link register-btn" href="<?= base_url('/register') ?>">
                Create Account
            </a>

        <?php endif; ?>

    </div>

</header>
