<?php
/* FOR FRONTEND.PHP

            require_once('views/userListPostsView.php'); A ajouter si les USERS peuvent ajouter un post
            require_once('views/userModifyPostView.php');
            require_once('views/postListValidationView.php');

            A ajouter si les USERS peuvent ajouter un post
            function listUserPosts(PostManager $postManager) //Affichage des posts de l'utilisateur
            {
            UserListPostsView::render($postManager);
            }

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
            $validPostUser = isset($_POST['validPosUser']);
            $deletePostUser = isset($_POST['deletePostUser']);
            if (isset($validPostUser)) {
            $postManager->valideUserPost();
            } elseif (isset($deletePostUser)) {
            $postManager->deleteUserPost();
            }
            PostListValidationView::render($postManager);
            }
*/

/* FOR INDEX.PHP

            // case 'listUserPosts':  // A ajouter si les USERS peuvent ajouter un post
            //     listUserPosts($postManager); //Affiche les posts d'un utilisateur
            //     break;

            // case 'addUserPost':
            //     addUserPost($postManager); //Ajoute d'un post par l'utilisateur
            //     break;

            // case 'listPostValidation':
            //     listPostValidation($postManager); //Liste les posts à valider
            //     break;
            // case 'validUserPost':
            //     valideUserPost($postManager); //Valide un post
            //     break;
