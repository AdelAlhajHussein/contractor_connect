<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Contractor_Connect' ?></title>
    <link rel="stylesheet" href="<?= base_url('css/App.css') ?>">
</head>
<body>

    <!-- header -->
     <?= view('components/header') ?>

     <main>
        <?= $this->renderSection('content') ?>
    </main>
    
    
</body>
</html>