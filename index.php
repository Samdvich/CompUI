<?php
    session_start();

    if (isset($_SESSION['loggedin'])) {
        header("Location: summary.php");
    } else {
        header("Location: login.php");
    }
?>