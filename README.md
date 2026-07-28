# amber-web

WordPress 7.0 site, containerised with Docker and deployed behind the shared
Traefik reverse proxy — the same setup as the other matamix sites.

## Structure

- `wp-content/themes/` — site themes
- `wp-content/plugins/` — site plugins
- WordPress core (`wp-admin/`, `wp-includes/`, root `wp-*.php`) is included for completeness

## Configuration and secrets

`wp-config.php` is **not** committed — it holds database credentials and auth
salts. Inside the image it is replaced by `docker/wp-config-docker.php`, which
reads everything from the `WORDPRESS_*` environment variables supplied via
`.env` on the server. `.env` is gitignored; commit `.env.example` instead.

## Docker & deployment

The `Dockerfile` builds `ramees95/amber_web:latest` from the full WordPress tree.
CI (`.github/workflows/main.yml`) builds and pushes the image on `main`/`v-*`,
then deploys over SSH to `~/amber-web` on the server, where it runs behind the
shared Traefik reverse proxy on `matamix_network` and connects to the shared
MySQL host. Server config lives in the deploy directory's `docker-compose.yml`
and `.env` (see `.env.example` for the variables).
