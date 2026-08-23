#!/bin/sh
set -eu

php artisan config:clear --env=playwright
exec php artisan serve --env=playwright --host=127.0.0.1 --port=8010
