# Use PHP 7.4 official image with Apache
FROM php:7.4-apache

# Install system dependencies including unzip for Composer
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zlib1g-dev \
    unzip && \
    rm -rf /var/lib/apt/lists/*  # Clean up unnecessary files

# Configure and install PHP extensions required for Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Enable necessary Apache modules
RUN a2enmod rewrite headers

# Set ServerName to suppress Apache warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy the application source to the container
COPY . /var/www/html

# Set the working directory to the root of the web server
WORKDIR /var/www/html

# Install Composer globally in the image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Run Composer to install PHP dependencies
RUN composer install --no-interaction

# Ensure files/folders are owned by the web server user
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 to access Apache
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
