<?php require_once("lib/db.php"); ?>
<?php require_once("lib/sessions.php"); ?>
<?php require_once("lib/functions.php"); ?>

<?php
    if (isset($_GET["id"])) {
        $commentIdFromUrl = $_GET["id"];
        $commentStatusFromUrl = $_GET["status"];
        if ($commentStatusFromUrl!='ON' and $commentStatusFromUrl!='OFF') {
            $_SESSION["errorMessage"] = "Comment failed to approve.";
            redirectTo("comments.php");
        }
        global $conn;
        $user = $_SESSION["userLoggedIn"]["username"];
        $query = "UPDATE comment SET status='$commentStatusFromUrl', approvedBy='$user' WHERE id='$commentIdFromUrl'";
        $execute = mysqli_query($conn, $query);
        if ($execute) {
            $_SESSION["successMessage"]="Comment" . (($commentStatusFromUrl=='ON') ? " approved ":" unapproved ") . "successfully.";
            redirectTo("comments.php");
        } else {
            $_SESSION["errorMessage"] = "Comment failed to approve.";
            redirectTo("comments.php");
        }
    }

?>
