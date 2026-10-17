#!/bin/sh
# Repair wp-content ownership at runtime, then start the main process.
#
# Build-time chown only affects the image. When the server's compose file mounts
# a volume or bind mount over wp-content/uploads, that mount masks the image's
# permissions and WordPress fails with:
#   "The uploaded file could not be moved to wp-content/uploads/..."
#
# This runs as root before Apache starts (Apache itself drops to www-data for
# workers), so it can fix ownership on whatever is actually mounted.
set -eu

WP_CONTENT=/var/www/html/wp-content

if [ "$(id -u)" = "0" ]; then
	for dir in uploads upgrade ai1wm-backups cache; do
		mkdir -p "$WP_CONTENT/$dir"
	done

	# Only chown what is not already correct — a recursive chown over a large
	# uploads volume on every boot would slow startup considerably.
	find "$WP_CONTENT" \! -user www-data -exec chown www-data:www-data '{}' + || true

	chmod 775 "$WP_CONTENT" \
		"$WP_CONTENT/uploads" \
		"$WP_CONTENT/upgrade" \
		"$WP_CONTENT/ai1wm-backups" \
		"$WP_CONTENT/cache" || true
fi

exec "$@"
