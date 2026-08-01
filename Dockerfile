FROM php:8.2-apache

# Installer les extensions PHP indispensables pour votre ERP (PDO MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Activer le module Apache mod_rewrite (essentiel pour vos routes MVC)
RUN a2enmod rewrite

# Copier tous les fichiers du projet dans le conteneur du serveur web
COPY . /var/www/html/

# Rediriger la racine du serveur web Apache vers votre dossier "public"
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Autoriser l'utilisation de .htaccess si vous en avez un
RUN sed -i '/<Directory \/var\/www\/html>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Exposer le port 80 pour le web
EXPOSE 80
