<?php
require_once('_defaultView.php');

use Models\PostManager;


class ManagePostAdminView extends _DefaultView
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


    public static function render($postManager, $post_Id = null, $homeManager = null): void
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
                    <h1 class="fw-bolder">All post I found</h1>
                    <p class="lead mb-0 fst-italic">Manage your post as you want ! Like a boss !</p>';
        if (isset($_SESSION['postAdd'])) :
            $this->htmlBefore .= $_SESSION['postAdd'];
            unset($_SESSION['postAdd']);
        endif;
        $this->htmlBefore .= '
                </div>
            </div>
        </header>';
    }


    private function _getContent()
    {
        $this->content = "";
        $this->content .= '
        <!-- Page content-->
        <div class="container">
            <div class="row">
            
                <!-- Blog entries-->
                <div class="col-lg-12">
                    <!-- Nested row for non-featured blog posts-->
                    <div class="row">
                        <form action="index.php?action=managePostAdmin" method="POST" class="text-center">
                        <input name="addPostAdmin" type="hidden" value="1">
                            <button class="btn btn-outline-success text-center">
                                Ajouter un post
                            </button>
                        </form>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">id_Post</th>
                                    <th scope="col">Heading</th>
                                    <th scope="col">Content (20 characters max)</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                        ';
        $listPost = $this->postManager->listPost();


        while ($data = $listPost->fetch()) :
            $this->content .= ' 
                        
                                <tr>
                                    <th scope="row">' . $data['id'] . '</th>
                                    <td>
                                        <a href="index.php?action=listComment&id=' . $data['id'] . '">' . $data['post_Heading'] . '</a>
                                    </td>
                                    <td>' . substr($data['post_Content'], 0, 50) . '</td>
                                    <td>
                                        <form action="index.php?action=managePostAdmin" method="POST">
                                            <input name="idPostAdmin" type="hidden" value="' . $data['id'] . '">
                                            <button type="submit" class="btn btn-outline-success" name="modifyAdminPost">Modify</button>
                                            <button type="submit" class="btn btn-outline-danger" name="deleteUserPost">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                             

                        ';

        endwhile;
        $this->content .= '
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>';
    }

    private function _getHtmlAfter()
    {
        return '
                </div>
            </div>
        </section>';
    }
}