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

création d'un trait CreataedAtTrait pour éviter la redondance de code
du coup on importe le use CreatedAtTrait en haut des classes User Categorie Commande et Produit