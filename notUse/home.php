<?php
require('views/header.php')
?>

<?php



while ($donnee = $listHome->fetch()) {


?>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <img class="img-responsive" src="public/img/Greard_Lucas_profile.png" alt="">
                    <div class="intro-text">
                        <span class="name"><?php echo $donnee['firstname'] ?> <?php echo $donnee['lastname']; ?></span>
                        <hr class="star-light">
                        <span class="skills"><?php echo $donnee['catch_Phrase']; ?> </span>
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
                    <p><?php echo $donnee['skill_1'] ?></p>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">60%</div>
                    </div>
                    <p><?php echo $donnee['skill_2'] ?></p>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 40%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">40%</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <p><?php echo $donnee['description'] ?></p>
                </div>

            </div>
        </div>
    </section>
<?php
}
require('views/footer.php');
?>