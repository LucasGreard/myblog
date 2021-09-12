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


    public static function render($userManager, $post_Id = null): void
    {
        $obj = new self($userManager);
        echo $obj->rendering;
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <header>
            <div class="container">
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
                    <div class="col-lg-6">
                        <ul class="list-group">
        
                                <li class="list-group-item active">' . $data['user_Lastname'] . '</li>
                                <li class="list-group-item disabled">' . $data['user_Firstname'] . '</li>
                                <li class="list-group-item disabled">' . $data['user_Mail'] . '</li>
                                <li class="list-group-item disabled">' . $data['user_Phone'] . '</li>
                                <li class="list-group-item disabled">' . $data['user_State'] . '</li>
                                <form action="index.php?action=deleteUser" method="POST">
                                    <input name="idUser" type="hidden" value="' . $data['id'] . '">
                                    <button type="submit" class="btn btn-danger" name="deleteUser">Delete</button>
                                </form>

                            </ul>
                        </div>';
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
