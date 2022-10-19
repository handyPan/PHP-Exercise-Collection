<?php require_once("lib/db.php"); ?>
<?php require_once("lib/sessions.php"); ?>
<?php require_once("lib/functions.php"); ?>

<?php
    if (isset($_GET["id"])) {
        $commentIdFromUrl = $_GET["id"];
        global $conn;
        $query = "DELETE FROM comment WHERE id='$commentIdFromUrl'";
        $execute = mysqli_query($conn, $query);
        if ($execute) {
            $_SESSION["successMessage"]="Comment deleted successfully.";
            redirectTo("comments.php");
        } else {
            $_SESSION["errorMessage"] = "Comment failed to delete.";
            redirectTo("comments.php");
        }
    }

?>
