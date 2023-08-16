# Use an official PHP runtime as a base image
FROM php:7.4-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the contents of your project to the container's working directory
COPY . .

# Install any necessary PHP extensions
RUN docker-php-ext-install mysqli

# Expose port 80 to the Docker host
EXPOSE 80
