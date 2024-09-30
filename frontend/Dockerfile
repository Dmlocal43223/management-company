FROM yiisoftware/yii2-php:8.1-apache

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/frontend/web|g' /etc/apache2/sites-available/000-default.conf
RUN useradd -m -s /bin/bash admin \
    && echo "admin:admin" | chpasswd \
    && usermod -aG sudo admin
USER admin