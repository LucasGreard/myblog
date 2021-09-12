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


    public static function render($userConnect, $post_Id = null): void
    {
        $obj = new self($userConnect);
        echo $obj->rendering;
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <header>
            <div class="container">
                <div class="row">';
    }


    private function _getContent()
    {
        $this->content = "";
        if (isset($_SESSION['VerifConnection'])) :
            $this->content .= '
                    <div class="card mb-12" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <img src="public/img/profile.png" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">' . $_SESSION['userFirstName'] . " " . $_SESSION['userLastName'] . '</h5>
                                    <p class="card-text">Phone : ' . $_SESSION['userPhone'] . '</p>
                                    <p class="card-text">Email : ' . $_SESSION['userMail'] . '</p>
                                </div>
                            </div>
                        </div>
                    </div>              

                    <a href="index.php?action=deleteSession">
                        <button type="button" class="btn btn-danger">Disconnect</button>
                    </a>
                </div>';
            $this->content .= '
            <div class="row">Modify your coordonnees
                <form action="index.php?action=modifyCoorUser" method="post">
                    <div class="form-group input-group">

                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                            <input name="userLastName" class="form-control" value="' . $_SESSION['userLastName'] . '" type="text">Last Name
                        </div>
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                            <input name="userFirstName" class="form-control" value="' . $_SESSION['userFirstName'] . '" type="text">First Name
                        </div>
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                            <input name="userPhone" class="form-control" value="' . $_SESSION['userPhone'] . '" type="text">Phone Number
                        </div>
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                            <input name="userMail" class="form-control" value="' . $_SESSION['userMail'] . '" type="email"> Email Address
                        </div>

                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                <input name="userPwd" class="form-control" placeholder="********" type="password"> Your actually password
                            </div>
                        </div> 

                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                <input name="userPwdModif1" class="form-control" placeholder="********" type="password"> Your new password
                            </div>
                        </div> 

                        <div class="form-group input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                            <input name="userPwdModif2" class="form-control" placeholder="********" type="password"> Verify your new password
                        </div>

                        <button type="submit" class="btn btn-warning">Save information</button>
                    </div>
                    
                </form>
            </div>
            
            
            ';

        else :
            $this->content .= '
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
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            <div class="col-lg-6">
                <ul class="list-group">

                    <a href="index.php?action=viewUserSignUp" class="list-group-item active">S\'inscrire</a>
                </ul>
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
