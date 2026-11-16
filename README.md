# Hospital Management System (HMS)

A production-ready, multi-tier Hospital Management System built with **Laravel 12**, **MySQL 8** and **Bootstrap 5**. It provides role-based dashboards and CRUD workflows for administrative staff, doctors, nurses, pharmacists and patients.

## Features

- **Role-Based Access Control** — custom `role` middleware with granular route protection for `admin`, `doctor`, `nurse`, `pharmacist` and `patient`.
- **Patient management** — registration, medical profile, emergency contacts and insurance details (admin).
- **Appointments** — scheduling, department/doctor allocation, status lifecycle (admin, doctor, patient views).
- **Medical records** — diagnoses, symptoms, treatments, prescriptions and visit history.
- **Prescriptions** — medication, dosage, dispensing status and pharmacist attribution.
- **Responsive Bootstrap 5 UI** — collapsible sidebar, data tables, validation feedback, role-specific navigation.
- **Secure authentication** — hashed passwords, session regeneration, inactive-account lockout, CSRF protection.
- **Production deployment** — `Dockerfile`, `docker-compose.yml` (app + Nginx + MySQL), `.env.example` and a full `DEPLOYMENT.md`.
- **Tested** — PHPUnit feature tests covering authentication and RBAC boundaries.

## Tech Stack

| Layer | Technology |
| --- | --- |
| Framework | Laravel 12 |
| Language | PHP 8.2+ |
| Database | MySQL 8 (SQLite supported for tests) |
| Frontend | Blade + Bootstrap 5.3 + Bootstrap Icons (CDN) |
| Auth | Laravel session guard |
| Containers | Docker, Nginx, PHP-FPM, MySQL |

## Quick Start

```bash
composer install
cp .env.example .env
php artisan key:generate

# Configure DB_* in .env, then:
php artisan migrate --seed
php artisan serve
```

Visit <http://localhost:8000>.

### Demo accounts (password: `password`)

| Role | Email |
| --- | --- |
| Admin | `admin@hms.test` |
| Doctor | `doctor@hms.test` |
| Nurse | `nurse@hms.test` |
| Pharmacist | `pharmacist@hms.test` |
| Patient | `patient@hms.test` |

## Docker Quick Start

```bash
docker compose up -d --build
# open http://localhost:8080
```

See [DEPLOYMENT.md](DEPLOYMENT.md) for GitHub publishing and cloud/VPS deployment instructions.

## Project Structure

```text
app/
  Http/
    Controllers/          # Auth, Dashboard, Admin, Doctor, Patient, Staff
    Middleware/           # RoleMiddleware (RBAC)
    Requests/             # Form request validation
  Models/                 # User, Patient, Department, Appointment, MedicalRecord, Prescription
database/
  migrations/             # Departments, users, patients, appointments, records, prescriptions
  seeders/                # DepartmentSeeder + DatabaseSeeder (mock data)
  factories/              # UserFactory
resources/views/
  layouts/app.blade.php   # Shared Bootstrap 5 layout + sidebar
  admin/ doctor/ patient/ staff/ auth/
routes/web.php            # Role-protected route groups
docker/                   # Nginx config + entrypoint
```

## Data Model (high level)

- `User` 1‑1 `Patient` (patients only), `User` *‑1 `Department`
- `Patient` 1‑N `Appointment`, 1‑N `MedicalRecord`
- `Department` 1‑N `Appointment`, 1‑N `User`
- `Appointment` 1‑1 `MedicalRecord`
- `MedicalRecord` 1‑N `Prescription`, `Prescription` *‑1 `User` (pharmacist)

## Security Notes

- Passwords are hashed with bcrypt (12 rounds by default).
- Inactive users are rejected at login and by middleware.
- Doctors can only open medical records they authored; patients only see their own data.
- Admin CRUD routes are protected by `auth` + `role:admin`.
- Validate and sanitize all input through Form Requests and controller validation.

## Testing

```bash
php artisan test
```

Tests use in-memory SQLite, so no MySQL server is required to run them.

## License

MIT
