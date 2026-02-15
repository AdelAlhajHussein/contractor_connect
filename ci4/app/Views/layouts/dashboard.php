<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Dashboard' ?> | Contractor Connect</title>
    <link rel="stylesheet" href="<?= base_url('css/App.css') ?>">
    <?= $this->renderSection('page_css') ?>
</head>
<body>
<!-- Default Dashboard Layout -->
    <!-- header -->
    <?= view('components/header') ?>

    <div class="dashboard-wrapper">
        <!-- TODO: ?= view('components/sidebar', ['userType' => $userType ?? 'guest']) ?-->

        <main class="dashboard-content">
            <?= $this->renderSection('content') ?>
        </main>
    </div>


    <!-- TODO: ?= view('components/footer') ? -->
    <!-- Load scripts -->
    <?= $this->renderSection('page_js') ?>
</body>
</html>