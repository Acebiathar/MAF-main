#!/bin/bash

# Run migrations automatically on deploy
php artisan migrate --force

# Start the server
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}