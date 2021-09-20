<?php
require_once('_defaultView.php');

class UserSignUpView extends _DefaultView
{

    private $userManager;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;

    private function __construct($userManager, $sessionError)
    {

        $this->userManager = $userManager;
        $this->sessionError = $sessionError;
        $this->_getHtmlBefore();
        $this->_getContent();
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = parent::getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= parent::getFooter();
    }


    public static function render($userManager, $sessionError = null): void
    {
        $obj = new self($userManager, $sessionError);
        print_r($obj->rendering);
    }


    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        <header class="py-5 bg-light border-bottom mb-4">
            <div class="container">
                <div class="text-center my-5">
                    <h1 class="fw-bolder">It\'s time to register ! </h1>
                    <p class="lead mb-0 fst-italic">We only live once, so do what you want and register !</p>
                </div>
            </div>
        </header>';
    }


    private function _getContent()
    {
        $this->content = "";

        $this->content .= isset($this->sessionError) ? '<div class="text-center" id="alert">' . $this->sessionError . '</div>' : false;
        $this->content .=
            '
            <div class="container">
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-8 text-center">
                        <form action="index.php?action=userSignUp" method="post">
                            
                            <div class="input-group-prepend">
                                Last Name<input name="userLastName" class="form-control" placeholder="Gréard" type="text">
                            </div>
                    
                            
                            <div class="input-group-prepend">
                                First Name<input name="userFirstName" class="form-control" placeholder="Lucas" type="text">                  
                            </div> 
                    
                            
                            <div class="input-group-prepend">
                                Email Address<input name="userMail" class="form-control" placeholder="nobody@gmail.com" type="email"> 
                            </div>
                    
                            
                            
                            <div class="input-group-prepend">
                                Phone Number<input name="userPhone" class="form-control" placeholder="06 66 66 66 66" type="text">
                            </div>
                    
                            
                            
                            <div class="input-group-prepend">
                                Your password<input name="userPwd" class="form-control" placeholder="********" type="password"> 
                            </div>
                    
                            
                            
                            <div class="input-group-prepend">
                                Verify your password<input name="userPwd2" class="form-control" placeholder="********" type="password"> 
                            </div>
                    
                            <div class="form-group">
                                <button type="submit" class="btn btn-outline-success"> Create Account </button>
                            </div> 
                            <p class="text-center">Have an account? <a href="index.php?action=userPageConnect">Log In</a> </p>
                        </form>
                    </div>
                    <div class="col-2"></div>
                </div>
            </div>
            ';
    }

    private function _getHtmlAfter()
    {
        return ' 
        
        ';
    }
}
