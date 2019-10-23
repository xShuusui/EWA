# Pull image from Docker Hub.
FROM php:7.3.10-apache

# Update and upgrade the system.
RUN apt-get update && apt-get upgrade -y

# Install mysqli in the image.
RUN docker-php-ext-install mysqli
