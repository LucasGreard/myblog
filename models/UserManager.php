<?php

namespace Models;

use Exception;
use Models\SuperglobalManager;

class UserManager extends Dbconnect
{
    private $dbConnect;

    public function __construct()
    {
        $this->dbConnect = $this->dbConnect();
    }
    /**
     * @throws exception
     */
    private function passwordHash($userPwd) //Renvoi le mdp haché
    {
        return password_hash($userPwd, CRYPT_BLOWFISH);
    }


    //Récupérer tous les utilisateurs
    private function ifUserExist($userPhone, $userMail)
    {
        $req = $this->dbConnect->prepare('
            SELECT COUNT(*) 
            FROM user 
            WHERE user_Mail=:mail
            OR user_Phone = :phone
            ');
        $req->execute(
            [
                'mail' => $userMail,
                'phone' => $userPhone
            ]
        );
        return $req->fetchColumn();
    }
    // Connexion d'un user
    public function signOn()
    {
        if (!isset($userInfo)) :
            //On récupére les données du formulaire de connexion
            $userMail = filter_input(INPUT_POST, 'userMail', FILTER_SANITIZE_EMAIL);
            $userPwd = filter_input(INPUT_POST, 'userPwd', FILTER_SANITIZE_STRING);
            if (isset($userMail) && isset($userPwd)) :
                $req = $this->dbConnect->prepare('
                        SELECT user_Password 
                        FROM user 
                        WHERE user_Mail=:user_Mail
                        
                    ');
                $req->execute(
                    [
                        'user_Mail' => $userMail

                    ]
                );
                $userPwdHash = $req->fetch();

                if (password_verify($userPwd, $userPwdHash['user_Password'])) :


                    //On récupére les infos de l'utilisateur
                    $req = $this->dbConnect->prepare('
                        SELECT * 
                        FROM user 
                        WHERE user_Mail=:user_Mail      
                    ');
                    $req->execute(
                        [
                            'user_Mail' => $userMail
                        ]
                    );
                    //On met les données dans $_SESSION
                    if ($userInfo = $req->fetch()) :
                        SuperglobalManager::putSession('idUser', $userInfo['id']);
                        SuperglobalManager::putSession('userLastName', $userInfo['user_Lastname']);
                        SuperglobalManager::putSession('userFirstName', $userInfo['user_Firstname']);
                        SuperglobalManager::putSession('userPhone', $userInfo['user_Phone']);
                        SuperglobalManager::putSession('userMail', $userInfo['user_Mail']);
                        SuperglobalManager::putSession('VerifConnexion', 1);
                        SuperglobalManager::putSession('userState', $userInfo['user_State']);
                        return $userInfo;
                    else :
                        SuperglobalManager::putSession('connexionLose', "USERNAME OR PASSWORD INCORRECT");
                    endif;
                else :
                    SuperglobalManager::putSession('connexionLose', "USERNAME OR PASSWORD INCORRECT");
                endif;
            endif;
        endif;
    }

    // Inscription d'un user
    public function UserSignUp($homeManager, $sessionError)
    {
        $userLastName = filter_input(INPUT_POST, 'userLastName', FILTER_SANITIZE_STRING);
        $userFirstName = filter_input(INPUT_POST, 'userFirstName', FILTER_SANITIZE_STRING);
        $userPhone = filter_input(INPUT_POST, 'userPhone', FILTER_SANITIZE_STRING);
        $userMail = filter_input(INPUT_POST, 'userMail', FILTER_SANITIZE_EMAIL);

        if (isset($userLastName) && isset($userFirstName) && isset($userPhone) && isset($userMail)) :

            $userPwd = filter_input(INPUT_POST, 'userPwd', FILTER_SANITIZE_STRING);
            $userPwd2 = filter_input(INPUT_POST, 'userPwd2', FILTER_SANITIZE_STRING);

            if (isset($userPwd) && isset($userPwd2) && ($userPwd === $userPwd2) && $userPwd != "" && $userPwd2 != "") : //Si les deux mots de passes sont identiques et existent
                $req = $this->ifUserExist($userPhone, $userMail);
                if ($req === '1') :
                    return $sessionError->sessionError(16);
                elseif ($req === '0') :
                    $userPwd = $this->passwordHash($userPwd);
                    $req = $this->dbConnect->prepare('
                    INSERT INTO user (user_Lastname, user_Firstname, user_Mail, user_Password, user_Phone, user_State) 
                    VALUES (:user_Lastname, :user_Firstname, :user_Mail, :user_Password, :user_Phone, :user_State)
                    ');
                    $req->execute(
                        [
                            'user_Lastname' => $userLastName,
                            'user_Firstname' => $userFirstName,
                            'user_Mail' => $userMail,
                            'user_Password' => $userPwd,
                            'user_Phone' => $userPhone,
                            'user_State' => "Guest"
                        ]
                    );
                    return $sessionError->sessionError(14);
                endif;
            else :
                return $sessionError->sessionError(15);
            endif;
        else :
            displayHome($homeManager);
        endif;
    }

    //Suppression de la session User
    public function UserLogOut()
    {
        session_destroy();
        header("Location: index.php");
    }
    //Modification des coordonnées de l'utilisateur
    public function modifyCoorUser()
    {

        $idUser = SuperglobalManager::getSession('idUser');
        if (isset($idUser)) :
            $userLastName = filter_input(INPUT_POST, 'userLastName', FILTER_SANITIZE_STRING);
            $userFirstName = filter_input(INPUT_POST, 'userFirstName', FILTER_SANITIZE_STRING);
            $userPhone = filter_input(INPUT_POST, 'userPhone', FILTER_SANITIZE_STRING);
            $userMail = filter_input(INPUT_POST, 'userMail', FILTER_SANITIZE_EMAIL);

            $req = $this->dbConnect->prepare('
                    UPDATE user
                    SET user_Lastname = ?, user_Firstname = ?, user_Mail = ?, user_Phone = ?
                    WHERE id = ?
                ');

            $req->execute(
                [
                    $userLastName,
                    $userFirstName,
                    $userMail,
                    $userPhone,
                    $idUser
                ]
            );
            SuperglobalManager::putSession('userLastName', $userLastName);
            SuperglobalManager::putSession('userFirstName', $userFirstName);
            SuperglobalManager::putSession('userPhone', $userPhone);
            SuperglobalManager::putSession('userMail', $userMail);
            $sessionError = new SuperglobalManager();
            return $sessionError->sessionError(9);
        else :
            userConnect();

        endif;
    }
    //Affiche les USERS via l'ADMIN
    public function listUserManage($homeManager)
    {

        $verifConnexion = SuperglobalManager::getSession('VerifConnexion');
        $userState = SuperglobalManager::getSession('userState');
        if (isset($verifConnexion) && $userState == "Admin") :
            $req = '
            SELECT *
            FROM user 
            WHERE user_State = "Guest" OR user_State ="Moderator" OR user_State ="User"
            ORDER BY user_Lastname ASC
            ';
            $db = $this->dbConnect();
            return $db->query($req);
        else :
            displayHome($homeManager);
        endif;
    }
    //Détruit un USER
    public function deleteUser()
    {
        $sessionError = new SuperglobalManager();
        $idUser = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_NUMBER_INT);
        $req = $this->dbConnect->prepare('
            DELETE FROM user
            WHERE id = :id
        ');
        $req->execute(
            [
                'id' => $idUser
            ]
        );
        $sessionError->sessionError(13);
    }
    public function acceptUser()
    {
        $sessionError = new SuperglobalManager();
        $idUser = filter_input(INPUT_POST, 'idUser', FILTER_SANITIZE_NUMBER_INT);
        $req = $this->dbConnect->prepare('
            UPDATE user
            SET user_State = "User"
            WHERE id = :id
        ');
        $req->execute(
            [
                'id' => $idUser
            ]
        );
        $sessionError->sessionError(12);
    }
}
