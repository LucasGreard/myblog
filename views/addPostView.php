<?php
require_once('_defaultView.php');

use Models\PostManager;


class AddPostAdminView extends _DefaultView
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
                    <h1 class="fw-bolder">Add a post</h1>
                    <p class="lead mb-0 fst-italic">Manage your post as you want ! Like a boss !</p>';

        // if (isset($_SESSION['postAdd'])) :
        //     $postAdd = htmlentities($_SESSION['postAdd']);
        //     $this->htmlBefore .= $postAdd;
        //     unset($_SESSION['postAdd']);
        // endif;
        $this->htmlBefore .= '
                </div>
            </div>
        </header>';
    }


    private function _getContent()
    {
        $this->content = "";
        $this->content .= isset($sessionError) ? '<div class="text-center" id="alert">' . $sessionError . '</div>' : false;
        $this->content .= '
        <form action="index.php?action=addPostAdmin" method="post">
            <div class="col-2"></div>
            <div class="col-12 text-center p-2">
                    <div class="input-group-prepend text-center">
                        Heading
                        <input name="addHeadingPost" class="form-control"  type="text">
                    </div> 
            </div>
            <div class="col-12 text-center p-2">
                    <div class="input-group-prepend text-center">
                        Chapô
                        <input name="addChapoPost" class="form-control"  type="text">
                    </div> 
            </div>
            <div class="col-12 text-center p-2">      
                    <div class="input-group-prepend text-center">
                        <label for="exampleFormControlTextarea1">Content</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="addContentPost"></textarea>
                    </div>
            </div>
            <div class="col-12 text-center p-2">
                <div class="input-group-prepend text-center">
                    <label for="exampleFormControlTextarea1">Categories</label>
                </div>
                <select class="custom-select" id="inputGroupSelect01" name="selectCategorieAddPost">
                    <option selected>Choose...</option>';

        $listCategories = $this->postManager->postListCategory();

        while ($data = $listCategories->fetch()) :
            $this->content .= '
                    <option value="' . $data['post_Category'] . '">' . $data['post_Category'] . '</option>';
        endwhile;

        $this->content .= '        
                </select>
            </div>
            <div class="col-12  text-center p-2"> 
                <input name="idPostAdmin" type="hidden" >
                <button type="submit" class="btn btn-warning text-center p-2">Add post</button>
            </div>
        </form>
                
                
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
