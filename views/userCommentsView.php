<?php
require_once('_defaultView.php');

use Models\CommentManager;

class userCommentsView extends _DefaultView
{

    private $commentManager;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;

    private function __construct(CommentManager $commentManager)
    {

        $this->commentManager = $commentManager;
        $this->_getHtmlBefore();
        $this->_getContent();
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = parent::getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= parent::getFooter();
    }


    public static function render($commentManager, $post_Id = null, $postManager = null): void
    {
        $obj = new self($commentManager);
        echo $obj->rendering;
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

        if (isset($_SESSION['commentManage'])) :
            $commentManage = htmlentities($_SESSION['commentManage']);
            $this->content .= '<h4>' . $commentManage . '</h4>';
            unset($_SESSION['commentManage']);
        endif;
    }


    private function _getContent()
    {
        $userListComments = $this->commentManager->userComments();
        $this->content = "";

        while ($data = $userListComments->fetch()) :
            $this->content .= '
                <div class="col-4 mb-2">
                    <ul class="list-group text-center">
                        
                        <li class="list-group-item list-group-item-dark">Message : ' . substr($data['comment_Content'], 0, 200) . ' ... </li>
                        <li class="list-group-item disabled">Date : ' . $data['comment_Date_Add'] . ' </li>
                        <li class="list-group-item disabled">Status : ' . $data['comment_Validation'] . ' </li>
                        <a href="index.php?action=listComment&id=' . $data['post_id'] . ' " class="btn btn-outline-dark">Voir le post en entier</a>';
            if (isset($_SESSION['userLastName']) && isset($_SESSION['userFirstName'])) :
                $userLastName = htmlentities($_SESSION['userLastName']);
                $userFirstName = htmlentities($_SESSION['userFirstName']);
                if (($userLastName . " " . $userFirstName) === $data['comment_Author']) :

                    $this->content .= ' <a href="index.php?action=deleteUserComment&id=' . $data['id'] . ' " class="btn btn-outline-danger">Delete your comment</a>';
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
