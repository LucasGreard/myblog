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

$db = Dbconnect::dbConnect(); //Initiates Connexion
$postManager = new PostManager(); //Initiates PostManager
$commentManager = new CommentManager(); //Initiates CommentManager
$userManager = new UserManager(); //Initiates UserManager
$homeManager = new HomeManager(); //Initiates HomeManager
$contactManager = new ContactManager(); //Initiates ContactManager 

try {
    if (!isset($_GET['action'])) {
        $_GET['action'] = "";
    }
    switch ($_GET['action']):

            // START : USERS
        case "userConnect":
            userConnect($userManager); //Display Connexion_Page
            break;
        case 'userLogOn':
            userLogOn($userManager); //Display Coordinate_User_Page
            break;

        case 'viewUserSignUp':
            viewUserSignUp($userManager); // Display Sign_Up_Page
            break;

        case 'userSignUp':
            userSignUp($userManager); // Valide Sign_Up
            break;

        case 'deleteSession':
            userLogOut($userManager); // Delete User
            break;

        case 'userComments':
            userComments($commentManager); // Display Comment_User_Page
            break;

        case 'addUserComment':
            addUserComment($commentManager, $postManager); // Add a comment by user
            break;

        case 'modifyCoorUser':
            modifyCoorUser($userManager); // Edit Coodinate User
            break;
            //END : USERS

            // START : Display Post(s) and Comment(s)
        case "listPosts":
            listPosts($postManager); // Display all Posts
            break;

        case 'listPost':
            listPost($commentManager, $postManager); // Display a Post with comment(s)
            break;
            // END : Display Post(s) and Comment(s)



            // START : CONTACT
        case 'contactMe':
            contactMe($contactManager); // Display Contact_Page
            break;

        case 'messageSend':
            messageSend($contactManager); // Send message
            break;
            //END : CONTACT


            //START : ADMIN
        case 'listCommentValidation':
            listCommentValidation($commentManager); // Display Comment_Validate_Page 
            break;

        case 'validAndDeleteCommentUser':
            valideAndDeleteCommentUser($commentManager); // Valid or delete Comment User by an Admin
            break;

        case 'deleteUserComment':
            deleteUserComment($commentManager); // Delete Comment by an Admin
            break;

        case 'listUserManage':
            listUserManage($userManager); // Display Manage_User_Page
            break;

        case 'ManageUser':
            ManageUser($userManager); // Delete an User
            break;

        case 'managePostAdmin': // Manage an User
            managePostAdmin($postManager);
            break;

        case 'modifyPostAdmin': // Edit Post by an Admin
            modifyPostAdmin($postManager);
            break;

        case 'addPostAdmin': // Add Post by an Admin
            addAdminPost($postManager);
            break;
            //END ADMIN

        default:
            displayHome($homeManager); // Display Home_Page
            break;

    endswitch;
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
Dbconnect::dbCloseConnection($db); // Delete connection
