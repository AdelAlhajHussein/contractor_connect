<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('page_css') ?>
    <link rel="stylesheet" href="<?= base_url('css/admin-dashboard.css') ?>">
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="dashboard-container">

    <div class="dashboard-title">
        <?= $title ?? 'Dashboard' ?>
    </div>

<!-- Data View Categories -->
    <div class="dashboard-grid">
        <!-- Default to user data on load -->
        <div class="dashboard-item">
            <img src="<?= base_url('img/categories.png') ?>" alt="Categories logo">
            <!--a href="<?= site_url('admin/categories') ?>">Categories</a-->
            <a href="#" class="ajax-link" data-target="categories">Categories</a>
        </div>

        <div class="dashboard-item">
            <img src="<?= base_url('img/user_logo.png') ?>" alt="user logo">
            <a href="#" class="ajax-link" data-target="users">Users</a>
        </div>
        <div class="dashboard-item">
            <img src="<?= base_url('img/contractor.png') ?>" alt="contractors logo">
            <a href="#" class="ajax-link" data-target="contractors">Contractors</a>
        </div>
        <div class="dashboard-item">
            <img src="<?= base_url('img/report.png') ?>" alt="report logo">
            <a href="#" class="ajax-link" data-target="reports">Admin Reports</a>
        </div>


        <div class="dashboard-item">
            <img src="<?= base_url('img/payment.png') ?>" alt="payment logo">
            <a href="<?= site_url('admin/payments') ?>">Payments & Subscriptions</a>
        </div>


        <div class="dashboard-item">
            <img src="<?= base_url('img/homeowner.png') ?>" alt="homeowner logo">
            <a href="<?= site_url('admin/homeowners') ?>">Homeowners</a>
        </div>
        <div class="dashboard-item">
            <img src="<?= base_url('img/project.png') ?>" alt="Project logo">
            <a href="<?= site_url('admin/projects') ?>">Projects</a>
        </div>
        <div class="dashboard-item">
            <img src="<?= base_url('img/bid.png') ?>" alt="bid logo">
            <a href="<?= site_url('admin/bids') ?>">Bids</a>
        </div>
        <div class="dashboard-item">
            <img src="<?= base_url('img/rate.png') ?>" alt="Ratings & Reviews logo">
            <a href="<?= site_url('admin/ratings') ?>">Ratings & Reviews</a>
        </div>
    </div>

    <!-- Search Form -->
    <form id="ajax-filter-form" method="get" action="<?= site_url('admin/users') ?>" class="filter-form">

        <input
                type="text"
                name="q"
                placeholder="Search username, name, or email..."
                value="<?= esc($_GET['q'] ?? '') ?>"
        >

        <select name="role_id">
            <option value="">All Roles</option>
            <option value="1" <?= (($_GET['role_id'] ?? '') === '1') ? 'selected' : '' ?>>Admin</option>
            <option value="2" <?= (($_GET['role_id'] ?? '') === '2') ? 'selected' : '' ?>>Homeowner</option>
            <option value="3" <?= (($_GET['role_id'] ?? '') === '3') ? 'selected' : '' ?>>Contractor</option>
        </select>

        <select name="status">
            <option value="">All Status</option>
            <option value="1" <?= (($_GET['status'] ?? '') === '1') ? 'selected' : '' ?>>Active</option>
            <option value="0" <?= (($_GET['status'] ?? '') === '0') ? 'selected' : '' ?>>Inactive</option>
        </select>

        <button type="submit">Filter</button>

        <a href="<?= site_url('admin/users') ?>" class="reset-link">Reset</a>

    </form>

    <!-- Table gets loaded in here -->
    <div id="table-content">
        <p>Select a view</p>
    </div>

</div>

<script>

    let currentTarget = 'users';
    function loadTable(target) {
        currentTarget = target; // update global variable

        const resultDiv = document.getElementById('table-content');
        const form = document.getElementById('ajax-filter-form');

        // Process form
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        // Prepare url
        const url = `<?= site_url('admin/dashboard/get_table/') ?>${target}?${params}`;

        resultDiv.innerHTML = "<p>Loading...</p>";

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Network response 500 or 404');
                return response.text();
            })
            .then(html => {
                resultDiv.innerHTML = html;
            })
            .catch(err => {
                resultDiv.innerHTML = `<p>Error: ${err.message}</p>`;
                console.error('Fetch Error:', err);
            });
    }

    // Handle category/nav clicks
    document.querySelectorAll('.ajax-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('data-target');
            loadTable(target);
        });
    });

    // Handle filter form submit
    document.getElementById('ajax-filter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        // Filter to the target use type
        loadTable(currentTarget);
    });
</script>
<?= $this->endSection() ?>

