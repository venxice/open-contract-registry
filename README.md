# Open Contract Register

A public bidding portal and admin panel built with CodeIgniter 4.

## Features

- Public contract/bidding records viewing
- Admin panel with user management (CRUD)
- Google SSO login
- Email/password login with password hashing
- Audit trail (login, logout, user CRUD, bidding CRUD)
- File attachments (PDF uploads)
- PHP peso (₱) currency formatting
- Responsive design (DM Sans, Libre Franklin, Space Mono)

## Requirements

- PHP 8.2+
- MySQL
- Composer

## Installation

```bash
git clone https://github.com/venxice/open-contract-registry.git
cd open-contract-registry
composer install
```

## Setup

### 1. Environment File

```bash
cp env .env
```

Edit `.env`:

```env
app.baseURL = 'http://localhost:8888'

database.default.hostname = localhost
database.default.database = dbm_db
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

### 2. Database

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS dbm_db;"
mysql -u root dbm_db < app/Database/dbm_db.sql
```

The SQL dump includes all tables and sample data.

### 3. Google SSO (Optional)

To enable Google login, you need to set up OAuth 2.0 credentials:

1. Go to [Google Cloud Console](https://console.cloud.google.com/apis/credentials)
2. Create OAuth 2.0 Client ID (Web application)
3. Under **Authorized JavaScript origins**, add:
   ```
   http://localhost:8888
   ```
4. Under **Authorized redirect URIs**, add:
   ```
   http://localhost:8888/api/auth/google/callback
   ```
5. Copy your Client ID and Client Secret into `.env`:

```env
app.googleClientId = 'YOUR_CLIENT_ID_HERE'
app.googleClientSecret = 'YOUR_CLIENT_SECRET_HERE'
```

> **Note:** Do not commit your `.env` file with real credentials to GitHub.

## Running the App

```bash
php -S localhost:8888 -t public/ router.php
```

Open [http://localhost:8888](http://localhost:8888)

## Admin Panel

Open [http://localhost:8888/admin/login](http://localhost:8888/admin/login)

**Default user:**
- Email: `admin@example.com`
- Password: `admin123`

## Project Structure

```
app/
├── Controllers/
│   ├── Admin/          # Admin view controllers
│   ├── Api/            # REST API (Auth, Bidding, User, Upload, AuditLog)
│   └── Public/         # Public view controllers
├── Models/             # UserModel, AuditLogModel
├── Views/
│   ├── admin/          # Admin panel (index, login)
│   └── public/         # Public page
├── Config/
│   └── Routes.php      # All routes
├── Database/
│   └── dbm_db.sql      # Database dump with data
public/
├── assets/
│   ├── css/            # style.css, admin.css
│   └── js/             # public.js, admin.js
└── uploads/            # Uploaded PDF files
```

## Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/` | Public page |
| GET | `/admin` | Admin panel |
| GET | `/admin/login` | Login page |
| POST | `/api/auth/login` | Email/password login |
| POST | `/api/auth/logout` | Logout |
| GET | `/api/auth/check` | Check session |
| GET | `/api/auth/google` | Google SSO redirect |
| GET | `/api/auth/google/callback` | Google SSO callback |
| GET | `/api/biddings` | List biddings |
| POST | `/api/biddings` | Create bidding |
| PUT | `/api/biddings/:id` | Update bidding |
| DELETE | `/api/biddings/:id` | Delete bidding |
| GET | `/api/users` | List users |
| POST | `/api/users` | Create user |
| PUT | `/api/users/:id` | Update user |
| DELETE | `/api/users/:id` | Delete user |
| GET | `/api/audit-logs` | List audit logs |
| POST | `/api/upload` | Upload file |
