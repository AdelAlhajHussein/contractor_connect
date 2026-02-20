# Project Name: Contractor Connect
A digital marketplace for home improvement jobs. 

## Tech Stack
- Linux
- Apache
- MySQL/MariaDB managed via CI4 Migrations
- Language: PHP 8.4

## Testing
This project uses PHPUnit for quality assurance testing.
- To run PHPUnit
    bash
    vendor/bin/phpunit

## Getting Started

## Installation/Setup: 
### 1. Clone the repository
    "git clone https://github.com/AdelAlhajHussein/contractor_connect.git"
### 2. Install dependencies
    bash
    composer install
### 3. Run the App
    Navigate to ci4 directory and run
    bash
    "php spark serve --port 8000"
    
    Cpanel requires css in /public_html/css, but ci4 expects it in /ci4/public/css
    "php spark serve --public ../public_html --port 8000"

    Then open in browser
    "http://localhost:8000"

### 4. Update code
    - To pull latest version of the code
    bash
    "git pull origin main"

    - Switch to local branch
    bash
    "git checkout -b <branch-name>"

    - Add file change to staging
    "git add <filename>"
    
    - Or stage all files with changes
    Navigate to root directory
    bash
    "git add ."
    
    - Push changes on branch
    bash
    "git push origin <branch-name>

    - Branch naming convention
    Begin with type ( feature / bugfix )
    Lowercase lettering with hyphens
    ex: "feature/add-login-form"
    ex: "bugfix/fix-login-error"

    - Merge with main
    Navigate to github and create a pull request
    Resolve any conflicts and merge with main branch


## The Team: 
**Member 1** Adel Alhaj Hussein - Back-end
**Member 2** Mehdi Jazi - Database
**Member 3** Eric Laudrum - Quality Assurance & Testing
**Member 4** Sana Karnelia - Front-end
**Member 5** Shifa Karnelia - Front-end

## Progress:
- [X] Initial project file setup
- [X] Create initial file layout
- [] Account creation and authentication
    - [X] Back-end functions and routing
    - [X] Front-end html
    - [] Front-end styling
- [] User login
    - [X] Back-end functions and routing
    - [X] Front-end html
    - [] Front-end styling
- [] Admin Dashboard
  - [X] Back-end functions and routing
  - [] Front-end html
  - [] Front-end styling
- [] Homeowner Dashboard
  - [] Back-end functions and routing
  - [] Front-end html
  - [] Front-end styling
- [] Contractor Dashboard
    - [] Back-end functions and routing
    - [] Front-end html
    - [] Front-end styling