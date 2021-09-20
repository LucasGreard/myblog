<?php
include_once(dirname(__FILE__) . '/_defaultView.php');
class userCommentsView extends _DefaultView
{
    private $commentManager;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;
    private $sessionError;

    private function __construct($commentManager, $sessionError)
    {

        $this->commentManager = $commentManager;
        $this->sessionError = $sessionError;
        $this->_getHtmlBefore();
        $this->_getContent();
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = parent::getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= parent::getFooter();
    }


    public static function render($commentManager, $sessionError = null): void
    {
        $obj = new self($commentManager, $sessionError);
        print_r($obj->rendering);
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <header class="py-5 bg-light border-bottom mb-4">
            <div class="container">
                <div class="text-center my-5">
                    <h1 class="fw-bolder">Look at the status of your comments !</h1>
                    <p class="lead mb-0 fst-italic">Some of your comments must be validated by an Admin !</p>
                </div>
            </div>
        </header>
        <div class="containter">
            <div class="row">
                ';
    }


    private function _getContent()
    {
        $userListComments = $this->commentManager->userComments();
        $this->content = "";

        $this->content .= isset($this->sessionError) ? '<div class="text-center" id="alert">' . $this->sessionError . '</div>' : false;
        while ($data = $userListComments->fetch()) :
            $this->content .= '
                <div class="col-4 mb-2">
                    <ul class="list-group text-center">
                        
                        <li class="list-group-item list-group-item-dark">Message : ' . substr($data['comment_Content'], 0, 200) . ' ... </li>
                        <li class="list-group-item disabled">Date : ' . $data['comment_Date_Add'] . ' </li>
                        <li class="list-group-item disabled">Status : ' . $data['comment_Validation'] . ' </li>
                        <a href="index.php?action=listPost&id=' . $data['post_id'] . ' " class="btn btn-outline-dark">Voir le post en entier</a>';
            if (isset($_SESSION['userLastName']) && isset($_SESSION['userFirstName'])) :
                $userLastName = htmlentities($_SESSION['userLastName']);
                $userFirstName = htmlentities($_SESSION['userFirstName']);
                if (($userLastName . " " . $userFirstName) === $data['comment_Author']) :

                    $this->content .= ' 
                    <form action="index.php?action=validAndDeleteCommentUser" method="POST">
                        <input name="idCommentUser" type="hidden" value="' . $data['id'] . '">
                        <input name="namePage" type="hidden" value="myComments">
                        <button type="submit" class="btn btn-outline-danger" name="deleteCommentUser">Delete</button>
                    </form>
                    ';
                endif;
                $this->content .= '
                    </ul>
                </div>
                  ';
            endif;
        endwhile;
    }

    private function _getHtmlAfter()
    {
        return '
            </div>
        </div>
        ';
    }
}
