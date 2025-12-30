# Laravel_url_shortener
A multi-company URL shortener service built with Laravel 10 featuring role-based access control, invitation-based onboarding, AJAX-driven dashboards, and public short URL resolution.  This project demonstrates real-world Laravel architecture, clean authorization, and production-grade patterns.

# Features
Laravel Sanctum
Spatie Roles & Permissions
Roles: SuperAdmin, Admin, Member

# Multiple Company System
* Superadmin creates companies by inviting admins.
* Each user belongs to a company (expect superadmin).
* Each active company has atleast one admin.
* Superadmin can invite only one or first admin.
* After accepting invitation by first admin company status get active.
* Admin can invite admin or other members of the company.
* Strict data isolation among companies.

# Invitation System
* Secure token based invites.
* Role aware invitations:
    * Superadmin -> Admin (new company)
    * Admin -> Admin/Member (same company)
* Invitation expiry
* Email-based onboarding

# URL Shortener

# Dashboards

# Tech Stack Used
  * Laravel 10
  * PHP 8.2.12
  * Database MySQL
  * Auth Sanctum
  * Spatie Permission
  * UI Bootstrap5

# Local Setup
  * git clone https://github.com/Cemp412/Laravel_url_shortener.git
  * cd <repository-name>

# Dependencies
  * composer install
  * npm install && npm run build && npm run dev
  * composer dump -autoload

# Environment Setup
  * Copy env.example to .env
  * Update .env:   
      DB_DATABASE = your_database  
      DB_USERNAME =  username  
      DB_PASSWORD = your_password  
      MAIL_MAILER = log  
    
# Run Migrations and Seeders
  * php artisan migrate
  * php artisan db:seed 

# Start the server
  * php artisan serve

# Default Credentials
  Email: superadmin@example.com   
  Password: secret$String
  
  
