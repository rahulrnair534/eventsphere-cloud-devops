# EventSphere – Event Management Portal
PHP 8 · MySQL (PDO) · Bootstrap 5 · Nginx + PHP-FPM · Docker

## Run locally (Docker)
1. `cp .env.example .env` and set passwords.
2. `mkdir -p uploads && chmod 777 uploads`
3. `docker compose up -d --build`
4. Open http://localhost:8080 (first start takes ~30 s while MySQL imports `database/schema.sql`).
- Admin: http://localhost:8080/admin/login.php → `admin@eventsphere.com` / `password` (change it in Admin → Profile).
- Users: sign up from the site.
- Reset DB: `docker compose down -v && docker compose up -d`

## Deploy on AWS EC2 (Ubuntu) + RDS
1. Create RDS MySQL 8 (db.t3.micro). Security group: allow 3306 only from the EC2 security group.
2. Create the DB, user and import schema from EC2:
   `mysql -h <rds-endpoint> -u admin -p -e "CREATE DATABASE eventsphere"` then `mysql -h <rds-endpoint> -u admin -p eventsphere < database/schema.sql`
3. On EC2: `sudo apt update && sudo apt install -y docker.io docker-compose-v2 git && sudo usermod -aG docker $USER` (re-login).
4. Clone the repo, `cp .env.example .env`, and set `DB_HOST=<rds-endpoint>`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `APP_ENV=production`.
5. Delete the `db` service (and `depends_on: [db]`) from `docker-compose.yml`, change web port to `"80:80"`.
6. `mkdir -p uploads && chmod 777 uploads && docker compose up -d --build`
7. Open EC2 security group port 80 and visit `http://<ec2-public-ip>`.

## Without Docker (native Nginx)
`sudo apt install nginx php-fpm php-mysql`, copy project to `/var/www/html`, use `docker/nginx.conf` as the server block
(change `fastcgi_pass` to `unix:/run/php/php8.3-fpm.sock`) and add `fastcgi_param DB_HOST ...;` lines for each env variable.

## Structure
`includes/` shared code (init.php = config+DB+auth+helpers) · `user/` user area · `admin/` admin area · `database/schema.sql` · `docker/` Nginx config.
Security: password_hash, PDO prepared statements, CSRF tokens, output escaping, role checks, validated uploads, blocked internal folders in Nginx.
