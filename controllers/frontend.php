<?php
//REQUIRE_ONCE 
require_once 'Models/PostManager.php';
require_once 'Models/CommentManager.php';
require_once 'Models/UserManager.php';
require_once 'views/postsListView.php';
require_once 'views/userSignUpView.php';
require_once 'views/postWithCommentView.php';
require_once 'views/userLogOnView.php';
require_once 'views/commentListValidationView.php';
require_once 'views/userListManageView.php';
require_once 'views/contactMeView.php';
require_once 'views/userCommentsView.php';
require_once 'views/adminManagePostView.php';
require_once 'views/modifyPostAdminView.php';
require_once 'views/addPostView.php';

//USE Models
use Models\HomeManager;
use Models\PostManager;
use Models\CommentManager;
use Models\UserManager;
use Models\ContactManager;
use Models\SuperglobalManager; //TEST 

//START : Fonction principale
function displayHome($homeManager) //Display Home_Page
{
    _DefaultView::render($homeManager);
}
//END : Fonction principale

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les posts
function listPosts($postManager) // Display Posts_Page
{
    PostsListView::render($postManager);
}
//END : Fonction pour les posts

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les commentaires
function listPost($commentManager, $postManager) // Display Post_Page with Comments
{
    $post_Id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    PostWithCommentView::render($commentManager, $post_Id, $postManager);
}

function addUserComment($commentManager, $postManager) // Add user comment
{
    $post_Id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $sessionError = $commentManager->addUsercomment($post_Id);
    $postManager->listPosts($post_Id);
    PostWithCommentView::render($commentManager, $post_Id, $postManager, $sessionError);
}


function userComments($commentManager) // Display Comment_User_Page
{
    userCommentsView::render($commentManager);
}
//END : Fonction pour les commentaires

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les utilisateurs
function userConnect() // Display Login_Page
{
    UserLogOnView::render($sessionError = null);
}

function userLogOn($userManager) // Validates the user's connection
{
    $userManager->signOn();
    UserLogOnView::render($sessionError = null);
}

function viewUserSignUp($userManager) // Display Sign_Up_Page
{
    UserSignUpView::render($userManager);
}

function userSignUp($userManager) // Validates user's sign up
{
    $userManager->UserSignUp($userManager);
    UserSignUpView::render($userManager);
}

function userLogOut($userManager) // Disconnect user
{
    $userManager->UserLogOut();
    _DefaultView::render($userManager);
}

function modifyCoorUser($userManager) // Edit user details
{
    $sessionError = $userManager->modifyCoorUser();
    UserLogOnView::render($sessionError);
}

//END : Fonction pour les utilisateurs

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Me contacter
function contactMe($contactManager) // Display Contact_Page
{
    ContactMeView::render($contactManager);
}

function messageSend($contactManager) // Send message (Contact_Page)
{
    ContactMeView::render($contactManager);
}
//END : Me contacter

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Start : Fonction pour Admin
function managePostAdmin($postManager) // Delete - Edit - Add a Post by an Admin
{
    $deleteAdminPost = filter_input(INPUT_POST, 'deleteAdminPost', FILTER_SANITIZE_NUMBER_INT);
    $addPostAdmin = filter_input(INPUT_POST, 'addPostAdmin', FILTER_SANITIZE_NUMBER_INT);
    $modifyAdminPost = filter_input(INPUT_POST, 'modifyAdminPost', FILTER_SANITIZE_NUMBER_INT);
    if (isset($deleteAdminPost)) :
        $sessionError = $postManager->deleteAdminPost();
        unset($deleteAdminPost);
        ManagePostAdminView::render($postManager, $sessionError);

    elseif (isset($addPostAdmin)) :
        AddPostAdminView::render($postManager);
        unset($addPostAdmin);

    elseif (isset($modifyAdminPost)) :
        ModifyPostAdminView::render($postManager);
        unset($modifyAdminPost);

    else :
        ManagePostAdminView::render($postManager, $sessionError = null);
    endif;
}

function modifyPostAdmin($postManager, $commentManager) // Validate modify Post by an Admin
{
    $post_Id = filter_input(INPUT_POST, 'idPostAdmin', FILTER_SANITIZE_NUMBER_INT);
    $userState = $_SESSION['userState'];
    if ($userState === "Admin") :
        $sessionError = $postManager->modifyUserPost();
    endif;
    var_dump($sessionError);
    PostWithCommentView::render($post_Id, $sessionError, $postManager, $commentManager);
}

function addAdminPost($postManager) // Add a Post by an Admin
{
    $sessionError = $postManager->addAdminPost();
    ManagePostAdminView::render($postManager, $sessionError);
}

function listCommentValidation($commentManager) // Display comment to validate by Admin
{
    CommentListValidationView::render($commentManager);
}

function valideAndDeleteCommentUser($commentManager) // Validate or delete comment by admin
{
    $validCommentUser = filter_input(INPUT_POST, 'validCommentUser', FILTER_SANITIZE_NUMBER_INT);
    $deleteCommentUser = filter_input(INPUT_POST, 'deleteCommentUser', FILTER_SANITIZE_NUMBER_INT);

    $namePage = filter_input(INPUT_POST, 'namePage', FILTER_SANITIZE_STRING);

    if (isset($validCommentUser)) :
        $commentManager->valideCommentUser();
    elseif (isset($deleteCommentUser)) :
        $commentManager->deleteUserComment();
        $sessionError = new SuperglobalManager();
        $sessionError->sessionError(3);

    endif;
    switch ($namePage):
        case 'myComments':
            userCommentsView::render($commentManager);
            break;
        case 'manageComments':
            CommentListValidationView::render($commentManager);
            break;
        case 'manageCommentsDirectlyOnPost':
            $post_Id = filter_input(INPUT_POST, 'idPostAdmin', FILTER_SANITIZE_NUMBER_INT);
            $postManager = new PostManager();
            PostWithCommentView::render($commentManager, $post_Id, $postManager, $sessionError);
            break;
    endswitch;
}

function listUserManage($userManager) // Display User_Manage_Page
{
    UserListManageView::render($userManager);
}

function ManageUser($userManager) // Delete or Accept Guest_User
{
    $deleteUser = filter_input(INPUT_POST, 'deleteUser', FILTER_SANITIZE_NUMBER_INT);
    $acceptUser = filter_input(INPUT_POST, 'acceptUser', FILTER_SANITIZE_NUMBER_INT);
    if (isset($deleteUser)) :
        $userManager->deleteUser();
    elseif (isset($acceptUser)) :
        $userManager->acceptUser();
    endif;
    UserListManageView::render($userManager);
}
//END : Fonction pour Admin

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////