<?php

namespace Models;

use Exception;

class UserManager extends Dbconnect
{
    private $userPwd;
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
        return $userPwdHash = password_hash($userPwd, CRYPT_BLOWFISH);
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
                        $_SESSION['idUser'] = $userInfo['id'];
                        $_SESSION['userLastName'] = $userInfo['user_Lastname'];
                        $_SESSION['userFirstName'] = $userInfo['user_Firstname'];
                        $_SESSION['userPhone'] = $userInfo['user_Phone'];
                        $_SESSION['userMail'] = $userInfo['user_Mail'];
                        $_SESSION['VerifConnection'] = 1;
                        $_SESSION['userState'] = $userInfo['user_State'];
                        return $userInfo;
                    else :
                        $_SESSION['connexionLose'] = "USERNAME OR PASSWORD INCORRECT";
                    endif;
                else :
                    $_SESSION['connexionLose'] = "USERNAME OR PASSWORD INCORRECT";
                endif;
            endif;
        endif;
    }

    // Inscription d'un user
    public function UserSignUp($homeManager)
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
                    return $_SESSION['userExist'] = 'User already exists !';
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
                    return $_SESSION['userExist'] = 'You have registered successfully. <a href="index.php?action=userConnect">Log in now !</a> !';
                endif;
            else :
                return $_SESSION['userExist'] = 'Passwords are not identical ! Retry !';
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
    public function modifyCoorUser($userManager)
    {
        $idUser = htmlentities($_SESSION['idUser']);
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
            $_SESSION['userLastName'] = $userLastName;
            $_SESSION['userFirstName'] = $userFirstName;
            $_SESSION['userPhone'] = $userPhone;
            $_SESSION['userMail'] = $userMail;


            $_SESSION['modifCoordUserValide'] = "Information Save prout";
        // return $req;

        else :
            userConnect($userManager);

        endif;
    }
    //Affiche les USERS via l'ADMIN
    public function listUserManage($homeManager)
    {
        $verifConnection = htmlentities($_SESSION['VerifConnection']);
        $userState = htmlentities($_SESSION['userState']);
        if (isset($verifConnection) && $userState == "Admin") :
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
        $_SESSION['userManage'] = 'User was delete ! </a>';
        header("Location: index.php?action=listUserManage");
    }
    public function acceptUser()
    {
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
        $_SESSION['userManage'] = 'User was accept ! </a>';
        header("Location: index.php?action=listUserManage");
    }
}
