<?php require_once("lib/db.php"); ?>
<?php require_once("lib/common.php"); ?>
<?php require_once("lib/sessions.php"); ?>
<?php
    function redirectTo($newLocation) {
        header("Location:".$newLocation);
        exit;
    }

    function login($username, $password) {
        global $conn;
        $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $execute = mysqli_query($conn, $query);
        if ($user=mysqli_fetch_assoc($execute)) {
            return $user;
        } else {
            return null;
        }
    }

    function checkLoginState() {
        if(!isset($_SESSION["userLoggedIn"])) {
            $_SESSION["errorMessage"] = "Login required";
            redirectTo("login.php");
        }
    }

    function showNavbarProfile() {
        if (isset($_SESSION["userLoggedIn"])) {
            // $output =  "<span class=\"navbar-profile\">Welcome, " . $_SESSION["userLoggedIn"]["username"] . "!</span>";
            $output = "<div class=\"dropdown pull-left navbar-welcome\">";
            $output .= "<div class=\"pull-left navbar-welcome-welcome\">Welcome,&nbsp;</div><button class=\"btn btn-default dropdown-toggle\" type=\"button\" id=\"dropdownMenu1\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\">";
            $output .=  $_SESSION["userLoggedIn"]["username"] . " ";
            $output .= "<span class=\"caret\"></span>";
            $output .= "</button>";
            $output .= "<ul class=\"dropdown-menu\" aria-labelledby=\"dropdownMenu1\">";
            $output .= "<li><a href=\"dashboard.php\">Dashboard</a></li>";
            $output .= "<li><a href=\"addPost.php\">Add Post</a></li>";
            $output .= "<li role=\"separator\" class=\"divider\"></li>";
            $output .= "<li><a href=\"logout.php\">Logout</a></li>";
            $output .= "</ul>";
            $output .= "</div>";
        } else {
            $output =  "<div class=\"pull-left navbar-welcome navbar-welcome-welcome\"><a href=\"login.php\">Login</a></div>";
        }

        echo $output;
    }
?>