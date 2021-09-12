<?php
require_once('_defaultView.php');

use Models\HomeManager;
use Models\PostManager;

class postListValidationView extends _DefaultView
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
        <header>
            <div class="container">
                <div class="row">';
    }


    private function _getContent()
    {
        $homeManager = new HomeManager();
        $listPostValidation = $this->postManager->listPostValidation($homeManager);
        $this->content = "";

        while ($data = $listPostValidation->fetch()) :

            $this->content .=
                ' 
                    <div class="col-lg-6">
                        <ul class="list-group">
        
                                <li class="list-group-item active">' . $data['post_Heading'] . '</li>
                                <li class="list-group-item disabled">' . $data['post_Content'] . '</li>
                                <li class="list-group-item disabled">' . $data['post_Author'] . '</li>
                                <form action="index.php?action=validUserPost" method="POST">
                                    <input name="idPostUser" type="hidden" value="' . $data['id'] . '">
                                    <button type="submit" class="btn btn-warning" name="validPostUser">Valid</button>
                                    <button type="submit" class="btn btn-danger" name="deletePostUser">Delete</button>
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
