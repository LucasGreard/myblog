<?php
require_once('_defaultView.php');

use Models\HomeManager;
use Models\UserManager;

class UserListManageView extends _DefaultView
{

    private $userManager;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;

    private function __construct(UserManager $userManager)
    {

        $this->userManager = $userManager;
        $this->_getHtmlBefore();
        $this->_getContent();
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = parent::getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= parent::getFooter();
    }


    public static function render($userManager, $post_Id = null, $postManager = null): void
    {
        $obj = new self($userManager);
        echo $obj->rendering;
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <header class="py-5 bg-light border-bottom mb-4">
            <div class="container">
                <div class="text-center my-5">
                    <h1 class="fw-bolder">God Mod for User\'s Life !</h1>
                    <p class="lead mb-0 fst-italic">As an administrator, You are in control of a user\'s life or death ! What joy !</p>
                </div>
            </div>
        </header>
        <div class="containter">
            <div class="row">';
        if (isset($_SESSION['userManage'])) :
            $this->content .= '<h4>' . $_SESSION['userManage'] . '</h4>';
            unset($_SESSION['userManage']);
        endif;
    }


    private function _getContent()
    {
        $homeManager = new HomeManager();
        $listUserManage = $this->userManager->listUserManage($homeManager);
        $this->content = "";

        while ($data = $listUserManage->fetch()) :

            $this->content .=
                ' 
                <div class="col-lg-4 mb-2">
                    <ul class="list-group text-center">
                        <li class="list-group-item list-group-item-dark">Name : ' . $data['user_Lastname'] . ' ' . $data['user_Firstname'] . '</li>
                        <li class="list-group-item disabled">Mail : ' . $data['user_Mail'] . '</li>
                        <li class="list-group-item disabled">Phone : ' . $data['user_Phone'] . '</li>
                        <li class="list-group-item disabled">State : ' . $data['user_State'] . '</li>
                        <form action="index.php?action=ManageUser" method="POST">
                            <input name="idUser" type="hidden" value="' . $data['id'] . '">
                            <button type="submit" class="btn btn-outline-danger" name="deleteUser">Delete</button>';
            if ($data['user_State'] == "Guest") :
                $this->content .= '
                            <button type="submit" class="btn btn-outline-success" name="acceptUser">Accept</button>';
            endif;
            $this->content .= '
                        </form>
                    </ul>
                </div>
                ';
        endwhile;
    }

    private function _getHtmlAfter()
    {
        return '
                </div>
            </div>
        </header>';
    }
}
