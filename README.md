# Contractor Connect
Contractor Connect is a high-performance web platform built with CodeIgniter 4, 
designed to streamline the connection between homeowners and trade professionals. 

The application facilitates project bidding, contractor discovery, 
and administrative oversight within a secure, role-based ecosystem.

## The Team:
**Member 1** Adel Alhaj Hussein - Back-end
**Member 2** Mehdi Jazi - Database
**Member 3** Eric Laudrum - Quality Assurance & Testing
**Member 4** Sana Karnelia - Front-end
**Member 5** Shifa Karnelia - Front-end

## Core Features
- **Role-Based Access Control:** Dedicated, secure workflows and dashboards for Admin, Homeowners, and Contractors
- **Project Management Lifecycle:** Full support for job postings, bidding, project status tracking, and project cancellation
- **Bid & Rating System:** Bidding system allowing contractors to submit detailed estimates and homeowners to provide post-completion ratings
- **Admin Oversight:** A centralized dashboard for managing user accounts, verifying contractor approvals, and auditing project listings

### Tech Stack
- **Framework:** CodeIgniter 4.5
- **Language:** PHP 8.4+
- **Database:** SQLite/MySQL
- **QA & Testing:** PHPUnit 11, Xdebug 3.5.1
- **Data Simulation:** Faker Library

### Quality Assurance
- **Comprehensive Feature Tests:** A full suite of automated tests covering authentication, session persistence, and data integrity
- **Code Coverage:** Achieved **92% line coverage** across the core application logic, as verified via Xdebug
- **Data Simulation:** Leveraged the **Faker** library to generate unique test datasets, ensuring robust validation of unique constraints and edge cases
- **Regression Safety:** Full execution of migrations and database refreshing (`DatabaseTestTrait`) before every test run to ensure a consistent testing state

### Running the Test Suite
To execute the full test suite and generate a report:

```bash
# Execute tests
vendor/bin/phpunit

# Generate HTML Coverage Report (requires Xdebug)
XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-html build/coverage
```

## Configuration & Setup

1. **Environment:** Copy configure database credentials in .env
2. **Database:** Run migrations to establish the schema and seed the initial data
   ```bash
   php spark migrate
   php spark db:seed RoleSeeder

3. **Validation:** Ensure server meets PHP 8.4 requirements for full compatibility




## Getting Started

## Prerequisites
- **PHP 8.4+**
- **Composer**
- **SQLite3** or **MySQL**

### Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/AdelAlhajHussein/contractor_connect.git](https://github.com/AdelAlhajHussein/contractor_connect.git)
   cd contractor_connect/ci4
   ```

2. **Install dependencies:**
    ```bash
    composer install
    ```
3. **Initialize the environment:**
4. ```bash
    cp env .env
    ```
5. **Run the App:**
    Navigate to ci4 directory and run
    ```bash
    php spark serve --port 8000
    ```
    For environments mimicking Cpanel structure (with public_html):
    ```bash
     php spark serve --public ../public_html --port 8000
   ```

## 🛠 Development Workflow

To maintain code quality and branch integrity, please follow these conventions:

### Branch Naming Convention
- **Features:** `feature/short-description` 
  - (ex: `feature/add-login-form`)
- **Bugfixes:** `bugfix/short-description` 
  - (ex: `bugfix/fix-login-error`)

### Standard Git Process

1. **Pull latest changes:**
   ```bash
   git pull origin main

2. **Create a local branch:**
   ```bash
   git checkout -b <branch-name>

3. **Stage and commit changes:**
   ```bash
   git add .
   git commit -m "Brief description of changes"

4. **Push to GitHub:**
   ```bash
   git push origin <branch-name>

5. **Merge:**
   Create a pull request on GitHub. Resolve any conflicts before merging into the 'main' branch.