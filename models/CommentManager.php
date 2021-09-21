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
        $sessionError = new SuperglobalManager();
        $commentContent = filter_input(INPUT_POST, 'contentCommentUser', FILTER_SANITIZE_STRING);
        if (isset($commentContent) && $commentContent != "") :
            $commentAuthor = SuperglobalManager::getSession('userLastName') . " " . SuperglobalManager::getSession('userFirstName');
            $commentUserId = $post_id;
            $req = $this->ifCommentExist($commentAuthor, $commentContent, $commentUserId);
            if ($req === '1') :
                return $sessionError->sessionError(5);
            elseif ($req === '0') :
                $userState = SuperglobalManager::getSession('userState');
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
                if ($userState == "Admin") :
                    return $sessionError->sessionError(1);
                else :
                    return $sessionError->sessionError(4);
                endif;
            endif;

        else :
            return $sessionError->sessionError(2);
        endif;
    }
    public function listCommentValidation($homeManager)
    {
        $verifConnexion = SuperglobalManager::getSession('verifConnexion');
        $userState = SuperglobalManager::getSession('userState');
        if (isset($verifConnexion) && $userState == "Admin") :
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
        $verifConnexion = SuperglobalManager::getSession('verifConnexion');
        $userState = SuperglobalManager::getSession('userState');
        if (isset($verifConnexion) && $userState === "Admin") :
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
    }
    public function userComments()
    {
        $homeManager = new HomeManager();
        $verifConnexion = SuperglobalManager::getSession('verifConnexion');
        $userLastName = SuperglobalManager::getSession('userLastName');
        $userFirstName = SuperglobalManager::getSession('userFirstName');
        if (isset($verifConnexion)) :
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
