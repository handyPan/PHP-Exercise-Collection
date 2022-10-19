<?php require_once("lib/db.php"); ?>
<?php require_once("lib/common.php"); ?>
<?php require_once("lib/sessions.php"); ?>
<?php require_once("lib/functions.php"); ?>
<?php
    if (isset($_GET["id"])) {
        $categoryIdFromUrl = $_GET["id"];
        global $conn;
        $query = "DELETE FROM category WHERE id='$categoryIdFromUrl'";
        $execute = mysqli_query($conn, $query);
        if ($execute) {
            $_SESSION["successMessage"]="Category deleted successfully.";
            redirectTo("categories.php");
        } else {
            $_SESSION["errorMessage"] = "Category failed to delete.";
            redirectTo("categories.php");
        }
    }
?>
