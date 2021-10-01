<?php

namespace Models;

use Exception;

class PostManager extends Dbconnect
{

    private $dbConnect;

    public function __construct()
    {
        $this->dbConnect = $this->dbConnect();
    }
    /**
     * @throws exception
     */

    /**
    * Display Posts_Page
    * @return sqlrequest
    */
    public function listPosts()
    {
        $req = '
            SELECT * 
            FROM post
            WHERE post_Validation = "Yes"
            ORDER BY post_Date_Add DESC
            ';
        $db = $this->dbConnect();
        return $db->query($req);
    }
    
    public function addAdminPost($sessionError)
    {
        $postHeading = filter_input(INPUT_POST, 'addHeadingPost', FILTER_SANITIZE_STRING);
        $postChapo = filter_input(INPUT_POST, 'addChapoPost', FILTER_SANITIZE_STRING);

        $postAuthor = SuperglobalManager::getSession('userLastName') . " " . SuperglobalManager::getSession('userFirstName');
        $postContent = filter_input(INPUT_POST, 'addContentPost', FILTER_SANITIZE_STRING);
        $postCategorie = filter_input(INPUT_POST, 'selectCategorieAddPost', FILTER_SANITIZE_STRING);
        if (isset($postHeading)  && isset($postContent) && isset($postChapo) && $postHeading != "" && $postChapo != "" && $postContent != "") :


            $postUserId = SuperglobalManager::getSession('idUser');;
            $userState = SuperglobalManager::getSession('userState');

            if ($userState === "Admin") :
                $postValidation = "Yes";
            else :
                $postValidation = "In Progress";
            endif;
            $req = $this->dbConnect->prepare('
                    INSERT INTO post ( post_Heading, post_Chapo, post_Author, post_Content, post_Category, post_Validation, user_Id)
                    VALUES ( :post_heading, :post_Chapo, :post_author, :post_content, :post_Category, :validation_id, :user_id)
                ');
            $req->execute(
                [
                    'post_heading' => $postHeading,
                    'post_Chapo' => $postChapo,
                    'post_author' => $postAuthor,
                    'post_content' => $postContent,
                    'post_Category' => $postCategorie,
                    'validation_id' => $postValidation,
                    'user_id' => $postUserId
                ]
            );
            return $sessionError->sessionError(5);

        else :
            return $sessionError->sessionError(6);

        endif;
    }
    public function deleteAdminPost($sessionError)
    {
        $idPostUser = filter_input(INPUT_POST, 'idPostAdmin', FILTER_SANITIZE_NUMBER_INT);
        $req = $this->dbConnect->prepare('
            DELETE FROM post
            WHERE id = :id
        ');
        $req->execute(
            [
                'id' => $idPostUser
            ]
        );
        return $sessionError->sessionError(7);
    }
    public function listUserPost()
    {

        $idPostUser = filter_input(INPUT_POST, 'idPostAdmin', FILTER_SANITIZE_NUMBER_INT);
        $req = $this->dbConnect->prepare('
        SELECT *
        FROM post 
        WHERE id = :id 
        ');
        $req->execute(
            [
                "id" => $idPostUser
            ]
        );
        return $req;
    }
    public function listUniquePost($post_id)
    {
        $req = $this->dbConnect->prepare('
        SELECT *
        FROM post 
        WHERE id = :id 
        ');
        $req->execute(
            [
                "id" => $post_id
            ]
        );
        return $req;
    }
    public function modifyUserPost($sessionError)
    {
        $idPostUser = filter_input(INPUT_POST, 'idPostAdmin', FILTER_SANITIZE_NUMBER_INT);
        $headingPostModify = filter_input(INPUT_POST, 'headingPostModify', FILTER_SANITIZE_STRING);
        $contentPostModify = filter_input(INPUT_POST, 'contentPostModify', FILTER_SANITIZE_STRING);
        $authorPostModify = filter_input(INPUT_POST, 'authorPostModify', FILTER_SANITIZE_STRING);
        $chapoPostModify = filter_input(INPUT_POST, 'chapoPostModify', FILTER_SANITIZE_STRING);
        $req = $this->dbConnect->prepare('
        UPDATE post
        SET post_Date_Modif = NOW(),   post_Heading  = :post_heading, post_Chapo = :post_chapo, post_Content = :post_content, post_Author = :post_author
        WHERE id = :id
        ');
        $req->execute(
            [
                "post_heading" => $headingPostModify,
                "post_content" => $contentPostModify,
                "post_author" => $authorPostModify,
                "post_chapo" => $chapoPostModify,
                "id" => $idPostUser
            ]
        );

        return $sessionError->sessionError(8);
    }

    public function listPostValidation(HomeManager $homeManager)
    {
        $sessionVerifConnexion = SuperglobalManager::getSession('verifConnexion');

        if (isset($sessionVerifConnexion) && $sessionVerifConnexion == "Admin") :
            $req = '
            SELECT *
            FROM post 
            WHERE post_Validation = "In Progress"
            ORDER BY post_Date_Add ASC
            ';
            $db = $this->dbConnect();
            return $db->query($req);
        else :
            displayHome($homeManager);
        endif;
    }
    public function valideUserPost()
    {
        $sessionVerifConnexion = SuperglobalManager::getSession('verifConnexion');
        if (isset($sessionVerifConnexion) && $sessionVerifConnexion  == "Admin") :
            $idPostUser = filter_input(INPUT_POST, 'idPostUser', FILTER_SANITIZE_NUMBER_INT);
            $req = $this->dbConnect->prepare('
            UPDATE post
            SET post_Validation = "Yes"
            WHERE id = :id
            ');
            $req->execute(
                [
                    "id" => $idPostUser
                ]
            );
            return $req;
        endif;
    }
    public function postListCategory()
    {
        $req = '
            SELECT DISTINCT post_Category 
            FROM post
            WHERE post_Validation = "Yes"
            ';
        $db = $this->dbConnect();
        return $db->query($req);
    }
    public function lastPostCreate()
    {
        $req = '
            SELECT  *
            FROM post
            ORDER BY post_Date_Add DESC
            LIMIT 1
            ';
        $db = $this->dbConnect();
        return $db->query($req);
    }
}
