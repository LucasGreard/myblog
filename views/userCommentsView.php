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


    public static function render($commentManager, $post_Id = null): void
    {
        $obj = new self($commentManager);
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
        $userListComments = $this->commentManager->userComments();
        $this->content = "";

        while ($data = $userListComments->fetch()) :
            $this->content .= '
                   <div class="col-lg-6">
                        <ul class="list-group">

                            <li class="list-group-item active">' . $data['comment_Date_Add'] . ' </li>
                            <li class="list-group-item disabled">' . substr($data['comment_Content'], 0, 200) . ' ... </li>
                            <li class="list-group-item disabled">' . $data['comment_Validation'] . ' </li>
                            <a href="index.php?action=listComment&id=' . $data['post_id'] . ' " class="list-group-item active">Voir le post en entier</a>

                        </ul>
                    </div>';
        endwhile;
    }

    private function _getHtmlAfter()
    {
        return '
                </div>
            </div>
        </section>';
    }
}
