<?php
require_once('_defaultView.php');

use Models\HomeManager;
use Models\CommentManager;

class commentListValidationView extends _DefaultView
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
                <div class="text-center my-5">';
        if (isset($_SESSION['commentManage'])) :
            $this->content .= '<h4>' . $_SESSION['commentManage'] . '</h4>';
            unset($_SESSION['commentManage']);
        endif;
        $this->htmlBefore .= '
                    <h1 class="fw-bolder">God Mod for User\'s Comments</h1>
                    <p class="lead mb-0 fst-italic">As an administrator, you have full control over the validation of comments! What joy !</p>
                </div>
            </div>
        </header>
        <div class="containter">
            <div class="row">
            ';
    }


    private function _getContent()
    {
        $homeManager = new HomeManager();
        $istCommentValidation = $this->commentManager->listCommentValidation($homeManager);
        $this->content = "";

        while ($data = $istCommentValidation->fetch()) :

            $this->content .=
                ' 
            <div class="col-lg-4 mb-2">
                <ul class=" list-group text-center">
            
                    <li class="list-group-item list-group-item-dark">Message : ' . $data['comment_Content'] . '</li>
                    <li class="list-group-item disabled">Author : ' . $data['comment_Author'] . '</li>
                    <li class="list-group-item disabled">Date : ' . $data['comment_Date_Add'] . '</li>
                    <a href="index.php?action=listComment&id=' . $data['post_id'] . ' " class="btn btn-outline-dark">Voir le post en entier</a>
                    <form action="index.php?action=validCommentUser" method="POST">
                        <input name="idCommentUser" type="hidden" value="' . $data['id'] . '">
                        <button type="submit" class="btn btn-outline-success" name="validCommentUser">Valid</button>
                        <button type="submit" class="btn btn-outline-danger" name="deleteCommentUser">Delete</button>
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
