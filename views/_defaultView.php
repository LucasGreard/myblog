<?php

use Models\HomeManager;

class _DefaultView
{
    private $homeManager;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;

    private function __construct(HomeManager $homeManager, $post_Id)
    {

        $this->homeManager = $homeManager;
        $this->_getHtmlBefore();
        $this->_getContent($homeManager, $post_Id);
        $this->htmlAfter = $this->_getHtmlAfter();

        $this->rendering = $this->getHeader();
        $this->rendering .= $this->htmlBefore;
        $this->rendering .= $this->content;
        $this->rendering .= $this->htmlAfter;
        $this->rendering .= $this->getFooter();
    }

    public function getHeader()
    {
        $header = '<!DOCTYPE html>
                    <html lang="en">
                    
                    <head>
                    
                        <meta charset="utf-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <meta name="description" content="Welcome to my entirely handmade site ! Have a good trip !">
                        <meta name="author" content="Gréard Lucas">
                    
                        <title>Entirely handmade site of a developper</title>
                    
                        <!-- Bootstrap Core CSS -->
                        <link href="public/css/bootstrap.min.css" rel="stylesheet">
                    
                        <!-- Theme CSS -->
                        <link href="public/css/freelancer.css" rel="stylesheet">
                    
                        <!-- Custom Fonts -->
                        <link href="public/font/font-awesome/font-awesome.css" rel="stylesheet" type="text/css">
                        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
                        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
                    
                        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                        <!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
                        <!--[if lt IE 9]>
                            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
                        <![endif]-->
                    
                    </head>
                    
                    <body id="page-top" class="index">
                    
                        <!-- Navigation -->
                        <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
                            <div class="container">
                                <!-- Brand and toggle get grouped for better mobile display -->
                                <div class="navbar-header page-scroll">
                                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                        <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                                    </button>
                    
                                    <a class="navbar-brand" href="index.php">Hi, 
                                    ';

        if (isset($_SESSION['userFirstName'])) :
            $header .= $_SESSION['userFirstName'];
        else :
            $header .= "World";
        endif;

        $header .= '                    !
                                    </a>
                                </div>
                    
                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                    <ul class="nav navbar-nav navbar-right">
                                        <li class="hidden">
                                            <a href="#page-top"></a>
                                        </li>
                                        <li class="page-scroll">
                                            <a href="index.php?action=listPost">See posts</a>
                                        </li>
                                        <li class="page-scroll">
                                            <a href="index.php?action=contactMe">Contact me</a>
                                        </li>
                                        <li class="page-scroll">';

        if (isset($_SESSION['userLastName'])) :

            $header .= '        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                ' . $_SESSION['userFirstName'] . " " . $_SESSION['userLastName'] . '
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                <a class="dropdown-item" href="index.php?action=userLogOn">My settings</a>';

            // A ajouter si les USERS peuvent ajouter un post   
            //<div class="dropdown-divider"></div>
            // <a class="dropdown-item" href="index.php?action=listUserPosts">See your posts</a> 

            $header .= '                     <div class="dropdown-divider"></div>';
            $header .= '<a class="dropdown-item" href="index.php?action=userComments">My comments</a>';

            if (isset($_SESSION['userState']) && $_SESSION['userState'] === "Admin") :

                //A ajouter si les USERS peuvent ajouter un post
                // $header .= '<a class="dropdown-item" href="index.php?action=listPostValidation">See POST to manage</a>
                //             <div class="dropdown-divider"></div>';


                $header .= '<a class="dropdown-item" href="index.php?action=listCommentValidation">See COMMENT to manage</a>
                            <div class="dropdown-divider"></div>';
                $header .= '<a class="dropdown-item" href="index.php?action=listUserManage">See USER to manage</a>
                            <div class="dropdown-divider"></div>   ';
            endif;

            $header .= '                <a class="dropdown-item" href="index.php?action=deleteSession">Disconnect</a>
                                            </div>
                                        </li>';
        else :
            $header .= '<a href="index.php?action=userConnect">Sign On</a>';
        endif;

        $header .= '                </li>
                                    </ul>
                                </div>
                                <!-- /.navbar-collapse -->
                            </div>
                            <!-- /.container-fluid -->
                        </nav>';

        return $header;
    }

    public function getFooter()
    {
        $footer = '<!-- Footer -->
                    <footer class="text-center">
                        <div class="footer-above">
                            <div class="container">
                                <div class="row">
                                    <div class="footer-col col-md-4">
                                        <h3>Location</h3>
                                        <p>Mesmeniers
                                            <br>Vezin-le-Coquet, 35132 Île-et-Villaine
                                        </p>
                                    </div>
                                    <div class="footer-col col-md-4">
                                        <h3>Around the Web</h3>
                                        <ul class="list-inline">
                                            <li>
                                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-facebook"></i></a>
                                            </li>
                                            <li>
                                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-twitter"></i></a>
                                            </li>
                                            <li>
                                                <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-linkedin"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="footer-col col-md-4">
                                        <h3>About You</h3>
                                        <ul class="list-group">';
        if (isset($_SESSION['VerifConnection'])) :
            $footer .= '<p>
                                <a href="index.php?action=userLogOn">My Account,' . $_SESSION['userLastName'] . '</a>
                            </p>
                            <p>
                                <a href="index.php?action=deleteSession">Disconnect</a>
                            </p>';
        else :
            $footer .= '<p><a href="index.php?action=userConnect">Se connecter</a></p>';
        endif;
        $footer .= '                </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-below">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        Copyright &copy; Your Website 2016
                                    </div>
                                </div>
                            </div>
                        </div>
                    </footer>
                
                    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
                    <div class="scroll-top page-scroll hidden-sm hidden-xs hidden-lg hidden-md">
                        <a class="btn btn-primary" href="#page-top">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                
                    <!-- jQuery -->
                    <script src="public/jquery/jquery.js"></script>
                
                    <!-- Bootstrap Core JavaScript -->
                    <script src="public/js/bootstrap.js"></script>
                
                    <!-- Plugin JavaScript -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
                
                    <!-- Contact Form JavaScript -->
                    <script src="public/js/jqBootstrapValidation.js"></script>
                    <script src="public/js/contact_me.js"></script>
                
                    <!-- Theme JavaScript -->
                    <script src="public/js/freelancer.js"></script>
                
                    </body>
                
                    </html>';
        return $footer;
    }

    public static function render($homeManager, $post_Id = null): void
    {
        $obj = new self($homeManager, $post_Id);
        echo $obj->rendering;
    }

    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        ';
    }
    private function _getContent($homeManager, $post_id)
    {

        $listHome = $this->homeManager->listHome();

        if ($donnee = $listHome->fetch()) :
            $this->content .= '
            <!-- Header -->
            <header>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <img class="img-responsive" src="public/img/Greard_Lucas_profile.png" alt="">
                            <div class="intro-text">
                                <span class="name">' . $donnee['firstname'] . ' ' . $donnee['lastname'] . '</span>
                                <hr class="star-light">
                                <span class="skills">' . $donnee['catch_Phrase'] . ' </span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
    
            <!-- About Section -->
            <section class="success" id="about">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <h2>About</h2>
                            <hr class="star-light">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-2">
                            <p>' . $donnee['skill_1'] . '</p>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">60%</div>
                            </div>
                            <p>' . $donnee['skill_2'] . '</p>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 40%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">40%</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <p>' . $donnee['description'] . '</p>
                        </div>
    
                    </div>
                </div>
            </section>';
        endif;
    }

    private function _getHtmlAfter()
    {
        return '';
    }
}
