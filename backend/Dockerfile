# Use PHP with Apache
FROM php:8.2-apache

# Install PDO MySQL extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set ServerName to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set working directory inside container
WORKDIR /var/www/html

# Copy backend source code into container
COPY src/ /var/www/html/

# Expose port 80 inside container
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
