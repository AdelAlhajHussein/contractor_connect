<!-- Use main page layout -->
 <?= $this->extend('layouts/main') ?>


<?= $this->section('content') ?>

<style>

    .about-container {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 60px 20px;
        min-height: 80vh;
        background-color: #f4f7fb;
    }


    .about-box {
        background-color: #ffffff;
        width: 100%;
        max-width: 900px;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        transition: all 0.3s ease;
    }




    .about-title {
        font-size: 40px;
        margin-bottom: 20px;
        color: #2c3e50;
        text-decoration: underline;
    }


    .about-content {
        font-size: 33px;
        line-height: 1.7;
        color: #333;
    }

    .about-content p {
        margin-bottom: 16px;
        color: #333 !important;
    }
    .about-closing {
        margin-top: 30px;
        font-weight: bold;
        color: #2c3e50;
        font-size: 33px;
    }


</style>


<div class="about-container">

    <div class="about-box">

        <h1 class="about-title">About Us</h1>

        <div class="about-content">

            <p>
                Contractor Connect is a platform designed to bridge the gap between
                homeowners and trustworthy contractors. Our goal is to help homeowners—especially
                those with little or no experience in renovation or construction—find reliable professionals
                for their home improvement projects.
            </p>

            <p>
                In today’s world, construction-related scams are
                unfortunately increasing. Many homeowners, particularly seniors,
                are targeted by dishonest individuals who demand upfront payment and
                then either perform poor-quality work or abandon the project entirely.
                These situations cause financial loss, stress, and safety concerns.
                Contractor Connect was created to protect homeowners from such risks by providing access to
                verified and trustworthy contractors.
            </p>

            <p>
                Our platform allows homeowners to review contractors’ profiles, including their
                past projects, ratings, and customer reviews. This transparency helps homeowners
                make informed decisions and choose contractors based on real performance and customer feedback,
                rather than relying solely on word-of-mouth or limited local information.
            </p>

            <p>
                In addition, during uncertain economic times, project costs can vary significantly.
                Well-known contractors may charge higher prices due to their reputation, even though many equally
                skilled and reliable contractors are available but less visible. Contractor Connect addresses this
                issue by providing a competitive bidding system, allowing multiple qualified contractors to submit
                proposals for a project. This helps homeowners receive fair pricing while giving skilled contractors equal opportunities to showcase their expertise.
            </p>
            <p>
                At Contractor Connect, our mission is to create a safe,
                transparent, and fair environment where homeowners can confidently connect with trusted contractors,
                compare options, and complete their projects with peace of mind.
            </p>
            <p class="about-closing">
                We look forward to helping you build with confidence. Happy building with Contractor Connect!!!
            </p>

        </div>


    </div>

</div>

<?= $this->endSection() ?>
