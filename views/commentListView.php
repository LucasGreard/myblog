<?php
require_once('_defaultView.php');

use Models\CommentManager;

class commentListView extends _DefaultView
{

    private $commentManager;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;

    private function __construct(CommentManager $commentManager, $post_Id)
    {

        $this->commentManager = $commentManager;
        $this->_getHtmlBefore();
        $this->_getContent($commentManager, $post_Id);
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = parent::getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= parent::getFooter();
    }


    public static function render($commentManager, $post_Id = null): void
    {
        $obj = new self($commentManager, $post_Id);
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


    private function _getContent($commentManager, $post_id)
    {
        $commentManager = $this->commentManager->listComment($post_id);
        $this->content = "";
        foreach ($commentManager as $data) :

            $this->content .=
                '       <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item disabled">Heading :' . $data['post_Heading'] . '</li>
                                <li class="list-group-item active">Author :' . $data['post_Author'] . '</li>
                                <li class="list-group-item active">Content :' . $data['post_Content'] . '</li>
                            </ul>
                        </div>';
            break;
        endforeach;
        $this->content .= '
                    </div>
                <div class="row">';

        if (isset($_SESSION['idUser'])) :
            $this->content .= '
                    <div class="col-lg-6 text-center">
                        <h2>Add a comment</h2>
                        <h4>';
            if (isset($_SESSION['commentAdd'])) :
                $this->content .= $_SESSION['commentAdd'];
                unset($_SESSION['commentAdd']);
            endif;
            $this->content .= '
                        </h4>
                        <hr class="star-light">
                    </div>';
        endif;

        $this->content .= '
                    <div class="';
        if (isset($_SESSION['idUser'])) :
            $this->content .= 'col-lg-6';
        else :
            $this->content .= 'col-lg-12';
        endif;
        $this->content .= ' text-center">
                        <h2>View Comment</h2>
                        <hr class="star-light">
                    </div>
                </div>';

        if (isset($_SESSION['idUser'])) :

            $this->content .= '
                <div class="row">
                    <div class="col-lg-6">
                        <form method="POST" action="index.php?action=addUserComment&id=' . $data['id'] . '">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Content</label>
                                <input name="idCommentUser" type="hidden" value="' . $data['id'] . '">
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="contentCommentUser">Start with a .php doc, ..</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>';
        endif;
        foreach ($commentManager as $data) :

            $this->content .= '
                        <div class="';
            if (isset($_SESSION['idUser'])) :
                $this->content .= 'col-lg-6';
            else :
                $this->content .= 'col-lg-12';
            endif;
            $this->content .= '">
                            <ul class="list-group">
                                <li class="list-group-item disabled">Content :' . $data['comment_Content'] . '</li>
                                <li class="list-group-item active">Author :' . $data['comment_Author'] . '</li>
                                <li class="list-group-item active">Status :' . $data['comment_Validation'] . '</li>
                                <li class="list-group-item active">';

            if (isset($_SESSION['idUser'])) :
                if ($data['comment_Author'] == $_SESSION['userLastName'] . " " . $_SESSION['userFirstName'] || $_SESSION['userState'] == "Admin") :
                    $this->content .= '
                                <form action="index.php?action=modifUserComment" method="POST">
                                    <input name="idCommentUser" type="hidden" value=' . $data['id'] . '>
                                    <button type="submit" class="btn btn-danger">Delete</button>';
                    if ($data['comment_Validation'] == "In progress") :
                        $this->content .= '
                                    <button type="submit" class="btn btn-warning">Accept</button>
                                </form>';
                    endif;
                endif;
            endif;
            $this->content .= '
                                </li>
                            </ul>
                        </div>';
        endforeach;
    }

    private function _getHtmlAfter()
    {
        return ' </div> <!-- card.// -->
            </div>
        </div>
</header>';
    }
}
