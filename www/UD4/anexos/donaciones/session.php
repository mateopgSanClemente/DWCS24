<?php
session_start();
if (!isset($_SESSION['admin'])) {	
    header("Location: login.php?redirect=true");
    exit();
}
?>