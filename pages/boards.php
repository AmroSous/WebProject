<?php
session_start();
include "../common/project.php";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="../styles/slideBarStyle.css" />
    <script src="../jscripts/slideBar.js"></script>

    <title>Board</title>
</head>
<body>

<?php include '../common/nav_bar.php'; ?>

    <table class="container" style="width: 100%"><tr>

            <td class=".sideBarCell" style="width: 170px">
        <!-- slide bar -->
        <div class="closedBar"></div>
        <a href="javascript:void(0)" onclick="openSlideBar()" class="openSlideBar"><div class="openImg"></div></a>
        <div class="slideBar">
            <table class="table1">
                <tr>
                    <td><div class="wsImage">B</div></td>
                    <td><div class="wsName">Workspace</div></td>
                    <td><a href="javascript:void(0)" class="closeSlideBar" onclick="closeSlideBar()"><div class="closeImg"></div></a></td>
                </tr>
            </table>
            <hr/>
            <table class="table2">
                <tr>
                    <td><span class="yourBoarders">Your boards:</span></td>
                    <td><a href="javascript:void(0)" class="addBoard" onclick="addBoard()">+</a></td>
                </tr>
            </table>
            <table class="boardsList">
                <tr>
                    <td>Board one</td>
                </tr>
                <tr>
                    <td>Board two</td>
                </tr>
                <tr>
                    <td>Third board</td>
                </tr>
            </table>
        </div>
            </td>
            <td>
        <!-- lists area -->
        <div class="board">
            <!-- top bar in board area -->
            <div class="topBar"></div>
            <!-- lists container -->
            <div class="listsContainer">
                <div class="list draggable" draggable="true">
                    <div class="card draggable" draggable="true"></div>
                    <div class="card draggable" draggable="true"></div>
                    <div class="card draggable" draggable="true"></div>
                </div>
                <div class="list draggable" draggable="true">
                    <div class="card draggable" draggable="true"></div>
                    <div class="card draggable" draggable="true"></div>
                    <div class="card draggable" draggable="true"></div>
                </div>
                <!-- add list div -->
                <div class="addList">
                    <span class="addAnotherList">+ Add another list</span>
                    <form action="" method="post">
                        <input type="text" name="listName" id="listName" /><br/>
                        <input type="submit" name="createList" id="createList"/>
                        <a href="javascript:void(0)" class="closeCreateList" onclick="closeCreateList()"></a>
                    </form>
                </div>
            </div>
        </div>
            </td>
        </tr></table>


</body>
</html>
