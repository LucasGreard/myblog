<?php
require_once('_defaultView.php');

use Models\HomeManager;
use Models\PostManager;

class userListPostsView extends _DefaultView
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
        $this->_getContent($postManager);
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
            <section class="success" id="about">
                <div class="container">
                    <div class="row">';
    }


    private function _getContent()
    {
        $listUserPosts = $this->postManager->listUserPosts();
        $this->content = "";
        $this->content .= '
                        <div class="col-lg-6 text-center">
                            <h2>Add a post</h2>
                            <h4>';
        if (isset($_SESSION['postAdd'])) :
            $this->content .= '' . $_SESSION['postAdd'] . '';
            unset($_SESSION['postAdd']);
        endif;
        $this->content .= '</h4>
                            <hr class="star-light">
                        </div>

                        <div class="col-lg-6 text-center">
                            <h2>View Post</h2>
                            <hr class="star-light">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <form method="POST" action="index.php?action=addUserPost">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Heading</label>
                                    <input type="text" class="form-control" id="exampleFormControlTextArea1" name="headingPostUser" value="How to create a PHP Doc">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Content</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="contentPostUser">Start with a .php doc, ..</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>';
        while ($data = $listUserPosts->fetch()) :
            $this->content .= '
                        <div class="col-lg-6">
                            <ul class="list-group">
                                <li class="list-group-item active">Heading : ' . $data['post_Heading'] . '</li>
                                <li class="list-group-item disabled">Content : ' . $data['post_Content'] . '</li>
                                <li class="list-group-item active">Status : ' . $data['post_Validation'] . '</li>
                                <li class="list-group-item active">
                                    <form action="index.php?action=deleteUserPost" method="POST">
                                        <input name="idPostUser" type="hidden" value="' . $data['id'] . '">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                    <form action="index.php?action=modifyUserPost" method="POST">
                                        <input name="idPostUser" type="hidden" value="' . $data['id'] . '">
                                        <button type="submit" class="btn btn-warning">Modify</button>
                                    </form>
                                </li>
                            </ul>
                        </div>';
        endwhile;
    }

    private function _getHtmlAfter()
    {
        return '
                    </div>
                </div>
            </section>

        </header>';
    }
}
