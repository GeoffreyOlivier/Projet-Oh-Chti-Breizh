# DOCUMENTATION


# Objectif:

  Développer une application contenant un catalogue de produits et une gestion de panier basique.

- L’application propose 12 produits au total.
- Les produits contiennent les champs suivants : id, nom, description, prix.
- Les produits seront stockés en base de donnée.
- Le panier sera stocké en session. 

### Tech

  * Projet Symfony 4.2 
  * PHP >= 7.1 
  * SQLite  

### Installation

Si vous voulez utiliser un conteneur il faudra installer Docker (Community Edition).

```sh
cd projetRiadhTalbi\docker-install-symfony
docker-compose up -d
```
Avec la commande vous aurez les identifiants du conteneur

```sh
docker ps
```

Vous pouvez installer le nouveau framework Symfony avec la commande

```sh
docker exec id_du_conteneur composer create-project symfony/website-skeleton catalogue
```
La methode decrite si-dessus vous donne tout ce qui est necessaire pour faire tourner votre application web sinon proceder comme suit :

Pour créer application catalogue, assurez-vous d’abord que vous utilisez PHP 7.1 ou supérieur et que [composer](https://getcomposer.org/download/) est installé. Sinon, commencez par installer [Composer globalement](https://symfony.com/doc/current/setup/composer.html) sur votre système.
Créez votre nouveau projet en exécutant:

```sh
$ composer create-project symfony/website-skeleton catalogue
```
Lancer votre application Symfony

```sh
$ cd catalogue
$ php bin/console server:run
```

### Configurer la base de données

Les informations de connexion à la base de données sont stockées sous forme de variable d’environnement appelée  DATABASE_URL. Pour le développement, vous pouvez trouver et personnaliser cet intérieur .env:

```sh
# .env (or override DATABASE_URL in .env.local to avoid committing your changes)
# customize this line! to use sqlite:
DATABASE_URL="sqlite:///%kernel.project_dir%/var/catalogue.db"
```
Maintenant que vos paramètres de connexion sont configurés, Doctrine peut créer la db_name base de données pour vous:
```sh
$ php bin/console doctrine:database:create
```
Créer une classe d'entité Product
Vous pouvez utiliser la make:entity commande pour créer cette classe et tous les champs dont vous avez besoin. La commande vous posera quelques questions - répondez comme ci-dessous:
```sh
$ php bin/console make:entity
Class name of the entity to create or update:
> Product
 to stop adding fields):
> nom
Field type (enter ? to see all types) [string]:
> string
Field length [255]:
> 255
Can this field be null in the database (nullable) (yes/no) [no]:
> no
 to stop adding fields):
> prix
Field type (enter ? to see all types) [string]:
> float
Can this field be null in the database (nullable) (yes/no) [no]:
> no
 to stop adding fields):
> description
Field type (enter ? to see all types) [string]:
> text
(press enter again to finish)
```
### Migrations: Création des tables / schémas de la base de données 
La Productclasse est entièrement configurée et prête à être enregistrée dans une producttable. Si vous venez de définir cette classe, votre base de données ne contient pas encore la product table. Pour l'ajouter, vous pouvez utiliser DoctrineMigrationsBundle , qui est déjà installé:
```sh
$ php bin/console make:migration
```
Consultez la nouvelle migration "src / Migrations / Version20180207231217.php" Puis: exécutez la migration avec php bin / console doctrine: migrations: migrate
```sh
$ php bin/console doctrine:migrations:migrate
```
### Dummy Data Fixtures
Doctrine fournit une bibliothèque qui vous permet de charger par programme des données de test dans votre projet (c'est-à-dire des "données de fixture"). Installez-le avec:
```sh
composer require doctrine/doctrine-fixtures-bundle --dev
```
Ensuite, utilisez la make:fixturescommande pour générer une classe de fixture vide:
```sh
php bin/console make:fixtures
The class name of the fixtures to create (e.g. AppFixtures):
> ProductFixture
```
Personnalisez la nouvelle classe pour charger des Productobjets dans Doctrine:
```sh
// src/DataFixtures/ProductFixture.php
namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1 ; $i <= 12; $i++) {
            $alphabet="abcdefghijklmnopqrstuvwxyz";
            $lettre_aleatoire=$alphabet[rand(0,25)];
            $product = new Product();
            $product->setNom($lettre_aleatoire.' product '.$i);
            $product->setDescription('Description du Produit '.$i);
            $product->setPrix(mt_rand(10, 100));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
```
Videz la base de données et rechargez toutes les classes de fixture avec:
```sh
php bin/console doctrine:fixtures:load
```
### compiler les assets 
Installer Encore dans votre projet
```sh
$ composer require encore
$ yarn install
 ```
 utiliser Bootstrap  dans votre projet
 ```sh
$ yarn add bootstrap@4.0.0-beta.3
 ```
Pour utiliser scss et fontawesome  dans votre projet proceder comme suit:
 ```sh
$ sass-loader@^7.0.1 node-sass --dev
$ yarn add --dev @fortawesome/fontawesome-free
 ```
 creer un nouveau dossier scss puis un fichien app.scss sous assets et le remplir comme suit:
 Modifier le fichier webpack.config.js fichier à la racine de votre projet comme suit:
 ```sh
@import "../../node_modules/bootstrap/scss/bootstrap.scss";
@import '../../node_modules/@fortawesome/fontawesome-free/css/all.css';
 ```
```sh
 var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    // ....
    .addEntry('app', './assets/scss/app.scss')

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()

    // .....
;

module.exports = Encore.getWebpackConfig();
 ```
 Pour construire les actifs, exécutez:
```sh
$ yarn encore dev --watch
```
dans votre fichier de mise en page de base. faire ce-ci:
```sh
{% block stylesheets %}
<link rel="stylesheet" href="{{ asset('build/app.css') }}" >
{% endblock %}
{% block javascripts %}
<script src="{{ asset('build/app.js') }}"></script>
{% endblock %}
```
### Developpement
Maintenant que vous avez une application Symfony entièrement fonctionnelle, vous pouvez commencer le développem.
Pour voir le resultat:
```sh
$ php bin/console server:run
#Tapez le lien ci-dessous sur votre navigateur web
http://localhost:8000
```
## Tester votre application

Pour commencer, il nous faut établir la liste des URL de l'application afin de connaître la somme de travail que nous avons devant nous.
Je vous invite donc à vous placer à la racine du projet fraîchement installé, puis à taper la commande suivante dans votre outil de lignes de commande pour lister toutes les routes de l'application :
```sh
$ php bin/console debug:router
```
Nous n'allons pas tester toutes les routes de l'application. Nous nous concentrerons uniquement sur les routes autres que celles provenant du framework Symfony (toutes celles qui ne commencent pas par _).
Ensuite pour les tests, il faut le composant PHPUnit :
```sh
$ composer req symfony/phpunit-bridge
```
Et pour les tests fonctionnels on va avoir besoin de :
```sh
$ composer require --dev symfony/browser-kit symfony/css-selector
```
Et comme on est feignant on va faire appelle au maker pour nous créer nos classes :
```sh
$ php bin/console make:unit-test
$ php bin/console make:functional-test
```
On va maintenant faire un peu de config, dans le fichier config/packages/test/framework.yaml, nous allons lui dire que nous allons utiliser les sessions (juste décommenter les 2 dernières lignes) :
```sh
framework:
    test: ~
    # Uncomment this section if you're using sessions
    session:
        storage_id: session.storage.mock_file
 ```
Et surtout ajouter cette ligne dans le fichier phpunit.xml.dist, c’est sur cette base de données que va se connecter phpunit pour effectuer qui ont besoin de votre base :
```sh
<!-- define your env variables for the test env here -->
<env name="DATABASE_URL" value="mysqlite:///%kernel.project_dir%/catalogue" />
 ```
 Vous pouvez déjà exécuter les tests par défaut en faisant simplement :
```sh
$ php bin/phpunit
  ```

