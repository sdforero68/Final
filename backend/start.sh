#!/bin/bash
cd /var/www/html
PORT=${PORT:-10000}
exec php -S 0.0.0.0:$PORT -t api

