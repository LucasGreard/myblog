<?php
require_once('_defaultView.php');

use Models\ContactManager;

class ContactMeView extends _DefaultView
{

    private $userManage;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;

    private function __construct(ContactManager $userManage)
    {

        $this->userManage = $userManage;
        $this->_getHtmlBefore();
        $this->_getContent();
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = parent::getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= parent::getFooter();
    }


    public static function render($userManage, $post_Id = null): void
    {
        $obj = new self($userManage);
        echo $obj->rendering;
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <section>
            <div class="container">
                <div class="row">';
    }


    private function _getContent()
    {
        $this->content .= '
        <form action="index.php?action=messageSend" method="post">
            <div class="form-group">
                <label for="exampleFormControlInput1">Email address</label>
                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="';
        if (isset($_SESSION['userMail'])) :
            $this->content .= $_SESSION['userMail'];
        else :
            $this->content .= 'name@example.com';
        endif;

        $this->content .= '"
                >
            </div>
            <div class="form-group">
            <label for="exampleFormControlTextarea1">Message</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
            <input name="idUser" type="hidden" value="';
        if (isset($_SESSION['idUser'])) :
            $this->content .= $_SESSION['idUser'];
        else :
            $this->content .= "Inconnu";
        endif;
        $this->content .= '">
            <button type="submit" class="btn btn-warning" name="sendMessage">Send</button>
        </form>';
    }

    private function _getHtmlAfter()
    {
        return '
                </div>
            </div>
        </section>';
    }
}
