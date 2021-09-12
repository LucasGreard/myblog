<?php
require_once('_defaultView.php');

use Models\PostManager;

class userModifyPostView extends _DefaultView
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
        $listUserPost = $this->postManager->listUserPost();
        $this->content = "";

        while ($data = $listUserPost->fetch()) :

            $this->content .= '
                        <div class="col-lg-12">
                            <h1>Modify your post</h1>
                            <form method="POST" action="index.php?action=modifyUserPost">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Heading</label>
                                    <input type="text" class="form-control" id="exampleFormControlTextArea1" value="' . $data['post_Heading'] . '" name="postHeadingUserModify">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Content</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="postContentUserModify">' . $data['post_Content'] . '</textarea>
                                </div>
                                <input name="idPostUser" type="hidden" value="' . $data['id'] . '">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>';
        endwhile;
        $this->content .= '<a href="index.php?action=listUserPosts" class="btn btn-link">Return to ur post</a>';
    }

    private function _getHtmlAfter()
    {
        return '        
                    </div>
                </div>
            </header>';
    }
}
