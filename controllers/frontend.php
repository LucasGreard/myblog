<?php
//REQUIRE_ONCE 
require_once('Models/PostManager.php');
require_once('Models/CommentManager.php');
require_once('Models/UserManager.php');
require_once('views/postListView.php');
require_once('views/userSignUpView.php');
require_once('views/commentListView.php');
// require_once('views/userListPostsView.php'); A ajouter si les USERS peuvent ajouter un post
require_once('views/userModifyPostView.php');
require_once('views/postListValidationView.php');
require_once('views/userLogOnView.php');
require_once('views/commentListValidationView.php');
require_once('views/userListManageView.php');
require_once('views/contactMeView.php');
require_once('views/userCommentsView.php');

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
function listComment(CommentManager $commentManager) // Affiche les commentaires
{
    $post_Id = $_GET['id'];
    CommentListView::render($commentManager, $post_Id);
}
function addUserComment(CommentManager $commentManager)
{
    $post_Id = $_GET['id'];
    $commentManager->addUsercomment($post_Id);
    CommentListView::render($commentManager, $post_Id);
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
    _DefaultView::render($userManager);
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
function deleteUser(UserManager $userManager)
{
    $userManager->deleteUser();
    UserListManageView::render($userManager);
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
