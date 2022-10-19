<?php 
    session_start();
    function errorMessage() {
        if (isset($_SESSION["errorMessage"])) {
            $output = "<div class=\"msg alert alert-danger alert-dismissible\" role=\"alert\">";
            $output .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
            $output .= htmlentities($_SESSION["errorMessage"]);
            $output .= "</div>";
            $_SESSION["errorMessage"]=null;
            return $output;
        }
    }
    function successMessage() {
        if (isset($_SESSION["successMessage"])) {
            $output = "<div class=\"msg alert alert-success alert-dismissible\" role=\"alert\">";
            $output .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
            $output .= htmlentities($_SESSION["successMessage"]);
            $output .= "</div>";
            $_SESSION["successMessage"]=null;
            return $output;
        }
    }
?>