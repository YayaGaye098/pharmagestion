#!/bin/sh
set -e

echo "==> PharmaGestion - Démarrage du conteneur App..."

# Attendre que MySQL soit prêt
echo "==> En attente de la base de données MySQL ($DB_HOST)..."
until php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); echo 'connected'; exit(0); } catch (\Exception \$e) { exit(1); }"; do
    echo "  > La base de données n'est pas encore prête, nouvelle tentative dans 2 secondes..."
    sleep 2
done
echo "==> Connexion MySQL réussie !"

# Générer la clé d'application si non définie
if [ -z "$APP_KEY" ]; then
    echo "==> Génération de la clé d'application APP_KEY..."
    php artisan key:generate --force
fi

# Exécution des migrations de base de données
echo "==> Exécution des migrations..."
php artisan migrate --force

# Vérification du lien symbolique du stockage
if [ ! -e /var/www/html/public/storage ]; then
    echo "==> Création du lien symbolique de stockage public/storage..."
    php artisan storage:link --force || true
fi

# Nettoyage des caches Laravel
echo "==> Nettoyage des caches Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ajuster les permissions au cas où
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> PharmaGestion est prêt ! Démarrage de PHP-FPM..."

exec "$@"
