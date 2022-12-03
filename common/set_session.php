<?php
global $conn;
include 'connect_db.php';
session_start();
if (isset($_SESSION['userId']) and isset($_SESSION['userName']) and isset($_SESSION['userEmail'])){
    die();
}else{
    $conn->prepare("select * from `users`");
}
