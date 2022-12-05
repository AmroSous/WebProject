<?php
session_start();

include '../common/project.php';

// if the user signed in go to workspaces
if (!(isset($_SESSION['validUser']) and $_SESSION['validUser'])){
    header('location: home.php');
}
?>

<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <base href="signup.php"/>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="../images/appIcon.png"/>

    <title>Workspaces</title>
</head>
<body>
<!--Navigation bar-->
<?php include '../common/nav_bar.php'; ?>

<div class="container">
    <div class="workspace">

    </div>
    <div class="addWorkspace">

    </div>
</div>

</body>
</html>