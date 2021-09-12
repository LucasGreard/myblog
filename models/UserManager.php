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
            if (isset($_POST['userMail']) && isset($_POST['userPwd'])) :
                $userMail = htmlentities($_POST['userMail']);
                $userPwd = htmlentities($_POST['userPwd']);

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
        if (isset($_POST['userLastName']) && isset($_POST['userFirstName']) && isset($_POST['userPhone']) && isset($_POST['userMail'])) :

            $userLastName = htmlentities($_POST['userLastName']);
            $userFirstName = htmlentities($_POST['userFirstName']);
            $userPhone = htmlentities($_POST['userPhone']);
            $userMail = htmlentities($_POST['userMail']);
            $userPwd = htmlentities($_POST['userPwd']);
            $userPwd2 = htmlentities($_POST['userPwd2']);

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
        if (isset($_SESSION['idUser'])) :
            $userLastName = htmlentities($_POST['userLastName']);
            $userFirstName =  htmlentities($_POST['userFirstName']);
            $userPhone = htmlentities($_POST['userPhone']);
            $userMail = htmlentities($_POST['userMail']);
            $userPwd = htmlentities($_POST['userPwd']);
            $userPwdModify1 = htmlentities($_POST['userPwdModif1']);
            $userPwdModify2 = htmlentities($_POST['userPwdModif2']);

            if ($userPwdModify1 === $userPwdModify2) :
                $req = $this->dbConnect->prepare('
                        SELECT user_Password 
                        FROM user 
                        WHERE id=:id 
                        
                    ');
                $req->execute(
                    [
                        'id' => $_SESSION['idUser']
                    ]
                );
                $userPwdHash = $req->fetch();

                if (password_verify($userPwd, $userPwdHash['user_Password'])) :
                    $req = $this->dbConnect->prepare('
                    UPDATE user
                    SET userLastname = :userLastname, user_Firstname = :user_Firstname, user_Mail = :user_Mail, user_Phone = :user_Phone
                    WHERE id = :id
                ');
                    $req->execute(
                        [
                            "userLastname" => $userLastName,
                            "user_Firstname" => $userFirstName,
                            "user_Mail" => $userPhone,
                            "user_Phone" => $userMail,
                            "id" => $_SESSION['idUser']
                        ]
                    );
                    return $req;
                else :
                    $_SESSION['modifCoordUserValide'] = "Password is not good";
                endif;
            else :
                $_SESSION['modifCoordUserValide'] = "The new passwords are not the same";
            endif;
        else :
            userConnect($userManager);
        endif;
    }
    //Affiche les USERS via l'ADMIN
    public function listUserManage($homeManager)
    {
        if (isset($_SESSION['VerifConnection']) && $_SESSION['userState'] == "Admin") :
            $req = '
            SELECT *
            FROM user 
            WHERE user_State = "Guest" OR user_State ="Moderator"
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
        $idUser = htmlentities($_POST['idUser']);
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
}
