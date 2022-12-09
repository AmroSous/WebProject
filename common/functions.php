<?php
require 'project.php';

function connectDB(){
    $conn = new PDO('mysql:host='.Project::DATABASE_HOSTNAME.';dbname='.Project::DATABASE_NAME
        , Project::DATABASE_USERNAME, Project::DATABASE_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

function test_input($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    return stripslashes($data);
}

function checkEmail($email){
    $errors = "";
    if (empty($email)){
        $errors .= "Email is required.";
    }elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $errors .= "Invalid email or password.<br/>";
    }elseif(!preg_match("/(@gmail.com)$/", $email)){
        $errors .= "Gmail account is required.<br/>";
    }
    return (empty($errors))? true : $errors;
}

function randomCode(){
    $random = "";
    for ($i = 0; $i < 4; $i++){
        $random .= rand(0, 9);
    }
    return $random;
}