# Deployment Guide

This document explains how to run the Hospital Management System (HMS) locally, push it to GitHub, and deploy it to a production server.

---

## 1. Requirements

| Tool | Version |
| --- | --- |
| PHP | 8.2+ (with `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`) |
| Composer | 2.x |
| MySQL | 8.0+ (or MariaDB 10.6+) |
| Node.js | Optional (only if you add a JS build pipeline) |
| Docker | 24+ (optional, for container deployment) |

---

## 2. Local Setup (without Docker)

```bash
# 1. Clone the repository
git clone https://github.com/<your-username>/hospital-management-system.git
cd hospital-management-system

# 2. Install PHP dependencies
composer install

# 3. Create the environment file
cp .env.example .env

# 4. Generate the application key
php artisan key:generate

# 5. Create the MySQL database
mysql -u root -p -e "CREATE DATABASE hms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Update .env with your database credentials
#    DB_DATABASE=hms
#    DB_USERNAME=root
#    DB_PASSWORD=your-password

# 7. Run migrations and seed demo data
php artisan migrate --seed

# 8. Start the development server
php artisan serve
```

Open <http://localhost:8000> and sign in with a demo account (password: `password`):

| Role | Email |
| --- | --- |
| Admin | `admin@hms.test` |
| Doctor | `doctor@hms.test` |
| Nurse | `nurse@hms.test` |
| Pharmacist | `pharmacist@hms.test` |
| Patient | `patient@hms.test` |

---

## 3. Docker Setup (recommended for cloud)

The repository ships with a `Dockerfile` (PHP 8.2-FPM) and `docker-compose.yml` (app + Nginx + MySQL 8).

```bash
# Build and start the stack
docker compose up -d --build

# Watch the boot/migration logs
docker compose logs -f app

# The app is available at:
# http://localhost:8080
```

### Environment variables for Docker

Create a `.env` file next to `docker-compose.yml` (a separate file from Laravel's `.env`):

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://hms.example.com
APP_PORT=8080

DB_DATABASE=hms
DB_USERNAME=hms
DB_PASSWORD=change-me
DB_ROOT_PASSWORD=change-root-me

# Set to true only on the first boot to load demo data
RUN_SEEDERS=false
```

The `app` container's `entrypoint.sh` automatically:

1. Copies `.env.example` to `.env` if missing.
2. Runs `php artisan key:generate` if no key is present.
3. Runs `php artisan migrate --force`.
4. Optionally seeds demo data when `RUN_SEEDERS=true`.
5. Caches config, routes and views.

> **Important:** the container generates the `.env` from `.env.example`, which points `DB_HOST` to `127.0.0.1`. Docker overrides `DB_HOST=db`, `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD` through `docker-compose.yml`, so no manual edit is needed.

### Useful commands

```bash
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
docker compose exec app php artisan tinker
docker compose down            # stop
docker compose down -v         # stop and delete database volume
```

---

## 4. Push the Repository to GitHub

```bash
# From the project root
git init
git add .
git commit -m "Initial commit: multi-tier hospital management system"

# Create the repository on GitHub first (or use the GitHub CLI)
gh repo create hospital-management-system --public --source=. --remote=origin --push

# Or manually:
git branch -M main
git remote add origin https://github.com/<your-username>/hospital-management-system.git
git push -u origin main
```

`vendor/`, `.env`, logs and compiled caches are excluded by `.gitignore`. Never commit `.env`.

---

## 5. Deploy Live

### Option A — VPS (Ubuntu 22.04/24.04 + Nginx + PHP-FPM + MySQL)

```bash
# 1. Server packages
sudo apt update
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-intl unzip git composer

# 2. Database
sudo mysql -e "CREATE DATABASE hms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'hms'@'localhost' IDENTIFIED BY 'strong-password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON hms.* TO 'hms'@'localhost'; FLUSH PRIVILEGES;"

# 3. Deploy code
sudo mkdir -p /var/www/hospital-management-system
sudo chown -R $USER:www-data /var/www/hospital-management-system
git clone https://github.com/<your-username>/hospital-management-system.git /var/www/hospital-management-system
cd /var/www/hospital-management-system

# 4. Install & configure
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate

# Edit .env:
#   APP_ENV=production
#   APP_DEBUG=false
#   APP_URL=https://your-domain.com
#   DB_DATABASE=hms
#   DB_USERNAME=hms
#   DB_PASSWORD=strong-password

# 5. Database, cache and permissions
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```

Create `/etc/nginx/sites-available/hms`:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/hospital-management-system/public;

    index index.php;
    charset utf-8;
    client_max_body_size 25M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable it and add HTTPS:

```bash
sudo ln -s /etc/nginx/sites-available/hms /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

Laravel scheduler (optional, for future cron jobs):

```cron
* * * * * cd /var/www/hospital-management-system && php artisan schedule:run >> /dev/null 2>&1
```

### Option B — Docker on a cloud VM (DigitalOcean, AWS EC2, Azure VM, etc.)

```bash
# 1. Provision an Ubuntu VM and install Docker
sudo apt update
sudo apt install -y docker.io docker-compose-v2
sudo usermod -aG docker $USER   # re-login afterwards

# 2. Clone the repo
git clone https://github.com/<your-username>/hospital-management-system.git
cd hospital-management-system

# 3. Create the compose .env (see section 3) and start
docker compose up -d --build

# 4. Put a reverse proxy (Nginx/Caddy/Traefik) or cloud load balancer in front
#    of port 8080 and terminate TLS there.
```

For managed hosts (Render, Railway, Fly.io, Laravel Forge, etc.):

- Build command: `composer install --no-dev --optimize-autoloader`
- Start command: `php artisan migrate --force && php-fpm` (or use the provided Dockerfile)
- Attach a managed MySQL database and set `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` in the dashboard.
- Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY` (run `php artisan key:generate --show` locally and paste the value).

### Option C — Shared hosting (cPanel)

1. Upload the project outside `public_html` and point the domain document root to `public/`.
2. Run `composer install --no-dev --optimize-autoloader` via SSH or the cPanel terminal.
3. Copy `.env.example` to `.env`, set database credentials, then run `php artisan key:generate` and `php artisan migrate --force`.
4. Ensure `storage/` and `bootstrap/cache/` are writable (`755`/`775`).

---

## 6. Production Checklist

- [ ] `APP_ENV=production` and `APP_DEBUG=false`
- [ ] `APP_KEY` generated and backed up
- [ ] Strong MySQL password; database user limited to the `hms` schema
- [ ] HTTPS enforced (Certbot or cloud load balancer)
- [ ] `php artisan config:cache route:cache view:cache` executed after deploy
- [ ] `storage/` and `bootstrap/cache/` writable by the web user
- [ ] Demo seeders disabled (`RUN_SEEDERS=false`) or removed
- [ ] Regular MySQL backups (`mysqldump`) scheduled
- [ ] Log rotation configured for `storage/logs`
- [ ] Firewall allows only 22/80/443

---

## 7. Updating a Live Deployment

```bash
cd /var/www/hospital-management-system
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
sudo systemctl reload php8.2-fpm nginx
```

With Docker: `git pull && docker compose up -d --build`.
