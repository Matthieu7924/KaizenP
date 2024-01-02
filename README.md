symfony new kaizenProject

symfony server:ca:install
cette commande est utilisée pour installer les certificats de développement SSL auto-signés pour le serveur Symfony local. Elle doit être exécutée une fois par projet pour générer et installer ces certificats.


mysql -u root -v
pour commander le mysql depuis le terminal

syymfony -v
Symfony CLI version 5.5.5

composer --version
Composer version 2.5.8

symfony check:requirements
cette commande verifie si symfony contient tous les modules necessaires

php -m
pour consulter la liste des modules installés


dans le fichier .env
pour ne pas tout synchroniser avec git on crée un fichier .env.local

symgony console doctrine:database:create

symfony console make:user
avec email en unique display

symfony console make:entity User
symfony console make:entity Commande
symfony console make:entity CDetailsCommande
symfony console make:entity Produit
symfony console make:entity Image

symfony console migrations:migrate
symfony console migrations:migrate


création du dossier public/assets
dans lequel on crée 2 dossiers  (css et js)
dans lesquels on importe les dossiers minifiés de bootstrap


Authentification
symfony console make:auth
on choisit  1 Login form authenticator (pour avoir direct le forumaire)
on le nomme userAuthenticator
ça génère le securityController

symfony console make:registration-form
on choisit yes pour que le user crée soit unique
on choisit no pour la vérification d'email car on implémente nous même la vérifi d'emails sans passser par symfony
yes pour l'authentification automatique
donc ceci a crée le regsitrationController

TAIT
création d'un trait CreataedAtTrait pour éviter la redondance de code
du coup on importe le use CreatedAtTrait en haut des classes User Categorie Commande et Produit

SLUG
ajout d'une propriété slug
le slug sert à avoir une url avec le nom d'un produit plutot qu'un id

DATA FIXTURES
création de datafixtures pour alimenter la bdd
    -composer require --dev orm-fixtures
    -composer require fakerphp/faker
on commence par créer des catégories sans le faker
    -symfony console make:fixtures
    -je code mon fichier de fixtures
    -symfony console doctrine:fixtures:load
on poursuit avec les users
    -symfony console make:fixtures UsersFixtures
avec les produits
    -symfony console make:fixtures ProduitsFixtures
et les images
    -symfony console make:fixtures ImagesFixtures


CONTOLEURS
symfony console make:controller ProfileController
    -cette commande executée crée le controleuret un template twig


    SANS PASSER PAR LES FIXTURES
    CREATION DE L'ADMIN
    php bin/console security:hash-password


Dans le MainController pour avoir les différentes catégories dans le index on injecte le categorie repository pour interroger la bdd et transmettre à la vue la liste des categories par ordre
chaque card bootstrap represente une categorie qui permet de renvoyer vers la liste des produits rattachés à cette catégorie 
donc on crée un nouveau controller 
CategorieController

dans partials on crée un fichier flash.html.twig
pour tous les messages d'alerte

pour la vérif des mails
il faut checker dans le fichier .env.local
pour envoyer un mail il faut d'abord activer le 
MAILER_DSN=smtp://localhost:1025
ensuite créer un dossier Service dans Src
dans lequel on crée un fichier SendMailService
on configure le mail 
ensuite dans le controleur RegistrationController on injecte le SenMailService
on crée un dossier Emails dans Templates
dans ce dossier on crée le fichier

pour tester on lance mailhog
par défaut symfony utilise messenger et pas mailhog
du coup il faut commenter le sendmailMessage dans le routing de Config/Packages/Messenger.yaml
pour rendre le lien du mail de vérification actif il faut un un json web token
dans le dossier Service on crée le fichier
