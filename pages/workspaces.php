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

    <!-- styles -->
    <link rel="icon" type="image/x-icon" href="../images/appIcon.png"/>
    <link rel="stylesheet" href="../styles/workspace.css">

    <title>Workspaces</title>
</head>
<body>
<!--Navigation bar-->
<?php // include '../common/nav_bar.php'; ?>

<!-- Workspaces elements here -->

    <nav class="nav">
        <div id="navName">Work spaces</div>
    </nav>
    <div class="content">
        <div class="video-wrapper">
            <video playsinline autoplay muted loop poster>
                <source src="../images/video.mp4" type="video/mp4">
            </video>
        </div>
        <div class="wsName">

            <button class="workspaces" onclick="document.getElementById('listt').style.visibility='visible'">Workspace one</button>
            <ul class="hh" id="listt" >
                <li >
                    <a class="card" href="http://localhost:63342/WebProject/pages/home.php"  >board one</a>
                </li>
                <li>
                    <a href="https://www.youtube.com/results?search_query=card+animation+in+css" target="_blank" class="card">board two</a>
                </li>
            </ul>
            <button class="workspaces" onclick="document.getElementById('l2').style.visibility='visible'">Workspace two</button>
            <ul id="l2">
                <li>
                    <a class="card" href="https://www.youtube.com">board one</a>
                </li>
                <li>
                    <a class="card">board two</a>
                </li>
            </ul>
        </div>



    </div>

    <div class="sidebar">
        <span>sidebar</span>
        <!--<ul>-->
        <!--    <ul class="work_sidebar">-->
        <!--       <li >board1</li>-->
        <!--        <li>board2</li>-->
        <!--    </ul>-->
        <!--    <li>hi</li>-->
        <!--</ul>-->
        <div class="w3-sidebar w3-bar-block w3-light-grey w3-card" style="width:160px;">
            <a href="#" class="w3-bar-item w3-button">Link 1</a>
            <button class="w3-button w3-block w3-left-align" onclick="myAccFunc()">
                Accordion <i class="fa fa-caret-down"></i>
            </button>
            <div id="demoAcc" class="w3-hide w3-white w3-card">
                <a href="#" class="w3-bar-item w3-button">Link</a>
                <a href="#" class="w3-bar-item w3-button">Link</a>
            </div>

            <div class="w3-dropdown-click">
                <button class="w3-button" onclick="myDropFunc()">
                    Dropdown <i class="fa fa-caret-down"></i>
                </button>
                <div id="demoDrop" class="w3-dropdown-content w3-bar-block w3-white w3-card">
                    <a href="#" class="w3-bar-item w3-button">Link</a>
                    <a href="#" class="w3-bar-item w3-button">Link</a>
                </div>
            </div>
            <a href="#" class="w3-bar-item w3-button">Link 2</a>
            <a href="#" class="w3-bar-item w3-button">Link 3</a>
        </div>



    </div>
    <script>
        function myAccFunc() {
            var x = document.getElementById("demoAcc");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
                x.previousElementSibling.className += " w3-green";
            } else {
                x.className = x.className.replace(" w3-show", "");
                x.previousElementSibling.className =
                    x.previousElementSibling.className.replace(" w3-green", "");
            }
        }

        function myDropFunc() {
            var x = document.getElementById("demoDrop");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
                x.previousElementSibling.className += " w3-green";
            } else {
                x.className = x.className.replace(" w3-show", "");
                x.previousElementSibling.className =
                    x.previousElementSibling.className.replace(" w3-green", "");
            }
        }
    </script>
    <a href="https://www.youtube.com">ssssssssss</a>

</body>
</html>