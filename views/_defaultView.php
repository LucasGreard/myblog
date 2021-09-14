<?php

use Models\HomeManager;
use Models\PostManager;

class _DefaultView
{
    private $homeManager;
    private $postManager;
    private $htmlBefore;
    private $content;
    private $htmlAfter;
    public $rendering;

    private function __construct(HomeManager $homeManager = null, $post_Id = null, PostManager $postManager = null)
    {

        $this->homeManager = $homeManager;
        $this->postManager = $postManager;
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
        $header = '
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
                    <meta name="description" content="" />
                    <meta name="author" content="Gréard Lucas" />
                    <title>Lucas Gréard - Projet 5 : Créez votre premier blog en PHP</title>
                    <!-- Favicon-->
                    <link rel="icon" type="image/x-icon" href="public/img/favicon.ico" />

                    <!-- Core theme CSS -->
                    <link href="public/css/style.css" rel="stylesheet" />
                    <link href="public/css/bootstrap.css" rel="stylesheet" />
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
                </head>
                <body>
                <!-- Responsive navbar-->
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <div class="container">
                        <a class="navbar-brand" href="index.php">Hi, 
                        ';
        if (isset($_SESSION['userFirstName'])) :
            $header .= $_SESSION['userFirstName'] . " !";
        else :
            $header .= "World !";
        endif;

        $header .= '
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon">
                            </span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?action=listPost">See posts</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?action=contactMe">Contact me</a>
                                </li>';
        if (isset($_SESSION['userLastName'])) :

            $header .= '        <li class="nav-item dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        ' . $_SESSION['userFirstName'] . " " . $_SESSION['userLastName'] . '
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <a class="dropdown-item" href="index.php?action=userLogOn">My settings</a>';

            // A ajouter si les USERS peuvent ajouter un post   
            //<div class="dropdown-divider"></div>
            // <a class="dropdown-item" href="index.php?action=listUserPosts">See your posts</a> 
            if ($_SESSION['userState'] != "Guest") :
                $header .= '                <a class="dropdown-item" href="index.php?action=userComments">My comments</a>';
            endif;
            if (isset($_SESSION['userState']) && $_SESSION['userState'] === "Admin") :

                //A ajouter si les USERS peuvent ajouter un post
                // $header .= '<a class="dropdown-item" href="index.php?action=listPostValidation">See POST to manage</a>
                //             <div class="dropdown-divider"></div>';


                $header .= '            <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="index.php?action=listCommentValidation">Manage Comments</a>
                                        ';
                $header .= '            <a class="dropdown-item" href="index.php?action=listUserManage">Manage Users</a>
                                        <div class="dropdown-divider"></div>   
                                        ';
                $header .= '            <a class="dropdown-item" href="index.php?action=managePostAdmin">Manage Posts</a>
                                        <div class="dropdown-divider"></div>   
                                        ';
            endif;

            $header .= '                <a class="dropdown-item link-danger" href="index.php?action=deleteSession">Disconnect</a>
                                    </ul>
                                </li>';
        else :
            $header .= '        <li class="nav-item">
                                    <a class="nav-link " href="index.php?action=userConnect">Sign On</a>
                                </li>';
        endif;
        $header .= ' 
                            </ul>
                        </div>
                    </div>
                </nav>
        ';

        return $header;
    }

    public function getFooter()
    {
        $footer = '
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container">
                <div class="row text-center">
                    <div class="footer-col col-md-4">
                        <h3 class="text-white">Location</h3>
                        <p class="text-white">
                            Mesmeniers
                            <br>Vezin-le-Coquet, 35132 Île-et-Villaine
                        </p>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3 class="text-white">Around the Web</h3>
                        <div class="center">
                            <div class="social-buttons">
                                <a href="Mck#4209"><i class="fab fa-discord"></i></a>
                                <a href="https://twitter.com/Lululatortue50"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.facebook.com/lucas.greard/"><i class="fab fa-facebook"></i></a>
                                <a href="https://www.youtube.com/channel/UCVQ98Fiprx8M40gem_mvmpg"><i class="fab fa-youtube"></i></a>
                                <a href="https://www.linkedin.com/in/lucas-gr%C3%A9ard-09a046169/"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3 class="text-white">About You</h3>
                        <ul class="list-group">';
        if (isset($_SESSION['VerifConnection'])) :
            $footer .= '
                            <p>
                                <a class="btn btn-outline-info" href="index.php?action=userLogOn">My Account,' . $_SESSION['userLastName'] . '</a>
                            </p>
                            <p>
                                <a class="btn btn-outline-danger" href="index.php?action=deleteSession">Disconnect</a>
                            </p>';
        else :
            $footer .= '
                            <p>
                                <a class="btn btn-outline-success" href="index.php?action=userConnect">Se connecter</a>
                            </p>';
        endif;
        $footer .= '                
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="public/js/scripts.js"></script>
        </body>
        </html>
        ';
        return $footer;
    }

    public static function render($homeManager, $post_Id = null, $postManager = null): void
    {
        $obj = new self($homeManager, $post_Id, $postManager);
        echo $obj->rendering;
    }

    private function _getHtmlBefore(): void
    {
        $this->htmlBefore = '
        ';
    }
    private function _getContent()
    {

        $listHome = $this->homeManager->listHome();

        if ($data = $listHome->fetch()) :
            $this->content .= '
            
            <!-- Page content-->
            <div class="container mt-5">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Post content-->
                        <article>
                            <!-- Post header-->
                            <header class="mb-4 text-center">
                                <!-- Post title-->
                                <h1 class="fw-bolder mb-1">Welcome to MY LIFE ! </h1> 
                                <!-- Post meta content-->
                                <div class="text-muted fst-italic mb-2">Borned on ' . $data['birthday'] . '   </div>
                                <!-- Post categories-->
                                <div class="badge bg-secondary text-decoration-none link-light" href="#!">' . $data['skill_1'] . '</div>
                                <div class="badge bg-secondary text-decoration-none link-light" href="#!">' . $data['skill_2'] . '</div>
                            </header>
                            <!-- Preview image figure-->
                            <figure class="mb-4 text-center">
                                <img class="img-fluid rounded" src="public/img/Greard_Lucas_profile.png" alt="Greard Lucas portrait" /></figure>
                            <!-- Post content-->
                            <section class="mb-5">
                                <p class="fs-5 mb-4 fst-italic text-center">' . $data['firstname'] . ' ' . $data['lastname'] . '</p>

                                <p class="fs-5 text-center "> <a class="btn btn-outline-info" href="public/doc/cvGreardLucas.pdf" download>Download Curriculum Vitae </a> </p>

                                <p class="fs-5 mb-4">The universe is large and old, and the ingredients for life as we know it are everywhere, so there\'s no reason to think that Earth would be unique in that regard. Whether of not the life became intelligent is a different question, and we\'ll see if we find that.</p>
                                <p class="fs-5 mb-4">If you get asteroids about a kilometer in size, those are large enough and carry enough energy into our system to disrupt transportation, communication, the food chains, and that can be a really bad day on Earth.</p>
                                <h2 class="fw-bolder mb-4 mt-5 text-center">I have odd cosmic thoughts every day</h2>
                                <p class="fs-5 mb-4">For me, the most fascinating interface is Twitter. I have odd cosmic thoughts every day and I realized I could hold them to myself or share them with people who might be interested.</p>
                                <p class="fs-5 mb-4">Venus has a runaway greenhouse effect. I kind of want to know what happened there because we\'re twirling knobs here on Earth without knowing the consequences of it. Mars once had running water. It\'s bone dry today. Something bad happened there as well.</p>

                                <p class="fs-5 mb-4 fst-italic text-center">' . $data['catch_Phrase'] . '</p>
                                

                              
                            </section>
                            <section class="mb-5">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-6">
                                                <div class="fst-italic text-center">
                                                    Frontend
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                                                    </div>
                                                </div>
                                        </div>

                                        <div class="col-lg-6">
                                                <div class="fst-italic text-center">
                                                    Web Design
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </article>
                        ';

            $this->content .= '
                    </div>
                    <!-- Side widgets-->
                    <div class="col-lg-4">
                        <!-- Categories widget-->
                        <div class="card mb-4">
                            <div class="card-header">Categories</div>
                            <div class="card-body">
                                <div class="row">
                                ';

        endif;
        $this->homeManager = new PostManager();
        $listHome = $this->homeManager->postListCategory();

        while ($data = $listHome->fetch()) :
            $this->content .= '                    
                                    <div class="col-sm-2">
                                        <a class="badge bg-secondary text-decoration-none link-light" href="#!">' . $data['post_Category'] . '</a>   
                                    </div>';
        endwhile;
        $this->content .= '                              
                            </div>
                        </div>
                    </div>';

        $this->content .= '
                                                    <!-- Side widget-->
                    <div class="card mb-4">
                        <div class="card-header">Last Article</div>
                        <div class="card-body">';

        $listHome = $this->homeManager->lastPostCreate();
        while ($data = $listHome->fetch()) :
            $this->content .= '                    
                        <div class="card" style="width: 18rem;">
                            
                        <div class="card-body">
                            <h5 class="card-title">' . $data['post_Heading'] . '</h5>
                            <p class="card-text">' . $data['post_Chapo'] . '</p>
                            <a href="index.php?action=listComment&id=' . $data['id'] . '" class="badge bg-secondary link-light text-center">See post</a>
                        </div>
                    </div>';
        endwhile;


        $this->content .= '
                        </div>
                    </div>
                </div>
            </div>
        
            ';
    }

    private function _getHtmlAfter()
    {
        return '
            </div>
        ';
    }
}
