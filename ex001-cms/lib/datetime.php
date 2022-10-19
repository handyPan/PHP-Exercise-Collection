<?php
    date_default_timezone_set("America/Toronto");
    $currentTime = time();
    $dateTime = strftime("%Y-%m-%d %H:%M:%S", $currentTime);
    echo $dateTime;
    echo "<br>";
    $dateTime = strftime("%B-%d-%Y %H:%M:%S", $currentTime);
    echo $dateTime;
?>