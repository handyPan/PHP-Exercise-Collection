<?php require_once("lib/db.php"); ?>
<?php require_once("lib/common.php"); ?>
<?php require_once("lib/sessions.php"); ?>
<?php require_once("lib/functions.php"); ?>
<?php
    if (isset($_GET["id"])) {
        $userIdFromUrl = $_GET["id"];
        global $conn;
        $query = "DELETE FROM user WHERE id='$userIdFromUrl'";
        $execute = mysqli_query($conn, $query);
        if ($execute) {
            $_SESSION["successMessage"]="Admin deleted successfully.";
            redirectTo("admins.php");
        } else {
            $_SESSION["errorMessage"] = "Admin failed to delete.";
            redirectTo("admins.php");
        }
    }
?>
