php bin\console doctrine:database:drop --force
del var\cache /q
php bin\console doctrine:database:create
php bin\console doctrine:schema:create
