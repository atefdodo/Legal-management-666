<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<h2 align="center">⚖️ Legal Management System</h2>

<p align="center">
  Laravel-based legal system to manage company documents, court cases, sessions, and appeals — powered by the Vuexy admin template.
</p>

---

## 🚀 Features

- 📂 **Company Document Management**
  - Upload, edit, archive legal/company documents
  - Expiry date alerts & dashboard summary
  - AJAX-powered modals for CRUD

- 🏛️ **Court Case Tracking**
  - Manage cases, court names, dates
  - Assign cases to lawyers
  - Appeal linking

- ⏱️ **Court Session Scheduling**
  - Session dates, times, and statuses
  - Integrated with court cases

- 📑 **Appeals Management**
  - Create & relate appeals to original cases
  - Status tracking (won, lost, in progress)

- 🔐 **Secure Authentication**
  - Email verification, password reset
  - Role-based access (admin/lawyer/client)

- 🎨 **Vuexy Admin Template Integration**
  - Fully responsive UI
  - DataTables, modals, toasts, and charts

---

## 🛠️ Installation

```bash
git clone https://github.com/atefdodo/Legal-management-666.git
cd Legal-management-666
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan storage:link
