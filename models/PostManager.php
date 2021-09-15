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

    //Affiche les posts de l'utilisateur

    public function listPosts() //Affiche tous les posts validées
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

    public function listUserPosts() //Affiche la liste des posts d'un utilisateur
    {
        $homeManager = new HomeManager();
        $verifConnection = htmlentities($_SESSION['VerifConnection']);
        $idUser = htmlentities($_SESSION['idUser']);
        if (isset($verifConnection)) :

            $req = $this->dbConnect->prepare('
            SELECT *
            FROM post 
            WHERE user_Id = :id
            ');
            $req->execute(
                [
                    "id" => $idUser
                ]
            );
            return $req;
        endif;
        displayHome($homeManager);
    }

    public function addAdminPost()
    {
        if (isset($_POST['addHeadingPost']) && isset($_POST['addContentPost']) && $_POST['addHeadingPost'] != "" && $_POST['addContentPost'] != "") :

            $postHeading = filter_input(INPUT_POST, 'addHeadingPost', FILTER_SANITIZE_STRING);
            $postChapo = filter_input(INPUT_POST, 'addChapoPost', FILTER_SANITIZE_STRING);
            $postAuthor = filter_input(INPUT_POST, 'userLastName', FILTER_SANITIZE_STRING) . " " . filter_input(INPUT_POST, 'userFirstName', FILTER_SANITIZE_STRING);
            $postContent = filter_input(INPUT_POST, 'addContentPost', FILTER_SANITIZE_STRING);
            $postCategorie = filter_input(INPUT_POST, 'selectCategorieAddPost', FILTER_SANITIZE_STRING);
            $postUserId = $_SESSION['idUser'];
            $userState = htmlentities($_SESSION['userState']);

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

            $_SESSION['postAdd'] = 'Your post is add ! </a>';
            header("Location: index.php?action=managePostAdmin");
        else :
            $_SESSION['postAdd'] = 'Your post isn\'t add ';
            header("Location: index.php?action=managePostAdmin");
        endif;
    }
    public function deleteAdminPost()
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
        $_SESSION['postAdd'] = 'Your post is delete ! </a>';
        header("Location: index.php?action=managePostAdmin");
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
    public function modifyUserPost()
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
        $_SESSION['postModify'] = "Your post has been modified";
        return $req;
    }

    public function listPostValidation(HomeManager $homeManager)
    {
        $verifConnection = htmlentities($_SESSION['VerifConnection']);
        if (isset($verifConnection) && $verifConnection == "Admin") :
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
        $verifConnection = htmlentities($_SESSION['VerifConnection']);
        if (isset($verifConnection) && $verifConnection  == "Admin") :
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
