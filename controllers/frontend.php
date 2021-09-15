<?php
//REQUIRE_ONCE 
require_once 'Models/PostManager.php';
require_once 'Models/CommentManager.php';
require_once 'Models/UserManager.php';
require_once 'views/postListView.php';
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
// use Models\SuperglobalManager; //TEST 

//START : Fonction principale
function displayHome(HomeManager $homeManager) //Display Home_Page
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
function listPost(CommentManager $commentManager, PostManager $postManager) // Display Post_Page with Comments
{
    $post_Id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    PostListView::render($commentManager, $post_Id, $postManager);
}

function addUserComment(CommentManager $commentManager, PostManager $postManager) // Add user comment
{
    $post_Id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $commentManager->addUsercomment($post_Id);
    $postManager->listPosts($post_Id);
    PostListView::render($commentManager, $post_Id, $postManager);
}


function userComments(CommentManager $commentManager) // Display Comment_User_Page
{
    userCommentsView::render($commentManager);
}
//END : Fonction pour les commentaires

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les utilisateurs
function userConnect(UserManager $userManager) // Display Login_Page
{
    UserLogOnView::render($userManager);
}

function userLogOn(UserManager $userManager) // Validates the user's connection
{
    $userManager->signOn();
    UserLogOnView::render($userManager);
}

function viewUserSignUp(UserManager $userManager) // Display Sign_Up_Page
{
    UserSignUpView::render($userManager);
}

function userSignUp(UserManager $userManager) // Validates user's sign up
{
    $userManager->UserSignUp($userManager);
    UserSignUpView::render($userManager);
}

function userLogOut(UserManager $userManager) // Disconnect user
{
    $userManager->UserLogOut();
    _DefaultView::render($userManager);
}

function modifyCoorUser(UserManager $userManager) // Edit user details
{
    $userManager->modifyCoorUser($userManager);
    UserLogOnView::render($userManager);
}

//END : Fonction pour les utilisateurs

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Me contacter
function contactMe(ContactManager $contactManager) // Display Contact_Page
{
    ContactMeView::render($contactManager);
}

function messageSend(ContactManager $contactManager) // Send message (Contact_Page)
{
    ContactMeView::render($contactManager);
}
//END : Me contacter

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Start : Fonction pour Admin
function managePostAdmin(PostManager $postManager) // Delete - Edit - Add a Post by an Admin
{
    $deleteAdminPost = filter_input(INPUT_POST, 'deleteAdminPost', FILTER_SANITIZE_NUMBER_INT);
    $addPostAdmin = filter_input(INPUT_POST, 'addPostAdmin', FILTER_SANITIZE_NUMBER_INT);
    $modifyAdminPost = filter_input(INPUT_POST, 'modifyAdminPost', FILTER_SANITIZE_NUMBER_INT);
    if (isset($deleteAdminPost)) :
        $postManager->deleteAdminPost();
        unset($deleteAdminPost);
        ManagePostAdminView::render($postManager);

    elseif (isset($addPostAdmin)) :
        AddPostAdminView::render($postManager);
        unset($addPostAdmin);

    elseif (isset($modifyAdminPost)) :
        ModifyPostAdminView::render($postManager);
        unset($modifyAdminPost);

    else :
        ManagePostAdminView::render($postManager);
    endif;
}

function modifyPostAdmin(PostManager $postManager) // Validate modify Post by an Admin
{
    $post_Id = filter_input(INPUT_POST, 'idPostAdmin', FILTER_SANITIZE_NUMBER_INT);
    $userState = $_SESSION['userState'];
    if ($userState === "Admin") :
        $postManager->modifyUserPost();
    endif;
    $commentManager = new CommentManager();
    PostListView::render($commentManager, $post_Id, $postManager);
}

function addAdminPost(PostManager $postManager) // Add a Post by an Admin
{
    $postManager->addAdminPost();
    UserListPostsView::render($postManager);
}

function listCommentValidation(CommentManager $commentManager) // Display comment to validate by Admin
{
    CommentListValidationView::render($commentManager);
}

function valideAndDeleteCommentUser(CommentManager $commentManager) // Validate or delete comment by admin
{
    $validCommentUser = filter_input(INPUT_POST, 'validCommentUser', FILTER_SANITIZE_NUMBER_INT);
    $deleteCommentUser = filter_input(INPUT_POST, 'deleteCommentUser', FILTER_SANITIZE_NUMBER_INT);
    if (isset($validCommentUser)) :
        $commentManager->valideCommentUser();
    elseif (isset($deleteCommentUser)) :
        $commentManager->deleteUserComment();
    endif;
    CommentListValidationView::render($commentManager);
}

function deleteUserComment(CommentManager $commentManager) // Delete comment by admin
{
    $commentManager->deleteUserComment();
    CommentListValidationView::render($commentManager);
}

function listUserManage(UserManager $userManager) // Display User_Manage_Page
{
    UserListManageView::render($userManager);
}

function ManageUser(UserManager $userManager) // Delete or Accept Guest_User
{
    $deleteUser = filter_input(INPUT_POST, 'deleteUser', FILTER_SANITIZE_NUMBER_INT);
    $acceptUser = filter_input(INPUT_POST, 'acceptUser', FILTER_SANITIZE_NUMBER_INT);
    if (isset($deleteUser)) :
        $userManager->deleteUser();
        UserListManageView::render($userManager);
    elseif (isset($acceptUser)) :
        $userManager->acceptUser();
        UserListManageView::render($userManager);
    endif;
}
//END : Fonction pour Admin

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////