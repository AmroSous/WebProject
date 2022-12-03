<?php
session_start();

include '../common/project.php';

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
    <link rel="stylesheet" type="text/css" href="../styles/loginStyle.css"/> <!--style for login page-->
    <link rel="stylesheet" type="text/css" href="../styles/homeStyle.css">
    <title>Home</title>
</head>
<body>
<?php include '../common/nav_bar.php'; ?>

<div class="header">

    <!--Content before waves-->
    <div class="inner-header flex">
        <!--Just the logo... Don't mind this-->
</svg>
        <h1 class="title"><?= Project::PROJ_NAME ?></h1>
        <h2 id="des2">to do your project in the best and fast way </h2>

        <div id="slide-show">
            <img src="../images/home1.jpeg" width="1200" height="744" alt="">
            <img src="../images/home2.jpeg" width="1200" height="744" alt="">
            <img src="../images/home1.jpeg" width="1200" height="744" alt="">
            <img src="../images/home2.jpeg" width="1200" height="744" alt="">
        </div>
    </div>

    <!--Waves Container-->
    <div>
        <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
            <defs>
                <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"/>
            </defs>
            <g class="parallax">
                <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7"/>
                <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)"/>
                <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)"/>
                <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff"/>
            </g>
        </svg>
    </div>
    <!--Waves end-->

</div>
<!--Header ends-->

<!--Content starts-->
<div class="content-grid">
    <div id="sec1">A productivity powerhouse</div>
    <div id="sec2">Simple, flexible, and powerful. All it takes are boards, lists,
        and cards to get a clear view of who is doing what and what needs to get done.</div>
    <button onclick="document.getElementById('img1').src='../images/home3.jpeg'" id="sec3" >
        <div>
            <span id="s1"> Boards</span>
            <div ><?= Project::PROJ_NAME ?> boards keep tasks organized and work moving forward.
                In a glance, see everything from “things to do” to “oh yeah, we did it!”</div>
        </div>
    </button>
    <button id="sec4" onclick="document.getElementById('img1').src='../images/home3.jpeg'">
        <div id="d1">
            <span id="s2">List</span>
            <div>The different stages of a task. Start as simple as To Do,
                Doing or Done or build a workflow custom fit to your team’s needs. There’s no wrong way to Trello.
            </div>
        </div>
    </button>
    <button id="sec5" onclick="document.getElementById('img1').src='img/cards.png'">
        <div>
            <span id="s3">cards</span>
            <div>Cards represent tasks and ideas and hold all the information to get the job done.
                As you make progress, move cards across lists to show their status.</div>
        </div>
    </button>
    <div id="sec6">
        <img id="img1" src="img/bords.png" onload="load()">
        <img id="img3" src="img/cards.png" style="display: none">
        <img id="img2" src="img/List.png.png"style="display: none">
    </div>

    <div id="second_section">


    </div>
</div>
<!--Content ends-->

</body>
</html>

