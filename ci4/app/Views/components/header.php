<header class="navbar custom-navbar sticky-top">
    <div class="container-fluid px-4 d-flex justify-content-between align-items-center">

        <div class="nav-left">
            <a href="<?= base_url('/') ?>" class="logo">
                Contractor Connect
            </a>
        </div>

        <div class="nav-right d-flex align-items-center gap-3">

            <a class="nav-link" href="<?= base_url('/') ?>">Home</a>
            <a class="nav-link" href="<?= base_url('about') ?>">About</a>

            <?php if (session()->get('logged_in')): ?>

                <?php
                $roleId = (int) session()->get('role_id');

                if ($roleId === 1) {
                    $dashboardUrl = site_url('admin/dashboard');
                } elseif ($roleId === 2) {
                    $dashboardUrl = site_url('homeowner/dashboard');
                } elseif ($roleId === 3) {
                    $dashboardUrl = site_url('contractor/dashboard');
                } else {
                    $dashboardUrl = site_url('/');
                }
                ?>

                <span class="username">
        Welcome!!!
    </span>


                <a class="nav-link " href="<?= $dashboardUrl ?>">
                    Dashboard
                </a>

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

    </div>
</header>