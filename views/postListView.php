<?php
require_once('_defaultView.php');

use Models\PostManager;

class postListView extends _DefaultView
{

    private $postManager;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;

    private function __construct(PostManager $postManager)
    {

        $this->postManager = $postManager;
        $this->_getHtmlBefore();
        $this->_getContent();
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = parent::getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= parent::getFooter();
    }


    public static function render($postManager, $post_Id = null): void
    {
        $obj = new self($postManager);
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
        $listPost = $this->postManager->listPost();
        $this->content = "";

        while ($data = $listPost->fetch()) :

            $this->content .= '
                   <div class="col-lg-6">
                        <ul class="list-group">

                            <li class="list-group-item active">' . $data['post_Heading'] . ' </li>
                            <li class="list-group-item disabled">' . substr($data['post_Content'], 0, 200) . ' ... </li>
                            <li class="list-group-item disabled">' . $data['post_Author'] . ' </li>
                            <li class="list-group-item disabled">' . $data['post_Date_Modif'] . ' </li>
                            <a href="index.php?action=listComment&id=' . $data['id'] . ' " class="list-group-item active">Voir le post en entier</a>

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
