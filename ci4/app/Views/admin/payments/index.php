<?= $this->extend('layouts/main') ?>
<?= $this->section('page_css') ?>
<style>

    body{
        margin:0;
        font-family: Arial, Helvetica, sans-serif;
        background:#f4f7fb;
    }


    .payment-container{
        max-width: 900px;
        margin: 80px auto;
        padding: 40px;
        background:white;
        border-radius:12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        text-align:center;
    }

    .payment-title{
        font-size:36px;
        margin-bottom:20px;
        color:#222;
    }


    .coming-box{
        margin-top:20px;
        padding:30px;
        background:#eef2f7;
        border-radius:10px;
        font-size:20px;
        color:#555;
        border:2px dashed #ccc;
    }


    .coming-icon{
        font-size:50px;
        margin-bottom:10px;
    }

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="payment-container">

    <div class="payment-title">
        Payments & Subscriptions
    </div>

    <div class="coming-box">

        <div class="coming-icon">
            💳
        </div>

        Payments & subscriptions section
        <br>
        <strong>Coming Soon</strong>


        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-danger">
            ← Back to Dashboard
        </a>

    </div>

</div>
<?= $this->endSection() ?>


