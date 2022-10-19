<?php require_once("lib/sessions.php"); ?>
<?php require_once("lib/functions.php"); ?>
<?php
    $_SESSION["userLoggedIn"] = null;
    session_destroy();
    redirectTo("login.php");
?>
