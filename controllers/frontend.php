<?php
//INCLUDE FILES 
//include_once(dirname(__FILE__) . '/../models\PostManager.php');
use Models\PostManager;
use Models\CommentManager;

//include_once(dirname(__FILE__) . '/../models\CommentManager.php');
include_once(dirname(__FILE__) . '/../models\UserManager.php');

include_once(dirname(__FILE__) . '/../views/_defaultView.php');
include_once(dirname(__FILE__) . '/../views/postsListView.php');
include_once(dirname(__FILE__) . '/../views/userSignUpView.php');
include_once(dirname(__FILE__) . '/../views/postWithCommentView.php');
include_once(dirname(__FILE__) . '/../views/userLogOnView.php');
include_once(dirname(__FILE__) . '/../views/commentListValidationView.php');
include_once(dirname(__FILE__) . '/../views/userListManageView.php');
include_once(dirname(__FILE__) . '/../views/contactMeView.php');
include_once(dirname(__FILE__) . '/../views/userCommentsView.php');
include_once(dirname(__FILE__) . '/../views/adminManagePostView.php');
include_once(dirname(__FILE__) . '/../views/modifyPostAdminView.php');
include_once(dirname(__FILE__) . '/../views/addPostView.php');

use Models\SuperglobalManager;
//START : Fonction principale
function displayHome($homeManager) //Display Home_Page
{
    _DefaultView::render($homeManager);
}
//END : Fonction principale

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les posts
function listPosts(PostManager $postManager) // Display Posts_Page
{
    PostsListView::render($postManager);
}
//END : Fonction pour les posts

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les commentaires
function listPost(PostManager $postManager, CommentManager $commentManager, int $post_Id) // Display Post_Page with Comments
{
    PostWithCommentView::render($postManager, $commentManager, $post_Id);
}

function addUserComment($postManager, $commentManager) // Add user comment
{
    $post_Id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $sessionError = $commentManager->addUsercomment($post_Id);
    $postManager->listUniquePost($post_Id);
    PostWithCommentView::render($postManager, $commentManager, $post_Id, $sessionError);
}


function userComments($commentManager, $sessionError = null) // Display Comment_User_Page
{
    userCommentsView::render($commentManager, $sessionError = null);
}
//END : Fonction pour les commentaires

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les utilisateurs
function userConnect() // Display Login_Page
{
    UserLogOnView::render();
}

function userLogOn($userManager) // Validates the user's connection
{
    $userManager->signOn();
    UserLogOnView::render();
}

function viewUserSignUp($userManager) // Display Sign_Up_Page
{
    UserSignUpView::render($userManager);
}

function userSignUp($userManager, $sessionError, $homeManager) // Validates user's sign up
{
    $sessionError = $userManager->UserSignUp($homeManager, $sessionError);
    UserSignUpView::render($userManager, $sessionError);
}

function userLogOut($userManager) // Disconnect user
{
    $userManager->UserLogOut();
    _DefaultView::render($userManager);
}

/**
 * Edit user details
 * 
 * @param object $userManager UserManager
 * @return void
 */
function modifyCoorUser($userManager)
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

function messageSend($contactManager, $sessionError) // Send message (Contact_Page)
{
    $mailUserSend = filter_input(INPUT_POST, 'mailUserSend', FILTER_SANITIZE_STRING);
    $messageUserSend = filter_input(INPUT_POST, 'messageUserSend', FILTER_SANITIZE_STRING);
    $testSendMessage = $contactManager->sendMessage($mailUserSend, $messageUserSend);

    $sessionError = $testSendMessage ?
        $sessionError->sessionError(17) : $sessionError->sessionError(18);

    ContactMeView::render($contactManager, $sessionError);
}
//END : Me contacter

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Start : Fonction pour Admin
function managePostAdmin($postManager, $sessionError) // Delete - Edit - Add a Post by an Admin
{
    $deleteAdminPost = filter_input(INPUT_POST, 'deleteAdminPost', FILTER_SANITIZE_NUMBER_INT);
    $addPostAdmin = filter_input(INPUT_POST, 'addPostAdmin', FILTER_SANITIZE_NUMBER_INT);
    $modifyAdminPost = filter_input(INPUT_POST, 'modifyAdminPost', FILTER_SANITIZE_NUMBER_INT);
    if (isset($deleteAdminPost)) :
        $sessionError = $postManager->deleteAdminPost($sessionError);
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

function modifyPostAdmin($postManager, $commentManager, $sessionError) // Validate modify Post by an Admin
{
    $post_Id = filter_input(INPUT_POST, 'idPostAdmin', FILTER_SANITIZE_NUMBER_INT);
    $userState = SuperglobalManager::getSession('userState');
    if ($userState === "Admin")
        $sessionError = $postManager->modifyUserPost($sessionError);

    PostWithCommentView::render($postManager, $commentManager, $post_Id,  $sessionError);
}

function addAdminPost($postManager, $sessionError) // Add a Post by an Admin
{
    $sessionError = $postManager->addAdminPost($sessionError);
    ManagePostAdminView::render($postManager, $sessionError);
}

function listCommentValidation($commentManager) // Display comment to validate by Admin
{
    CommentListValidationView::render($commentManager);
}

function valideAndDeleteCommentUser($postManager, $commentManager, $sessionError) // Validate or delete comment by admin
{
    $validCommentUser = filter_input(INPUT_POST, 'validCommentUser', FILTER_SANITIZE_NUMBER_INT);
    $deleteCommentUser = filter_input(INPUT_POST, 'deleteCommentUser', FILTER_SANITIZE_NUMBER_INT);

    $namePage = filter_input(INPUT_POST, 'namePage', FILTER_SANITIZE_STRING);

    if (isset($validCommentUser)) :
        $commentManager->valideCommentUser();
        $sessionError = $sessionError->sessionError(11);
    elseif (isset($deleteCommentUser)) :
        $commentManager->deleteUserComment();
        $sessionError = $sessionError->sessionError(3);
    endif;

    switch ($namePage):
        case 'myComments':
            userCommentsView::render($commentManager, $sessionError);
            break;
        case 'manageComments':
            CommentListValidationView::render($commentManager, $sessionError);
            break;
        case 'manageCommentsDirectlyOnPost':
            $post_Id = filter_input(INPUT_POST, 'idPostAdmin', FILTER_SANITIZE_NUMBER_INT);
            PostWithCommentView::render($postManager, $commentManager, $post_Id, $sessionError);
            break;
    endswitch;
}

function listUserManage($userManager) // Display User_Manage_Page
{
    UserListManageView::render($userManager);
}

function ManageUser($userManager, $sessionError, $homeManager) // Delete or Accept Guest_User
{
    $deleteUser = filter_input(INPUT_POST, 'deleteUser', FILTER_SANITIZE_NUMBER_INT);
    $acceptUser = filter_input(INPUT_POST, 'acceptUser', FILTER_SANITIZE_NUMBER_INT);
    if (isset($deleteUser)) :
        $userManager->deleteUser();
        $sessionError = $sessionError->sessionError(13);
    elseif (isset($acceptUser)) :
        $userManager->acceptUser();
        $sessionError = $sessionError->sessionError(12);
    endif;
    UserListManageView::render($userManager, $sessionError, $homeManager);
}
//END : Fonction pour Admin

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////