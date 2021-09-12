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
        if (isset($_POST['headingPostUser']) && isset($_POST['contentPostUser']) && $_POST['headingPostUser'] != "" && $_POST['contentPostUser'] != "") :
            $postHeading = htmlentities($_POST['headingPostUser']);
            $postAuthor = $_SESSION['userLastName'] . " " . $_SESSION['userFirstName'];
            $postContent = htmlentities($_POST['contentPostUser']);
            $postUserId = $_SESSION['idUser'];
            if ($_SESSION['userState'] === "Admin") :
                $postValidation = "Yes";
            else :
                $postValidation = "In Progress";
            endif;
            $req = $this->dbConnect->prepare('
                    INSERT INTO post ( post_Heading, post_Author, post_Content, post_Validation, user_Id)
                    VALUES ( :post_heading, :post_author, :post_content, :validation_id, :user_id)
                ');
            $req->execute(
                [
                    'post_heading' => $postHeading,
                    'post_author' => $postAuthor,
                    'post_content' => $postContent,
                    'validation_id' => $postValidation,
                    'user_id' => $postUserId
                ]
            );

            $_SESSION['postAdd'] = 'Your post is add ! </a>';
            header("Location: index.php?action=listUserPosts");
        else :
            $_SESSION['postAdd'] = 'Your post isn\'t add ';
            header("Location: index.php?action=listUserPosts");
        endif;
    }
    public function deleteUserPost()
    {
        $idPostUser = htmlentities($_POST['idPostUser']);
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
        header("Location: index.php?action=listUserPosts");
    }
    public function listUserPost()
    {
        $idPostUser = htmlentities($_POST['idPostUser']);
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
    public function modifyUserPost()
    {
        $idPostUser = htmlentities($_POST['idPostUser']);
        $headingPostModify = htmlentities($_POST['postHeadingUserModify']);
        $contentPostModify = htmlentities($_POST['postContentUserModify']);
        $req = $this->dbConnect->prepare('
        UPDATE post
        SET post_Date_Modif = NOW(),   post_Heading  = :post_heading, post_Content = :post_content
        WHERE id = :id
        ');
        $req->execute(
            [
                "post_heading" => $headingPostModify,
                "post_content" => $contentPostModify,
                "id" => $idPostUser
            ]
        );
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
}
