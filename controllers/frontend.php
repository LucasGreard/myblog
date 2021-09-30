<?php
//INCLUDE FILES 
use Models\PostManager;
use Models\CommentManager;

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

/**
 * Display Home_Page
 * @param object $homeManager HomeManager
 * @return void
 */
function displayHome($homeManager) 
{
    _DefaultView::render($homeManager);
}

//END : Fonction principale

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les posts

/**
 * Display Posts_Page
 * @param object $postManager PostManager
 * @return void
 */
function listPosts(PostManager $postManager) 
{
    PostsListView::render($postManager);
}

//END : Fonction pour les posts

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les commentaires

/**
 * Display Post_Page with Comments
 * @param object $postManager PostManager
 * @param object $commentManager CommentManager
 * @param int $post_Id
 * @return void
 */
function listPost(PostManager $postManager, CommentManager $commentManager, int $post_Id) 
{
    PostWithCommentView::render($postManager, $commentManager, $post_Id);
}

/**
 * Add user comment
 * @param object $postManager PostManager
 * @param object $commentManager CommentManager
 * @return void
 */
function addUserComment($postManager, $commentManager)
{
    $post_Id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $sessionError = $commentManager->addUsercomment($post_Id);
    $postManager->listUniquePost($post_Id);
    PostWithCommentView::render($postManager, $commentManager, $post_Id, $sessionError);
}

/**
 * Display Comment_User_Page
 * @param object $commentManager CommentManager
 * @return void
 */
function userComments($commentManager, $sessionError = null)
{
    userCommentsView::render($commentManager, $sessionError = null);
}

//END : Fonction pour les commentaires

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les utilisateurs

/**
 * Display Login_Page
 * @return void
 */
function userConnect()
{
    UserLogOnView::render();
}

/**
 * Validates the user's connection
 * @param object $userManager UserManager
 * @return void
 */
function userLogOn($userManager) 
{
    $userManager->signOn();
    UserLogOnView::render();
}

/**
 * Display Sign_Up_Page
 * @param object $userManager UserManager
 * @return void
 */
function viewUserSignUp($userManager) 
{
    UserSignUpView::render($userManager);
}

/**
 * Validates user's sign up
 * @param object $userManager UserManager
 * @param object $sessionError SuperGlobalManager
 * @param object $homeManager HomeManager
 * @return void
 */
function userSignUp($userManager, $sessionError, $homeManager)
{
    $sessionError = $userManager->UserSignUp($homeManager, $sessionError);
    UserSignUpView::render($userManager, $sessionError);
}

/**
 * Disconnect user
 * @param object $userManager UserManager
 * @return void
 */
function userLogOut($userManager) 
{
    $userManager->UserLogOut();
    _DefaultView::render($userManager);
}

/**
 * Edit user details
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

/**
 * Display Contact_Page
 * @param object $contactManager ContactManager
 * @return void
 */
function contactMe($contactManager)
{
    ContactMeView::render($contactManager);
}

/**
 * Send message (Contact_Page)
 * @param object $contactManager ContactManager
 * @param object $sessionError SuperGlobalManager
 * @return void
 */
function messageSend($contactManager, $sessionError)
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

/**
 * Delete - Edit - Add a Post by an Admin
 * @param object $postManager PostManager
 * @param object $sessionError SuperGlobalManager
 * @return void
 */
function managePostAdmin($postManager, $sessionError)
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

/**
 * Validate modify Post by an Admin
 * @param object $postManager PostManager
 * @param object $commentManager CommentManager
 * @param object $sessionError SuperGlobalManager
 * @return void
 */
function modifyPostAdmin($postManager, $commentManager, $sessionError)
{
    $post_Id = filter_input(INPUT_POST, 'idPostAdmin', FILTER_SANITIZE_NUMBER_INT);
    $userState = SuperglobalManager::getSession('userState');
    if ($userState === "Admin")
        $sessionError = $postManager->modifyUserPost($sessionError);

    PostWithCommentView::render($postManager, $commentManager, $post_Id,  $sessionError);
}

/**
 * Add a Post by an Admin
 * @param object $postManager PostManager
 * @param object $sessionError SuperGlobalManager
 * @return void
 */
function addAdminPost($postManager, $sessionError) 
{
    $sessionError = $postManager->addAdminPost($sessionError);
    ManagePostAdminView::render($postManager, $sessionError);
}

/**
 * Display comment to validate by Admin
 * @param object $commentManager CommentManager
 * @return void
 */
function listCommentValidation($commentManager)
{
    CommentListValidationView::render($commentManager);
}

/**
 * Validate or delete comment by admin
 * @param object $postManager PostManager
 * @param object $commentManager CommentManager
 * @param object $sessionError SuperGlobalManager
 * @return void
 */
function valideAndDeleteCommentUser($postManager, $commentManager, $sessionError) // 
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

/**
 * Display User_Manage_Page
 * @param object $userManager UserManager
 * @return void
 */
function listUserManage($userManager) 
{
    UserListManageView::render($userManager);
}

/**
 * Delete or Accept Guest_User
 * @param object $userManager UserManager
 * @param object $homeManager HomeManager
 * @param object $sessionError SuperGlobalManager
 * @return void
 */
function ManageUser($userManager, $sessionError, $homeManager)
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