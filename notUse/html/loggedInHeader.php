<header>
    <div class="container">
        <div class="row">
            <div class="card mb-12" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="public/img/profile.png" class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $_SESSION['userFirstName'] . " " . $_SESSION['userLastName']; ?></h5>
                            <p class="card-text">Phone : <?php echo $_SESSION['userPhone']; ?></p>
                            <p class="card-text">Email : <?php echo $_SESSION['userMail']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <a href="index.php?action=deleteSession">
                <button type="button" class="btn btn-danger">Se déconnecter</button>
            </a>
        </div>
        <div class="row">

        </div>
    </div>
</header>



