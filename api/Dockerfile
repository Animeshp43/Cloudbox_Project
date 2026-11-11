
# Use PHP-FPM with Alpine Linux
FROM php:8.3-fpm-alpine

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Create a shared group with File Browser's GID 1000
RUN addgroup -g 1000 cloudbox-share \
    && addgroup www-data cloudbox-share

# Set the working directory
WORKDIR /var/www/html

# Copy our backend application
COPY . /var/www/html/

# Expose PHP-FPM port
EXPOSE 9000

