<?php
include_once(dirname(__FILE__) . '/_defaultView.php');
class ManagePostAdminView extends _DefaultView
{

    private $postManager;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;
    public $sessionError;

    private function __construct($postManager, $sessionError)
    {

        $this->postManager = $postManager;
        $this->sessionError = $sessionError;
        $this->_getHtmlBefore();
        $this->_getContent($postManager, $sessionError);
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = parent::getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= parent::getFooter();
    }


    public static function render($postManager, $sessionError = null)
    {
        $obj = new self($postManager, $sessionError);
        print_r($obj->rendering);
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <header class="py-5 bg-light border-bottom mb-4">
            <div class="container">
                <div class="text-center my-5">
                    <h1 class="fw-bolder">All post I found</h1>
                    <p class="lead mb-0 fst-italic">Manage your post as you want ! Like a boss !</p>';
        $this->htmlBefore .= '
                </div>
            </div>
        </header>';
    }


    private function _getContent()
    {
        $this->content = "";
        $this->content .= isset($this->sessionError) ? '<div class="text-center" id="alert">' . $this->sessionError . '</div>' : false;
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
        $listPosts = $this->postManager->listPosts();


        while ($data = $listPosts->fetch()) :
            $this->content .= ' 
                        
                                <tr>
                                    <th scope="row">' . $data['id'] . '</th>
                                    <td>
                                        <a href="index.php?action=listPost&id=' . $data['id'] . '">' . $data['post_Heading'] . '</a>
                                    </td>
                                    <td>' . substr($data['post_Content'], 0, 50) . '</td>
                                    <td>
                                        <form action="index.php?action=managePostAdmin" method="POST">
                                            <input name="idPostAdmin" type="hidden" value="' . $data['id'] . '">
                                            <button type="submit" class="btn btn-outline-success" name="modifyAdminPost">Modify</button>
                                        </form>
                                        <button type="submit" class="btn btn-outline-danger" data-toggle="modal" data-target="#confirmDelete">Delete</button>
                                    </td>
                                </tr>
                             
                                <div class="modal" id="confirmDelete">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Confirm delete Post ?</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="modal-body">
                                           /!\ If you delete the post you will not be able to recover it ! /!\
                                        </div>
                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                                            <form action="index.php?action=managePostAdmin" method="POST">
                                            <input name="idPostAdmin" type="hidden" value="' . $data['id'] . '">
                                            <button type="submit" class="btn btn-danger" name="deleteAdminPost">Confirm delete</button>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
