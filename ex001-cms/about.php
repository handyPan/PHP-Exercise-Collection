<?php require_once("lib/db.php"); ?>
<?php require_once("lib/sessions.php"); ?>
<?php require_once("lib/functions.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <!-- add the Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;1,400&family=Roboto:ital,wght@0,400;0,700;1,400&family=Lato:ital,wght@0,400;0,700;1,400&family=Rubik:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- <div id="page-top"></div> -->
    <!-- navigation bar begins -->
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a href="index.php" class="navbar-brand"><img src="img/Blog_pic.png"></a>
                <div id="filler"></div>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="collapse">
            <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li class="active"><a href="about.php">About</a></li>
                    <?php
                        if (isset($_SESSION["userLoggedIn"])) {
                            echo "<li><a href=\"dashboard.php\">Dashboard</a></li>";
                        }
                    ?>
                </ul>
                <form action="index.php" class="navbar-form navbar-right" method="get">
                    <?php showNavbarProfile(); ?>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search something..." name="search">
                    </div>
                    <button class="btn btn-primary" type="Submit" name="btnSearch">Go!</button>
                </form>
            </div>
        </div>
    </nav>
    <!-- navigation bar ends -->
    <!-- <div id="page-top"></div> -->
    <div class="container-fluid container-post about-main-area">
        <div class="row blog-header">
            <h1>About this project</h1>
            <p class="lead">This project is to practice the development of a Blog CMS system using PHP.</p>
        </div>

        <div class="row">
            <!-- main area begins -->
            <h2>Used Tools</h2>
            <p class="lead">IDE: Visual Studio Code</p>
            <p class="lead">Server: XAMPP - Apache/MySQL</p>
            <p class="lead">Front-End: HTML/CSS/JavaScript/jQuery/Bootstrap</p>
            <p class="lead">Back-End: PHP</p>

            <h2>Todo</h2>
            <ul>
                <li>
                    <p class="lead">Develop the landing page and other critical pages for business presence.</p>
                </li>
                <li>
                    <p class="lead">Improve the dashboard.</p>
                </li>
                <li>
                    <p class="lead">Build the profile page.</p>
                </li>
                <li>
                    <p class="lead">Change the "user" table and the comments on "Read Blog" page.</p>
                </li>
            </ul>
            <!-- main area ends -->
        </div>
    </div>

    <!-- footer begins -->
    <div id="footer">
        <!-- <hr> -->
        <p>Created by Handy Pan &copy; 2022 All rights reserved.</p>
        <a href="#">
            Check out more projects
        </a>
        <!-- <hr> -->
    </div>
    <!-- footer ends -->
    <!-- <div id="footer-bottom"></div> -->

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/closeAlert.js"></script>
</body>
</html>