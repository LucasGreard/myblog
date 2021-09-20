<?php
include_once(dirname(__FILE__) . '/_defaultView.php');

use Models\PostManager;

class PostsListView extends _DefaultView
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
        print_r($obj->rendering);
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <header class="py-5 bg-light border-bottom mb-4">
            <div class="container">
                <div class="text-center my-5">
                    <h1 class="fw-bolder">All post I found</h1>
                    <p class="lead mb-0 fst-italic">If you don\'t see your comment, it means that it hasn\'t yet been validated by the Administrator</p>
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
                        ';
        $listPost = $this->postManager->listPosts();
        while ($data = $listPost->fetch()) :
            $this->content .= ' 
                        <div class="col-lg-6">
                            <!-- Blog post-->
                            <div class="card mb-4">
                                <a href="index.php?action=listPost&id=' . $data['id'] . '">
                                    <img class="card-img-top" src="' . $data['post_Picture'] . $data['id'] . '.jpg" alt="..." />
                                </a>
                                <div class="card-body">
                                    <div class="small text-muted">' . $data['post_Date_Add'] . '</div>
                                    <h2 class="card-title h4"><a class="card-title h4" href="index.php?action=listPost&id=' . $data['id'] . '">' . $data['post_Heading'] . '</a></h2>
                                    <p class="card-text">' . $data['post_Chapo'] . '</p>
                                    <a class="btn btn-light" href="index.php?action=listPost&id=' . $data['id'] . '">Read more →</a>
                                </div>
                            </div>
                        </div>
            ';
        endwhile;
    }

    private function _getHtmlAfter()
    {
        return '
                    </div>
                </div>
            </div>
        </div>';
    }
}
