FROM php:8.3-apache

# Install required packages including nano
RUN apt-get update && apt-get install -y \
  git \
  zip \
  unzip \
  libpng-dev \
  libzip-dev \
  default-mysql-client \
  nano  # Added nano installation

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the custom apache.conf file into the container
COPY config/apache/apache.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www

# Copy application files into the container
COPY . /var/www

# Set the correct permissions for the 'var' directory (log and cache)
RUN chown -R www-data:www-data /var/www/var && \
    chmod -R 775 /var/www/var

# Install Composer (from the official Composer image)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Composer dependencies
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-scripts --no-autoloader

# Expose port 80
EXPOSE 80

# Update Apache configuration to point to the public directory
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Set the default command to run Apache in the foreground
CMD ["apache2-foreground"]
