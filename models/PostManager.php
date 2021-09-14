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

    public function listPost() //Affiche tous les posts validées
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
        if (isset($_SESSION['VerifConnection'])) :

            $req = $this->dbConnect->prepare('
            SELECT *
            FROM post 
            WHERE user_Id = :id
            ');
            $req->execute(
                [
                    "id" => $_SESSION['idUser']
                ]
            );
            return $req;
        else :
            displayHome($homeManager);
        endif;
    }

    public function addUserPost()
    {
        if (isset($_POST['addHeadingPost']) && isset($_POST['addContentPost']) && $_POST['addHeadingPost'] != "" && $_POST['addContentPost'] != "") :
            $postHeading = htmlentities($_POST['addHeadingPost']);
            $postChapo = htmlentities($_POST['addChapoPost']);
            $postAuthor = $_SESSION['userLastName'] . " " . $_SESSION['userFirstName'];
            $postContent = htmlentities($_POST['addContentPost']);
            $postCategorie = htmlentities($_POST['selectCategorieAddPost']);
            $postUserId = $_SESSION['idUser'];
            if ($_SESSION['userState'] === "Admin") :
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
    public function deleteUserPost()
    {
        $idPostUser = htmlentities($_POST['idPostAdmin']);
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

        $idPostUser = htmlentities($_POST['idPostAdmin']);
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
        $idPostUser = htmlentities($_POST['idPostAdmin']);
        $headingPostModify = htmlentities($_POST['headingPostModify']);
        $contentPostModify = htmlentities($_POST['contentPostModify']);
        $authorPostModify = htmlentities($_POST['authorPostModify']);
        $chapoPostModify = htmlentities($_POST['chapoPostModify']);
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
        if (isset($_SESSION['VerifConnection']) && $_SESSION['userState'] == "Admin") :
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
        if (isset($_SESSION['VerifConnection']) && $_SESSION['userState'] == "Admin") :
            $idPostUser = htmlentities($_POST['idPostUser']);
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
