<?php
session_start();

use Models\CommentManager;
use Models\ContactManager;
use Models\Dbconnect;
use Models\HomeManager;
use Models\PostManager;
use Models\UserManager;



require __DIR__ . "/vendor/autoload.php";
require('controllers/frontend.php');
$db = Dbconnect::dbConnect(); //Initialise la connexion
$postManager = new PostManager(); //Initialise le PostManager
$commentManager = new CommentManager(); //Initialise le CommentManager
$userManager = new UserManager(); //Initialise le UserManager
$homeManager = new HomeManager(); //Initialise le HomeManager
$contactManager = new ContactManager(); //Initialise le contactManager 

try {
    if (!isset($_GET['action'])) {
        $_GET['action'] = "";
    }
    switch ($_GET['action']):
        case "userConnect":
            userConnect($userManager); //Va vers la page de connexion
            break;

        case "listPost":
            listPost($postManager); //affiche les posts
            break;

        case 'listComment':
            listComment($commentManager); // Affiche les commentaires liés au post choisi
            break;

        case 'userLogOn':
            userLogOn($userManager); //Va vers la page où l'utilisateur est connecté
            break;

        case 'viewUserSignUp':
            viewUserSignUp($userManager); //Va vers la page d'inscription
            break;

        case 'userSignUp':
            userSignUp($userManager); //Inscrit un nouvel utilisateur
            break;

        case 'deleteSession':
            userLogOut($userManager); //Déconnecte un utilisateur
            break;

            // case 'listUserPosts':  // A ajouter si les USERS peuvent ajouter un post
            //     listUserPosts($postManager); //Affiche les posts d'un utilisateur
            //     break;

        case 'addUserPost':
            addUserPost($postManager); //Ajoute d'un post par l'utilisateur
            break;

        case 'deleteUserPost':
            deleteUserPost($postManager); //Suppression d'un post pour un utilisateur
            break;

        case 'modifyUserPost':
            modifyUserPost($postManager); //Modification d'un post par un utilisateur
            break;

        case 'listPostValidation':
            listPostValidation($postManager); //Liste les posts à valider
            break;
        case 'validUserPost':
            valideUserPost($postManager); //Valide un post
            break;

        case 'addUserComment':
            addUserComment($commentManager); //Ajoute un commentaire
            break;

        case 'modifyCoorUser':
            modifyCoorUser($userManager); //Modifie les coordonnées d'un utilisateur
            break;

        case 'listCommentValidation':
            listCommentValidation($commentManager); //Liste les commentaires en attente de validation
            break;

        case 'validCommentUser':
            valideCommentUser($commentManager); //Valide un commentaire
            break;

        case 'deleteUserComment':
            deleteUserComment($commentManager); //Supprime un commentaire non valide
            break;

        case 'listUserManage':
            listUserManage($userManager); //Liste les utilisateurs 
            break;

        case 'deleteUser':
            deleteUser($userManager); //Supprime un utilisateur
            break;

        case 'contactMe':
            contactMe($contactManager); //Affiche la page de contact
            break;

        case 'messageSend':
            messageSend($contactManager); //Envoi un message à l'admin
            break;
        case 'userComments':
            userComments($commentManager); //Affiche une page avec tous les commentaires d'un utilisateur
        default:
            displayHome($homeManager); //Affiche l'accueil
    endswitch;
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
Dbconnect::dbCloseConnection($db); //Supprime la connexion
