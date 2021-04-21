# Tournament

Le projet Tournament est réalisé par Lucas Guillotin dans le cadre du Ydays "Labo approfondissement Symfony"

Tournament est la future plateforme incontournable de gestions de tournois de football.
Ici, vous trouverez tout le nécessaire pour permettre à votre club / ligue de gérer ses tournois.

## Installation

1. Clonez ce projet dans un dossier
2. Installez Docker si ce n'est pas déjà fait
3. À l'intérieur de ce dossier, tapez ces commandes :

```shell
docker-compose build
docker-compose up -d
docker-compose exec php bash
```

4. Une fois à l'intérieur du conteneur php grâce à la commande précédente, tapez les commandes suivantes :
```shell
composer install
composer require symfony/apache-pack
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load       
```

5. À partir d'ici vous pouvez aller sur votre navigateur préféré et taper dans l'url afin de consulter tous les requêtes existantes sur le site :
```
localhost:8080
```

## Conteneur Mysql

1. Pour intéragir avec le conteneur, lancez la commande : 
```shell
docker-compose exec {nom du service mysql} bash
```
2. Pour accéder à la base de données, tapez les commandes suivantes :
```shell
mysql -u {mot de passe} -p
```
Le mot de passe sera celui indiqué dans le docker-compose.yml
```shell
use {nom de la base};
show tables;
```

C'est bon vous y êtes.
