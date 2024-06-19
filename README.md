# User Managment App (Laravel, Vue project)

## Project Overview

This project consists of a backend and frontend application built using Laravel and Vue.js with JWT authentication. The backend provides a simple API for user management including login, create, edit, and delete operations. JWT tokens are used for authentication, allowing users to perform CRUD operations securely both via the API and frontend.

### Backend Features

- **Framework**: Built on Laravel, utilizing its robust features for API development.
- **Authentication**: JWT (JSON Web Tokens) used for user authentication.
- **User Management**: CRUD operations for users (Create, Read, Update, Delete).
- **Seeder**: Includes a seeder for initial admin user creation during database migrations.

### Frontend Features

- **Framework**: Vue.js used for frontend development.
- **HTTP Requests**: Axios library employed to consume backend API endpoints.
- **Views**: Three main views implemented - Home, Login, and Dashboard:
  - **Home**: Display page for demonstration purposes.
  - **Login**: Form for user login with error handling.
  - **Dashboard**: Table displaying users with options to edit, change password, and delete. Additional functionality includes user creation (restricted to authenticated users) and route protection for unauthorized access.

## Installation and Dependencies

-Clone the repository:
git clone git@github.com/laravel-jwt-vue-vuetify.git

## Backend Setup

Navigate to the backend directory:
- `cd backend`

Install dependencies:
- `composer install`
- `composer update`

Configure `.env` file with database details:
- Set up your database configuration in `.env` file.

Run migrations and seed initial admin user:
- `php artisan migrate --seed`

To run tests using SQLite in-memory database:
- `php artisan test --filter UserControllerTest`

Start the backend server:
- `php artisan serve`

## Frontend Setup

Navigate to the frontend directory:
- `cd frontend`

Install dependencies:
- `npm install`

Start the development server:
- `npm run dev`

Access the project via the provided link in your browser.

## Project

Click on Login and enter using the default administrator credentials:
- Email: admin@example.com
- Password: password

Upon successful login, you will be redirected to the Dashboard.

The Dashboard displays registered users. Initially, only the admin user will be visible.

To register a new user, click on the "User Register" button.

Fill out the forms to register the new user.

Log out from the admin account.

### Log in with the new user account



