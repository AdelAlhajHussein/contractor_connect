<?= $this->extend('layouts/main') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('css/homepage.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="services-page">

    <div class="services-title">Services</div>

    <!-- 1) Assemble (image on right) -->
    <section class="service-block right">
        <div class="service-card">
            <h2>Assemble</h2>
            <p>
                Need help assembling furniture, fixtures, or equipment? Contractor Connect helps homeowners quickly find skilled assembly professionals in their area.
                Instead of struggling with complex instructions, simply post your request and connect with verified contractors who can complete the job safely and efficiently.
            </p>
        </div>
        <div class="service-media">
            <img src="<?= base_url('img/assemble.png') ?>" alt="Assemble service">
        </div>
    </section>

    <!-- 2) Clean (image on left) -->
    <section class="service-block left">
        <div class="service-card">
            <h2>Clean</h2>
            <p>
                Looking for reliable cleaning services? Our platform connects homeowners with trusted local cleaning contractors for deep cleaning,
                routine maintenance, or move-in/move-out services. Compare options and choose the right professional for your needs.
            </p>
        </div>
        <div class="service-media">
            <img src="<?= base_url('img/clean.png') ?>" alt="Cleaning service">
        </div>
    </section>

    <!-- 3) Maintain (image on right) -->
    <section class="service-block right">
        <div class="service-card">
            <h2>Maintain</h2>
            <p>
                Regular property maintenance is essential for long-term value. Contractor Connect makes it easy to find experienced maintenance professionals
                for inspections, small fixes, and preventive care.
            </p>
        </div>
        <div class="service-media">
            <img src="<?= base_url('img/maintain.png') ?>" alt="Maintenance service">
        </div>
    </section>

    <!-- 4) Renovate (image on left) -->
    <section class="service-block left">
        <div class="service-card">
            <h2>Renovate</h2>
            <p>
                Planning a renovation project? We help homeowners connect with qualified renovation contractors for kitchens, bathrooms, basements, and more.
                Review options and choose the best contractor to bring your vision to life.
            </p>
        </div>
        <div class="service-media">
            <img src="<?= base_url('img/renovate.png') ?>" alt="Renovation service">
        </div>
    </section>

    <!-- 5) Repair (image on right) -->
    <section class="service-block right">
        <div class="service-card">
            <h2>Repair</h2>
            <p>
                From minor fixes to urgent repairs, Contractor Connect helps homeowners find reliable repair specialists quickly.
                Post your repair needs and connect with experienced contractors ready to restore functionality and safety.
            </p>
        </div>
        <div class="service-media">
            <img src="<?= base_url('img/repair.png') ?>" alt="Repair service">
        </div>
    </section>

    <!-- 6) Paint (image on left) -->
    <section class="service-block left">
        <div class="service-card">
            <h2>Paint</h2>
            <p>
                Ready to refresh your home with a new coat of paint? Our platform connects homeowners with professional painters
                for interior and exterior projects. Compare services and choose the right expert with confidence.
            </p>
        </div>
        <div class="service-media">
            <img src="<?= base_url('img/paint.png') ?>" alt="Painting service">
        </div>
    </section>



</div>

<?= $this->endSection() ?>
