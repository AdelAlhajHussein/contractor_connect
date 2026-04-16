<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
    <style>

        body{
            margin:0;
            font-family: Arial, Helvetica, sans-serif;
            background:#f4f7fb;
        }

        /* Main container */
        .media-container{
            max-width: 900px;
            margin: 80px auto;
            padding: 40px;
            background:white;
            border-radius:12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            text-align:center;
        }

        /* Title */
        .media-title{
            font-size:36px;
            margin-bottom:20px;
            color:#222;
        }

        /* Coming soon box */
        .coming-box{
            margin-top:20px;
            padding:30px;
            background:#eef2f7;
            border-radius:10px;
            font-size:20px;
            color:#555;
            border:2px dashed #ccc;
        }

        /* Icon */
        .coming-icon{
            font-size:50px;
            margin-bottom:10px;
        }

    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="media-container">

        <div class="media-title">
            Social Media Integration
        </div>

        <div class="coming-box">

            <div class="coming-icon">
                📱
            </div>

            Social media features are currently under development.
            <br>
            <strong>Coming Soon</strong>

        </div>

    </div>

<?= $this->endSection() ?>