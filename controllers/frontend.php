<?php
//REQUIRE_ONCE 
require_once('Models/PostManager.php');
require_once('Models/CommentManager.php');
require_once('Models/UserManager.php');
require_once('views/postListView.php');
require_once('views/userSignUpView.php');
require_once('views/commentListView.php');
require_once('views/userLogOnView.php');
require_once('views/commentListValidationView.php');
require_once('views/userListManageView.php');
require_once('views/contactMeView.php');
require_once('views/userCommentsView.php');
require_once('views/adminManagePostView.php');
require_once('views/modifyPostAdminView.php');
require_once('views/addPostView.php');
// require_once('views/userListPostsView.php'); A ajouter si les USERS peuvent ajouter un post
// require_once('views/userModifyPostView.php');
// require_once('views/postListValidationView.php');


//USE Models
use Models\HomeManager;
use Models\PostManager;
use Models\CommentManager;
use Models\UserManager;
use Models\ContactManager;


//START : Fonction principale

function displayHome(HomeManager $homeManager) //Accède à la page Accueil
{
    _DefaultView::render($homeManager);
}
//END : Fonction principale

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les posts

function listPost(PostManager $postManager) // Affiche les posts
{
    PostListView::render($postManager);
}

// A ajouter si les USERS peuvent ajouter un post
// function listUserPosts(PostManager $postManager) //Affichage des posts de l'utilisateur
// {
//     UserListPostsView::render($postManager);
// }

function addUserPost(PostManager $postManager) //Ajouter un post
{
    $postManager->addUserPost();
    UserListPostsView::render($postManager);
}

function deleteUserPost(PostManager $postManager)
{
    $postManager->deleteUserPost();
    UserListPostsView::render($postManager);
}

function modifyUserPost(PostManager $postManager)
{

    if (isset($_POST['postHeadingUserModify']) && isset($_POST['postContentUserModify'])) {
        $postManager->modifyUserPost();
    }
    UserModifyPostView::render($postManager);
}

function listPostValidation(PostManager $postManager)
{
    PostListValidationView::render($postManager);
}

function valideUserPost(PostManager $postManager)
{
    if (isset($_POST['validPostUser'])) {
        $postManager->valideUserPost();
    } elseif (isset($_POST['deletePostUser'])) {
        $postManager->deleteUserPost();
    }
    PostListValidationView::render($postManager);
}

//END : Fonction pour les posts

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les commentaires
function listComment(CommentManager $commentManager, PostManager $postManager) // Affiche les commentaires
{
    $post_Id = $_GET['id'];
    CommentListView::render($commentManager, $post_Id, $postManager);
}
function addUserComment(CommentManager $commentManager, PostManager $postManager)
{
    $post_Id = $_GET['id'];
    $commentManager->addUsercomment($post_Id);
    $postManager->listPost($post_Id);
    CommentListView::render($commentManager, $post_Id, $postManager);
}
function listCommentValidation(CommentManager $commentManager)
{
    CommentListValidationView::render($commentManager);
}
function valideCommentUser(CommentManager $commentManager)
{
    if (isset($_POST['validCommentUser'])) :
        $commentManager->valideCommentUser();
    elseif (isset($_POST['deleteCommentUser'])) :
        $commentManager->deleteUserComment();
    endif;
    CommentListValidationView::render($commentManager);
}
function deleteUserComment(CommentManager $commentManager)
{
    $commentManager->deleteUserComment();
    CommentListValidationView::render($commentManager);
}
function userComments(CommentManager $commentManager)
{
    userCommentsView::render($commentManager);
}
//END : Fonction pour les commentaires

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Fonction pour les utilisateurs
function userConnect(UserManager $userManager) // Accède à la page de connection
{
    UserLogOnView::render($userManager);
}

function userLogOn(UserManager $userManager) //Page de validation de connection
{
    $userManager->signOn();
    UserLogOnView::render($userManager);
}

function viewUserSignUp(UserManager $userManager) //Accès à la page d'inscription
{
    UserSignUpView::render($userManager);
}

function userSignUp(UserManager $userManager) //Page de validation d'inscription
{
    UserSignUpView::render($userManager);
    $userManager->UserSignUp($userManager);
}

function userLogOut(UserManager $userManager) //Déconnexion de l'utilisateur
{
    $userManager->UserLogOut();
    _DefaultView::render($userManager, $postManager = null, $commentManager = null);
}
function modifyCoorUser(UserManager $userManager)
{

    $userManager->modifyCoorUser($userManager);
    UserLogOnView::render($userManager);
}
function listUserManage(UserManager $userManager)
{
    UserListManageView::render($userManager);
}
function ManageUser(UserManager $userManager)
{
    if (isset($_POST['deleteUser'])) :
        $userManager->deleteUser();
        UserListManageView::render($userManager);
    elseif (isset($_POST['acceptUser'])) :
        $userManager->acceptUser();
        UserListManageView::render($userManager);
    endif;
}
//END : Fonction pour les utilisateurs

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//START : Me contacter
function contactMe(ContactManager $contactManager)
{
    ContactMeView::render($contactManager);
}
function messageSend(ContactManager $contactManager)
{

    ContactMeView::render($contactManager);
}

//END : Me contacter

//Start : Fonction pour Admin

function managePostAdmin(PostManager $postManager)
{
    if (isset($_POST['deleteUserPost'])) :
        $postManager->deleteUserPost();
        unset($_POST['deleteUserPost']);
        ManagePostAdminView::render($postManager);

    elseif (isset($_POST['addPostAdmin'])) :
        AddPostAdminView::render($postManager);

    elseif (isset($_POST['modifyAdminPost'])) :
        ModifyPostAdminView::render($postManager);
        unset($_POST['modifyAdminPost']);

    else :
        ManagePostAdminView::render($postManager);
    endif;
}
function modifyPostAdmin(PostManager $postManager)
{
    $post_Id = $_POST['idPostAdmin'];
    if ($_SESSION['userState'] === "Admin") :
        $postManager->modifyUserPost();
    endif;
    $commentManager = new CommentManager();
    CommentListView::render($commentManager, $post_Id, $postManager);
}
//END : Fonction pour Admin