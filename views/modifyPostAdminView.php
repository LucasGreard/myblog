<?php
require_once('_defaultView.php');

use Models\PostManager;


class ModifyPostAdminView extends _DefaultView
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


    public static function render($postManager, $post_Id = null, $homeManager = null, $sessionError = null): void
    {
        $obj = new self($postManager);
        echo $obj->rendering;
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <header class="py-5 bg-light border-bottom mb-4">
            <div class="container">
                <div class="text-center my-5">
                    <h1 class="fw-bolder">Modify your post</h1>
                    <p class="lead mb-0 fst-italic">Manage your post as you want ! Like a boss !</p>
                </div>
            </div>
        </header>';
    }


    private function _getContent()
    {
        $this->content = "";
        $this->content .= '
        <div class="container">
            <div class="row">
                
                
                        ';
        $listUserPost = $this->postManager->listUserPost();


        while ($data = $listUserPost->fetch()) :
            $this->content .= ' 
                <form action="index.php?action=modifyPostAdmin" method="post">
                <div class="col-2"></div>
                    <div class="col-12 text-center p-2">
                            <div class="input-group-prepend text-center">
                                Heading
                                <input name="headingPostModify" class="form-control" value="' . $data['post_Heading'] . '" type="text">
                            </div> 
                    </div>
                    <div class="col-12 text-center p-2">      
                            <div class="input-group-prepend text-center">
                                <label for="exampleFormControlTextarea1">Chapo</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="chapoPostModify">' . $data['post_Chapo'] . '</textarea>
                            </div>
                    </div> 
                    <div class="col-12 text-center p-2">      
                            <div class="input-group-prepend text-center">
                                <label for="exampleFormControlTextarea1">Content</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="10" name="contentPostModify">' . $data['post_Content'] . '</textarea>
                            </div>
                    </div> 
                    <div class="col-12  text-center p-2"> 
                            <div class="input-group-prepend text-center">
                                Author
                                <input name="authorPostModify" class="form-control" value="' . $data['post_Author'] . '" type="text">
                            </div>
                    </div>
                    <div class="col-12  text-center p-2"> 
                        <input name="idPostAdmin" type="hidden" value="' . $data['id'] . '">
                        <button type="submit" class="btn btn-warning text-center p-2">Save post</button>
                    </div>
                </form>
        ';

        endwhile;
        $this->content .= '
                
            </div>
        </div>
                           ';
    }

    private function _getHtmlAfter()
    {
        return '
                </div>
            </div>
        </section>';
    }
}
