#!/bin/bash

# Print all environment variables for debugging
printenv

# Wait for the MySQL service to be available
until mysql -h db -u root -e "SELECT 1"; do
  >&2 echo "MySQL is unavailable - sleeping"
  sleep 1
done


# Create the database if it does not exist
mysql -h db -u root -e "CREATE DATABASE IF NOT EXISTS \`${MYSQL_DATABASE}\`;"

# Run migrations
cd /var/www/html
php bin/console doctrine:migrations:migrate --no-interaction