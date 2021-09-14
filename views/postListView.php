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
        $listPost = $this->postManager->listPost();
        $i = 1;

        while ($data = $listPost->fetch()) :
            if ($i === 1) :
                $this->content .= '
                        <div class="col-lg-8">
                            <!-- Blog post-->
                            <div class="card mb-4 col-lg-12">
                                <a href="#!">
                                    <img class="card-img-top" src="' . $data['post_Picture'] . $data['id'] . '.jpg" alt="..." />
                                </a>
                                <div class="card-body">
                                    <div class="small text-muted">' . $data['post_Date_Modif'] . '</div>
                                    <h2 class="card-title h4"><a class="card-title h4" href="index.php?action=listComment&id=' . $data['id'] . '">' . $data['post_Heading'] . '</a></h2>
                                    <p class="card-text">' . $data['post_Chapo'] . '</p>
                                    <a class="btn btn-light" href="index.php?action=listComment&id=' . $data['id'] . '">Read more →</a>
                                </div>
                            </div>
                        </div>
                        <!-- Side widgets-->
                        <div class="col-lg-4">
                            <!-- Search widget-->
                            <div class="card mb-4">
                                <div class="card-header">Search</div>
                                <div class="card-body">
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Enter search term..." aria-label="Enter search term..." aria-describedby="button-search" />
                                        <button class="btn btn-primary" id="button-search" type="button">Go!</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Side widgets-->
                            <div class="col-lg-12">
                                <!-- Categories widget-->
                                <div class="card mb-4">
                                    <div class="card-header">Categories</div>
                                    <div class="card-body">
                                        <div class="row">   
                            
                            
                            
                            ';
                $this->homeManager = new PostManager();
                $listHome = $this->homeManager->postListCategory();

                while ($data = $listHome->fetch()) :
                    $this->content .= '                    
                                            <div class="col">
                                                <a class="badge bg-secondary text-decoration-none link-light" href="#!">' . $data['post_Category'] . '</a>   
                                            </div>';
                endwhile;


                $this->content .= '
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        ';
                $i = 0;
            else :
                $this->content .= ' 
                        <div class="col-lg-6">
                            <!-- Blog post-->
                            <div class="card mb-4">
                                <a href="#!">
                                    <img class="card-img-top" src="' . $data['post_Picture'] . $data['id'] . '.jpg" alt="..." />
                                </a>
                                <div class="card-body">
                                    <div class="small text-muted">' . $data['post_Date_Add'] . '</div>
                                    <h2 class="card-title h4"><a class="card-title h4" href="index.php?action=listComment&id=' . $data['id'] . '">' . $data['post_Heading'] . '</a></h2>
                                    <p class="card-text">' . $data['post_Chapo'] . '</p>
                                    <a class="btn btn-light" href="index.php?action=listComment&id=' . $data['id'] . '">Read more →</a>
                                </div>
                            </div>
                        </div>
            ';
            endif;

        endwhile;
        $this->content .= '
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
