<?php
require_once('_defaultView.php');

use Models\UserManager;

class UserSignUpView extends _DefaultView
{

    private $userManager;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;

    private function __construct(UserManager $userManager)
    {

        $this->userManager = $userManager;
        $this->_getHtmlBefore();
        $this->_getContent();
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = parent::getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= parent::getFooter();
    }


    public static function render($userManager, $post_Id = null): void
    {
        $obj = new self($userManager);
        echo $obj->rendering;
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '<header>
                                <div class="container">
                                    <div class="row">
                                        <div class="card bg-light">';
    }


    private function _getContent()
    {
        $this->content = "";
        $this->content .=
            '<article class="card-body mx-auto" style="max-width: 1200px;">
                <p class="divider-text">
                    <span class="bg-light">Create an account</span>
                </p>';

        if (isset($_SESSION['userExist'])) :
            $this->content .= ' <div class="alert alert-danger" role="alert">
                ' . $_SESSION['userExist'] . '</div>';
            unset($_SESSION['userExist']);
        endif;
        $this->content .= '<form action="index.php?action=userSignUp" method="post">
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <input name="userLastName" class="form-control" placeholder="Gréard" type="text">Last Name
                                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                    </div>

                                </div> <!-- form-group// -->

                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <input name="userFirstName" class="form-control" placeholder="Lucas" type="text">First Name
                                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                    </div>

                                </div> <!-- form-group// -->

                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                                        <input name="userMail" class="form-control" placeholder="nobody@gmail.com" type="email"> Email Address
                                    </div>

                                </div> <!-- form-group// -->
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                                        <input name="userPhone" class="form-control" placeholder="06 66 66 66 66" type="text">Phone Number
                                    </div>

                                </div>
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                        <input name="userPwd" class="form-control" placeholder="********" type="password"> Your password
                                    </div>

                                </div> <!-- form-group// -->
                                <div class="form-group input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                        <input name="userPwd2" class="form-control" placeholder="********" type="password"> Verify your password
                                    </div>

                                </div> <!-- form-group// -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block"> Create Account </button>
                                </div> <!-- form-group// -->
                                <p class="text-center">Have an account? <a href="index.php?action=userPageConnect">Log In</a> </p>
                            </form>
                        </article>';
    }

    private function _getHtmlAfter()
    {
        return ' </div> <!-- card.// -->
            </div>
        </div>
    </header>';
    }
}
