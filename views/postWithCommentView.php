<?php
require_once('_defaultView.php');

use Models\CommentManager;
use Models\PostManager;

class PostWithCommentView extends _DefaultView
{

    private $commentManager;
    private $postManager;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;
    public $sessionError;

    private function __construct($post_Id, $sessionError, $postManager, $commentManager)
    {

        $this->commentManager = $commentManager;
        $this->sessionError = $sessionError;
        $this->postManager = $postManager;
        $this->_getHtmlBefore();
        $this->_getContent($post_Id, $sessionError);
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = parent::getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= parent::getFooter();
    }

    public static function render($commentManager, $post_Id = null, $postManager = null, $sessionError = null): void
    {
        $obj = new self($commentManager, $post_Id, $postManager, $sessionError);
        echo $obj->rendering;
    }

    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        ';
    }

    private function _getContent($post_id, $sessionError)
    {
        var_dump($post_id);
        $postManager = $this->postManager->listUniquePost($post_id);

        $this->content = "";
        $postManager = $postManager->fetchAll();
        if (!empty($postManager)) :
            foreach ($postManager as $data) :
                $this->content .=
                    '       <!-- Page content-->
                        <div class="container mt-5">
                            <div class="row">
                                <div class="col-lg-12">
                                    <!-- Post content-->
                                    <article>
                                        <!-- Post header-->
                                        <header class="mb-12">
                                            <!-- Post title-->
                                            <h1 class="fw-bolder mb-1">' . $data['post_Heading'] . '</h1>
                                            <!-- Post meta content-->
                                            <div class="text-muted fst-italic mb-2">Posted on ' . $data['post_Date_Add'] . ' by ' . $data['post_Author'] . '</div>
                                            <div class="text-muted fst-italic mb-2">Last modification on ' . $data['post_Date_Modif'] . '</div>
                                            <!-- Post categories-->
                                            <a class="badge bg-secondary text-decoration-none link-light" href="#!">' . $data['post_Category'] . '</a>';

                $this->content .=
                    '                    </header>
                                        <!-- Preview image figure-->
                                        <figure class="mb-4"><img class="img-fluid rounded" src="' . $data['post_Picture'] . $data['id'] . '.jpg" alt="..." /></figure>
                                        <!-- Post content-->
                                        <section class="mb-5">
                                            <p class="fs-5 mb-4">
                                                ' . $data['post_Chapo'] . '
                                            </p>
                                        </section>
                                        <section class="mb-5">
                                            <p class="fs-5 mb-4">
                                                ' . $data['post_Content'] . '
                                            </p>
                                        </section>
                                    </article>
                                    <!-- Comments section-->
                                    <section class="mb-5">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <!-- Comment form-->
                                                ';

                if (isset($_SESSION['idUser'])) :
                    $userState = htmlentities($_SESSION['userState']);
                    if ($userState != "Guest") :
                        $this->content .= isset($sessionError) ? '<div class="text-center" id="alert">' . $sessionError . '</div>' : false;
                        $this->content .= '
                                    
                                                <form class="mb-4" method="POST" action="index.php?action=addUserComment&id=' . $data['id'] . '&#alert">
                                                    <input name="idCommentUser" type="hidden" value="' . $data['id'] . '">
                                                    <textarea class="form-control" rows="3" name="contentCommentUser" placeholder="Join the discussion and leave a comment!"></textarea>
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-dark">Submit</button>
                                                    </div>
                                                </form>';
                    elseif ($userState == "Guest") :
                        $this->content .= '
                                                <p>
                                                    You must have a certified account to post a message !
                                                </p>
                        ';
                    else :
                        $this->content .= '
                                                <p>If you want to add a comment, you must 
                                                    <a href="index.php?action=userConnect" class="link" >log in</a>
                                                </p>
                        ';
                    endif;
                endif;
                break;
            endforeach;
        else :
            header("Location: index.php");
        endif;
        $commentManager = $this->commentManager->listComment($post_id);
        foreach ($commentManager as $data) :
            $this->content .= '      <!-- Comment with nested comments-->
                                            <div class="d-flex mb-4">
                                                <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                                <div class="ms-3">
                                                    <div class="fw-bold">' . $data['comment_Author'] . ' <span class="fw-light">added</span> ' . $data['comment_Date_Add'] . '</div>
                                                    ' . $data['comment_Content'] . '
                                                    
                                                </div>';
            if (isset($_SESSION['userState'])) :
                $userState = htmlentities($_SESSION['userState']);
                $userLastName = htmlentities($_SESSION['userLastName']);
                $userFirstName = htmlentities($_SESSION['userFirstName']);
                if ($userState == "Admin" || $userLastName . " " . $userFirstName == $data['comment_Author']) :
                    $this->content .= '
                                                <form class="p-1" action="index.php?action=validAndDeleteCommentUser" method="POST">
                                                    <input name="idCommentUser" type="hidden" value="' . $data[0] . '">
                                                    <input name="idPostAdmin" type="hidden" value="' . $data[5] . '">
                                                    <input name="namePage" type="hidden" value="manageCommentsDirectlyOnPost">
                                                    <button type="submit" class="btn btn-outline-danger" name="deleteCommentUser">Delete comment </button>
                                                </form>';
                endif;
            endif;
            $this->content .= '
                                            </div>';
        endforeach;
        $postManager = $this->postManager->listUniquePost($post_id);
        $this->content .= '
                                    </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>';
    }

    private function _getHtmlAfter()
    {
        return ' ';
    }
}
