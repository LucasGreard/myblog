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


    public static function render($userManage, $post_Id = null, $postManager = null, $sessionError = null): void
    {
        $obj = new self($userManage);
        echo $obj->rendering;
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <header class="py-5 bg-light border-bottom mb-4">
            <div class="container">
                <div class="text-center my-5">
                    <h1 class="fw-bolder">Take the initiative, contact me !</h1>
                    <p class="lead mb-0 fst-italic">
                        How about making an appointment to talk about your business, your organization, your needs? We can see if we have an interest in collaborating.
                    </p>
                </div>
            </div>
        </header>';
    }


    private function _getContent()
    {
        $this->content .= '
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d42619.245700809595!2d-1.7234738316587024!3d48.11596753403909!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x480ede2fa7d69085%3A0x40ca5cd36e4ab30!2sRennes!5e0!3m2!1sfr!2sfr!4v1631544649471!5m2!1sfr!2sfr" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
                <div class="col-4 text-center">
                    <div class="">Call me : 06 52 32 19 43 </div>
                    <br/>
                    <p>Or</p>
                    <p>Send me a message !</p>
                    <form action="index.php?action=messageSend" method="post">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Email address</label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="';

        if (isset($_SESSION['userMail'])) :
            $userMail = htmlentities($_SESSION['userMail']);
            $this->content .= $userMail;
        else :
            $this->content .= 'name@example.com';
        endif;

        $this->content .= '">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Message</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                        <input name="idUser" type="hidden" value="';

        if (isset($_SESSION['idUser'])) :
            $idUser = htmlentities($_SESSION['idUser']);
            $this->content .= $idUser;
        else :
            $this->content .= " Inconnu";
        endif;
        $this->content .= '">
                        <button type="submit" class="btn btn-outline-success" name="sendMessage">Send</button>
                    </form>
                    
                </div>
                <div class="col-2"></div>
                
            </div>
        </div>
        ';
    }

    private function _getHtmlAfter()
    {
        return '
                ';
    }
}
