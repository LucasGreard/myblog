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


    public static function render($commentManager, $post_Id = null): void
    {
        $obj = new self($commentManager);
        echo $obj->rendering;
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <header>
            <div class="container">
                <div class="row">';
        if (isset($_SESSION['commentManage'])) :
            $this->content .= '<h4>' . $_SESSION['commentManage'] . '</h4>';
            unset($_SESSION['commentManage']);
        endif;
    }


    private function _getContent()
    {
        $homeManager = new HomeManager();
        $istCommentValidation = $this->commentManager->listCommentValidation($homeManager);
        $this->content = "";

        while ($data = $istCommentValidation->fetch()) :

            $this->content .=
                ' 
                    <div class="col-lg-6">
                        <ul class="list-group">
        
                                <li class="list-group-item active">' . $data['comment_Content'] . '</li>
                                <li class="list-group-item disabled">' . $data['comment_Author'] . '</li>
                                <li class="list-group-item disabled">' . $data['comment_Date_Add'] . '</li>
                                <li class="list-group-item disabled">' . $data['post_id'] . '</li>
                                <form action="index.php?action=validCommentUser" method="POST">
                                    <input name="idCommentUser" type="hidden" value="' . $data['id'] . '">
                                    <button type="submit" class="btn btn-warning" name="validCommentUser">Valid</button>
                                    <button type="submit" class="btn btn-danger" name="deleteCommentUser">Delete</button>
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
