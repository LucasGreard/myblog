<!DOCTYPE html>
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
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
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
                    <?php
                    echo isset($_SESSION['userFirstName']) ? $_SESSION['userFirstName'] : "World";
                    ?>
                    !
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
                        <?php
                        if (isset($_SESSION['userLastName'])) {
                        ?>


                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $_SESSION['userFirstName'] . " " . $_SESSION['userLastName']; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="index.php?action=userLogOn">My settings</a>
                            <a class="dropdown-item" href="index.php?action=listUserPosts">See your posts</a>
                            <?php
                            if (isset($_SESSION['userState']) && $_SESSION['userState'] === "Admin") {
                                echo '<a class="dropdown-item" href="index.php?action=listPostValidation">See post to accept</a>';
                            }
                            ?>
                            <a class="dropdown-item" href="index.php?action=deleteSession">Disconnect</a>
                        </div>
                    </li>
                <?php
                        } else {
                ?>
                    <a href="index.php?action=userConnect">Se connecter</a>
                <?php

                        }
                ?>
                </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>