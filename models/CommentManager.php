<?php

namespace Models;

use Models\Dbconnect;
use Exception;
use Models\SuperglobalManager;

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
            FROM comment
            WHERE  
                post_id =?
            AND comment_Validation = "Yes"
            ORDER BY comment_Date_Add DESC');
        $req->execute(
            [
                $post_id
            ]
        );
        $req = $req->fetchAll();
        return $req;
    }
    public function addUserComment($post_id)
    {
        if (isset($_POST['contentCommentUser']) && $_POST['contentCommentUser'] != "") :
            $commentAuthor = $_SESSION['userLastName'] . " " . $_SESSION['userFirstName'];
            $commentContent = htmlentities($_POST['contentCommentUser']);
            $commentUserId = $post_id;
            $req = $this->ifCommentExist($commentAuthor, $commentContent, $commentUserId);
            if ($req === '1') :
                return $_SESSION['commentManage'] = 'Comment already exists !';
            elseif ($req === '0') :
                $userState = htmlentities($_SESSION['userState']);
                if ($userState == "Admin") :
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
                    $sessionError = new SuperglobalManager();
                    return $sessionError->sessionError(1);
                else :
                    $sessionError = new SuperglobalManager();
                    return $sessionError->sessionError(4);
                endif;
            endif;

        else :
            $sessionError = new SuperglobalManager();
            return $sessionError->sessionError(2);
        endif;
    }
    public function listCommentValidation(HomeManager $homeManager)
    {
        $verifConnection = htmlentities($_SESSION['VerifConnection']);
        $userState = htmlentities($_SESSION['userState']);
        if (isset($verifConnection) && $userState == "Admin") :
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
        $verifConnection = htmlentities($_SESSION['VerifConnection']);
        $userState = htmlentities($_SESSION['userState']);
        if (isset($verifConnection) && $userState === "Admin") :
            $idCommentUser = filter_input(INPUT_POST, 'idCommentUser', FILTER_SANITIZE_NUMBER_INT);
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
        $idCommentUser = filter_input(INPUT_POST, 'idCommentUser', FILTER_SANITIZE_NUMBER_INT);
        $req = $this->dbConnect->prepare('
            DELETE FROM comment
            WHERE id = :id
        ');
        $req->execute(
            [
                'id' => $idCommentUser
            ]
        );

        // $_SESSION['commentManage'] = 'Your comment is delete !';
    }
    public function userComments()
    {
        $homeManager = new HomeManager();
        $verifConnection = htmlentities($_SESSION['VerifConnection']);
        $userLastName = htmlentities($_SESSION['userLastName']);
        $userFirstName = htmlentities($_SESSION['userFirstName']);
        if (isset($verifConnection)) :
            $req = $this->dbConnect->prepare('
            SELECT *
            FROM comment
            WHERE  comment_Author = ?
            ORDER BY comment_Validation DESC
            ');
            $req->execute(
                array(
                    $userLastName . " " . $userFirstName
                )
            );
            return $req;
        else :
            displayHome($homeManager);
        endif;
    }
    private function ifCommentExist($commentAuthor, $commentContent, $postId)
    {
        $req = $this->dbConnect->prepare('
            SELECT COUNT(*) 
            FROM comment
            WHERE comment_Content=:content
            AND comment_Author = :commentAuthor
            AND post_id = :post_id
            ');
        $req->execute(
            [
                'commentAuthor' => $commentAuthor,
                'content' => $commentContent,
                'post_id' => $postId
            ]
        );
        return $req->fetchColumn();
    }
}
