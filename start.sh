#!/bin/bash
set -e

# Cache config Laravel untuk performa (aman dijalankan tiap start)
php artisan config:cache
php artisan route:cache

# Jalankan migration otomatis setiap deploy baru
# --force wajib karena kita di environment production (non-interactive)
php artisan migrate --force

# Jalankan server, dengar di $PORT yang dikasih Render (default 8080 kalau lokal)
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
