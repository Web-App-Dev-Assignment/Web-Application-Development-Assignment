<?php
    session_start();
    session_destroy();
    header("Location: ../page/index.php");
    exit;
?>