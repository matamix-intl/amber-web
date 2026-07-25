# amber-web

WordPress 7.0 site, containerised with Docker.

## Running locally

Requires Docker Desktop.

```bash
cp .env.example .env
```

Fill in `.env`: pick a `DB_PASSWORD` and `DB_ROOT_PASSWORD`, and paste a fresh
set of salts from <https://api.wordpress.org/secret-key/1.1/salt/> (copy just the
values, without the PHP `define(...)` wrapper).

Then:

```bash
docker compose up -d --build
```

The site comes up at <http://localhost:8080>. Change the port with `HTTP_PORT`
in `.env`.

```bash
docker compose logs -f wordpress   # tail logs
docker compose down                # stop, keeping data
docker compose down -v             # stop and delete database + uploads
```

## How it fits together

- **`Dockerfile`** — builds on `wordpress:php8.3-apache` and copies this site's
  code in. `mod_rewrite` and `AllowOverride All` are enabled so permalinks work.
- **`docker-compose.yml`** — the `wordpress` service plus a MySQL 8.4 `db`.
  WordPress waits on the database's healthcheck before starting.
- **Volumes** — `db_data` holds the database, `uploads` holds media. Both survive
  `down` and rebuilds. Site code lives in the image, so a rebuild ships changes.

## Configuration and secrets

`wp-config.php` is **not** committed — it holds database credentials and auth
salts. In the container it is generated at startup from the `WORDPRESS_*`
environment variables in `docker-compose.yml`, which read from `.env`.

`.env` is gitignored. Keep it that way; commit `.env.example` instead. For a
production deploy, supply these through your host's secret manager rather than
a file on disk.

## First run

An empty database means WordPress shows its install screen on first visit. To
carry over content from an existing site, export the database and import it:

```bash
docker compose exec -T db mysql -u root -p"$DB_ROOT_PASSWORD" amber_web < dump.sql
```

Copy any existing media into the uploads volume as well:

```bash
docker compose cp ./wp-content/uploads/. wordpress:/var/www/html/wp-content/uploads/
```
