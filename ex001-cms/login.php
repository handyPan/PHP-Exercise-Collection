<?php require_once("lib/db.php"); ?>
<?php require_once("lib/common.php"); ?>
<?php require_once("lib/sessions.php"); ?>
<?php require_once("lib/functions.php"); ?>
<?php
    if (isset($_POST["submit"])) {
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        if (empty($username) or empty($password)) {
            $_SESSION["errorMessage"] = "All fields can't be empty.";
            redirectTo("login.php");
        } else {
           $resultUser = login($username, $password);
           if ($resultUser) {
            $_SESSION["userLoggedIn"] = $resultUser;
            $_SESSION["successMessage"] = "Login successfully. Welcome, {$_SESSION["userLoggedIn"]["username"]}!";
            redirectTo("dashboard.php");
           } else {
            $_SESSION["errorMessage"] = "Login failed";
            redirectTo("login.php");
           } 
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- add the Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,600;1,400&family=Roboto:ital,wght@0,400;0,700;1,400&family=Lato:ital,wght@0,400;0,700;1,400&family=Rubik:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
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
                    <li><a href="about.php">About</a></li>
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
    <div class="container-fluid">
        <div class="row">
            <!-- main area begins -->
            <div class="col-sm-offset-4 col-sm-4" id="login-form">
                <div>
                    <?php 
                        echo errorMessage();
                        echo successMessage(); 
                    ?>
                </div>
                <h2>Welcome
                    <?php 
                        if (isset($_SESSION["userLoggedIn"])) {
                            echo ", {$_SESSION["userLoggedIn"]["username"]}";
                        }
                    ?>!
                </h2>
                <?php
                    if (isset($_SESSION["userLoggedIn"])) {
                ?>
                    <p class="lead">
                        Already logged in, <a href="logout.php">logout</a> or redirect to <a href="index.php">Home</a> in <span id="countdown">10</span> seconds.
                    </p>
                    <progress value="0" max="10" id="progressBar"></progress>
                <?php
                    } else {
                ?>
                    <div>
                        <form action="login.php" method="post">
                            <fieldset>
                                    <div class="form-group">
                                        <label for="username"><span class="field-info">Username:</span></label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-envelope text-secondary"></span></span>
                                            <input class="form-control" type="text" name="username" id="username" placeholder="username">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password"><span class="field-info">Password:</span></label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock text-secondary"></span></span>
                                            <input class="form-control" type="password" name="password" id="password" placeholder="password">
                                        </div>
                                    </div>
                                    <br>   
                                    <button class="btn btn-primary btn-block" type="Submit" name="submit">Login</button>
                                    <!-- <input class="btn btn-primary btn-block" type="Submit" name="Submit" value="Add New Category"> -->
                            </fieldset>
                            <br>
                        </form>
                    </div>
                <?php        
                    }
                ?>
            </div>
            <!-- main area ends -->
        </div> 
    </div>
    <!-- <div class="body-filler"></div> -->
    <!-- footer begins -->
    <div id="footer">
        <!-- <hr> -->
        <p>Created by Handy Pan &copy; 2022 All rights reserved.</p>
        <a href="#">Check out more projects</a>
        <!-- <hr> -->
    </div>
    <!-- footer ends -->
    <!-- <div id="footer-bottom"></div> -->

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/closeAlert.js"></script>
    <script src="js/countDown.js"></script>
</body>
</html>