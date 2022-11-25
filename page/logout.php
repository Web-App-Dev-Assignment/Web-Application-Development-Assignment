<?php
//might turn into page

    session_start();
    session_destroy();
    header("Location: index.php");
    exit;
?>