<?php

namespace Models;

use Models\Dbconnect;
use Exception;

class CommentManager extends Dbconnect
{
    private $dbConnect;

    public function __construct()
    {
        $this->dbConnect = $this->dbConnect();
    }
    /**
     * @throws exception
     */
    public function listComment($post_id)
    {

        $req = $this->dbConnect->prepare('
        SELECT *
        FROM comment AS c, post AS p
        WHERE  c.post_id =?
        AND p.id =?
        AND c.comment_Validation = "Yes"
        ORDER BY comment_Date_Add DESC');
        $req->execute(
            array(
                $post_id,
                $post_id
            )
        );
        $req = $req->fetchAll();
        if (empty($req)) :
            $req = $this->dbConnect->prepare('
                SELECT *
                FROM post 
                WHERE  id =?
                ');
            $req->execute(
                array(
                    $post_id
                )
            );
            return $req;
        endif;
        return $req;
    }
    public function addUserComment($post_id)
    {
        if (isset($_POST['contentCommentUser']) && $_POST['contentCommentUser'] != "") :
            $commentAuthor = $_SESSION['userLastName'] . " " . $_SESSION['userFirstName'];
            $commentContent = htmlentities($_POST['contentCommentUser']);
            $commentUserId = $post_id;

            if ($_SESSION['userState'] == "Admin") :
                $commentValidation = "Yes";
            else :
                $commentValidation = "In Progress";
            endif;

            $req = $this->dbConnect->prepare('
                    INSERT INTO comment ( comment_Content, comment_Author, comment_Validation, post_id)
                    VALUES ( :comment_Content, :comment_Author, :comment_Validation, :post_id)
                ');
            $req->execute(
                [
                    'comment_Content' => $commentContent,
                    'comment_Author' => $commentAuthor,
                    'comment_Validation' => $commentValidation,
                    'post_id' => $commentUserId
                ]
            );
            if ($_SESSION['userState'] == "Admin") :
                $_SESSION['commentAdd'] = 'Your comment is add ! </a>';
            else :
                $_SESSION['commentAdd'] = 'Your comment must first be validated by the administrator before being visible. 
                                            Find all your comments awaiting validation <a href ="">here</a>  ! </a>';
            endif;

        else :
            $_SESSION['commentAdd'] = 'Your comment isn\'t add ';
        endif;
    }
    public function listCommentValidation(HomeManager $homeManager)
    {
        if (isset($_SESSION['VerifConnection']) && $_SESSION['userState'] == "Admin") :
            $req = '
            SELECT *
            FROM comment as c 
            WHERE c.comment_Validation = "In Progress"
            ORDER BY comment_Date_Add DESC
            ';
            $db = $this->dbConnect();
            return $db->query($req);
        else :
            displayHome($homeManager);
        endif;
    }
    public function valideCommentUser()
    {
        if (isset($_SESSION['VerifConnection']) && $_SESSION['userState'] === "Admin") :
            $idCommentUser = htmlentities($_POST['idCommentUser']);
            $req = $this->dbConnect->prepare('
            UPDATE comment
            SET comment_Validation = "Yes"
            WHERE id = :id
            ');
            $req->execute(
                [
                    "id" => $idCommentUser
                ]
            );
            $_SESSION['commentManage'] = 'Comment modified by "YES"';
        endif;
    }
    public function deleteUserComment()
    {
        $idCommentUser = htmlentities($_POST['idCommentUser']);
        $req = $this->dbConnect->prepare('
            DELETE FROM comment
            WHERE id = :id
        ');
        $req->execute(
            [
                'id' => $idCommentUser
            ]
        );
        $_SESSION['CommentAdd'] = 'Your comment is delete ! </a>';
        header("Location: index.php?action=listUserPosts");
    }
    public function userComments()
    {
        $homeManager = new HomeManager();
        if (isset($_SESSION['VerifConnection'])) :
            $req = $this->dbConnect->prepare('
            SELECT *
            FROM comment
            WHERE  comment_Author = ?
            ORDER BY comment_Validation DESC
            ');
            $req->execute(
                array(
                    $_SESSION['userLastName'] . " " . $_SESSION['userFirstName']
                )
            );
            return $req;
        else :
            displayHome($homeManager);
        endif;
    }
}
