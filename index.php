<?php
session_start();

use Models\CommentManager;
use Models\ContactManager;
use Models\Dbconnect;
use Models\HomeManager;
use Models\PostManager;
use Models\SuperglobalManager;
use Models\UserManager;


include(dirname(__FILE__) . '/vendor/autoload.php');
include(dirname(__FILE__) . '/controllers/frontend.php');

// Initialize all managers
$db = Dbconnect::dbConnect();
$postManager = new PostManager();
$commentManager = new CommentManager();
$userManager = new UserManager();
$homeManager = new HomeManager();
$contactManager = new ContactManager();
$sessionError = new SuperglobalManager();

$actionUrl = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_URL);

if (!isset($actionUrl)) {
    $actionUrl = "";
}
switch ($actionUrl):

        // START : USERS
    case "userConnect":
        userConnect(); //Display Connexion_Page
        break;
    case 'userLogOn':
        userLogOn($userManager); //Display Coordinate_User_Page
        break;

    case 'viewUserSignUp':
        viewUserSignUp($userManager); // Display Sign_Up_Page
        break;

    case 'userSignUp':
        userSignUp($userManager, $sessionError, $homeManager); // Valide Sign_Up
        break;

    case 'deleteSession':
        userLogOut($userManager); // Delete User
        break;

    case 'userComments':
        userComments($commentManager, $sessionError = null); // Display Comment_User_Page
        break;

    case 'addUserComment':
        addUserComment($postManager, $commentManager); // Add a comment by user
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
        $post_Id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        listPost($postManager, $commentManager, $post_Id); // Display a Post with comment(s)
        break;
        // END : Display Post(s) and Comment(s)



        // START : CONTACT
    case 'contactMe':
        contactMe($contactManager); // Display Contact_Page
        break;

    case 'messageSend':
        messageSend($contactManager, $sessionError); // Send message
        break;
        //END : CONTACT


        //START : ADMIN
    case 'listCommentValidation':
        listCommentValidation($commentManager); // Display Comment_Validate_Page 
        break;

    case 'validAndDeleteCommentUser':
        valideAndDeleteCommentUser($postManager, $commentManager, $sessionError); // Valid or delete Comment User by an Admin
        break;

    case 'listUserManage':
        listUserManage($userManager); // Display Manage_User_Page
        break;

    case 'ManageUser':
        ManageUser($userManager, $sessionError, $homeManager); // Delete an User
        break;

    case 'managePostAdmin': // Manage an User
        managePostAdmin($postManager, $sessionError);
        break;

    case 'modifyPostAdmin': // Edit Post by an Admin
        modifyPostAdmin($postManager, $commentManager, $sessionError);
        break;

    case 'addPostAdmin': // Add Post by an Admin
        addAdminPost($postManager, $sessionError);
        break;
        //END ADMIN

    default:
        displayHome($homeManager); // Display Home_Page
        break;

endswitch;

Dbconnect::dbCloseConnection($db); // CClloosse connection
