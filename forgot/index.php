<?php
session_start();

// if the user signed in go to workspaces
if (isset($_SESSION['validUser']) and $_SESSION['validUser']){
    header('location: home.php');
}