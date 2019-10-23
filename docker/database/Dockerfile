# Pull image from Docker Hub.
FROM mariadb:latest

# Update and update the system | Install the locales package.
RUN apt-get update && apt-get upgrade -y \
 && apt-get install locales

# Replace in the file '/etc/local.gen' the locale settings | Reconfigure the new locale settings in background | Update the local variable LANG.
RUN sed -i 's/# de_DE.UTF-8 UTF-8/de_DE.UTF-8 UTF-8/' /etc/locale.gen \
 && dpkg-reconfigure --frontend=noninteractive locales \
 && update-locale LANG=de_DE.UTF-8

# Set the environment variable LANG on the new locale setting.
ENV LANG de_DE.UTF-8

