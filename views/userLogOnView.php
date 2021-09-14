<?php
require_once('_defaultView.php');

use Models\UserManager;

class UserLogOnView extends _DefaultView
{

    private $userConnect;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;

    private function __construct(UserManager $userConnect)
    {

        $this->userConnect = $userConnect;
        $this->_getHtmlBefore();
        $this->_getContent();
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = parent::getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= parent::getFooter();
    }


    public static function render($userConnect, $post_Id = null, $postManager = null): void
    {
        $obj = new self($userConnect);
        echo $obj->rendering;
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <header class="py-5 bg-light border-bottom mb-4">
            <div class="container">
                <div class="text-center my-5">';

        if (isset($_SESSION['VerifConnection'])) :
            $this->htmlBefore .= '        
                    <h1 class="fw-bolder">If it\'s you, it\'s your contact details :)</h1>
                    <p class="lead mb-0 fst-italic">Modify what you want !</p>
                </div>
            </div>
        </header>';
        else :
            $this->htmlBefore .= '        
                    <h1 class="fw-bolder">Log in or sign up ? That is the question</h1>
                    <p class="lead mb-0 fst-italic">Nice to meet you !</p>
                </div>
            </div>
        </header>';
        endif;
        $this->htmlBefore .= '
        <div class="container">
            <div class="row">
                
                
            ';
    }


    private function _getContent()
    {
        $this->content = "";
        if (isset($_SESSION['VerifConnection'])) :
            $this->content .= '
                <div class="col-3">
                    <img src="public/img/thumbnail/karl-tomas-apresentacao-fornite.jpg" alt="..." class="img-thumbnail">
                </div>
                <div class="col-8">
                    <form action="index.php?action=modifyCoorUser" method="post">
                        <div class="form-group input-group">
                    
                            <div class="input-group-prepend text-center p-1">
                                Last Name
                                <input name="userLastName" class="form-control" value="' . $_SESSION['userLastName'] . '" type="text">
                            </div>
                            <div class="input-group-prepend text-center p-1">
                                First Name
                                <input name="userFirstName" class="form-control" value="' . $_SESSION['userFirstName'] . '" type="text">
                            </div>
                            <div class="input-group-prepend text-center p-1">
                                Phone Number
                                <input name="userPhone" class="form-control" value="' . $_SESSION['userPhone'] . '" type="text">
                            </div>
                            <div class="input-group-prepend text-center p-1">
                                Email Address
                                <input name="userMail" class="form-control" value="' . $_SESSION['userMail'] . '" type="email"> 
                            </div>
                            <div class="input-group-prepend text-center p-1">
                                Your actually password
                                <input name="userPwd" class="form-control" placeholder="********" type="password"> 
                            </div>
                            <div class="input-group-prepend text-center p-1">
                                Your new password
                                <input name="userPwdModif1" class="form-control" placeholder="********" type="password"> 
                            </div>
                            <div class="input-group-prepend text-center p-1">
                                Verify your new password
                                <input name="userPwdModif2" class="form-control" placeholder="********" type="password"> 
                            </div>
                            <div class="input-group text-center p-1">
                                Status : 
                                ' . $_SESSION['userState'] . ' 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-warning text-center">Save information</button>
                            </div>
                            <div class="col-3">
                                <a href="index.php?action=deleteSession">
                                <button type="button" class="btn btn-danger">Disconnect</button>
                                </a>
                            </div>
                        </div>
                        
                    </form>
                </div>
                <div class="col-1"></div>

            </div>
            
            
            ';

        else :
            $this->content .= '
            <div class ="col-3">
                </div>
            <div class ="col-6">
                <form action="index.php?action=userLogOn" method="post">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="userMail">
                        <small id="emailHelp" class="form-text text-muted">We\'ll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="userPwd">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    ';
            if (isset($_SESSION['connexionLose'])) :
                $this->content .= '<h5>' . $_SESSION['connexionLose'] . '</h5>';
                unset($_SESSION['connexionLose']);
            endif;
            $this->content .= '
                    <button type="submit" class="btn btn-dark ">Submit</button> If you don\'t have an account, <a href="index.php?action=viewUserSignUp" class="">register !</a>
                </form>

            </div>
            <div class="col-3"></div>

        </div>';
        endif;
    }

    private function _getHtmlAfter()
    {
        return '
            </div>
        </header>
        ';
    }
}
