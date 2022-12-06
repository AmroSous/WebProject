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

    <title>Board</title>
</head>
<body>

<?php include '../common/nav_bar.php'; ?>

    <div class="container">

        <!-- slide bar -->
            <div class="closedBar"></div>
            <a href="javascript:void(0)" onclick="openSlideBar()" class="openSlideBar"><div class="openImg"></div></a>
            <div class="slideBar">
                <table class="table1">
                    <tr>
                        <td><div class="wsImage">W</div></td>
                        <td><div class="wsName">Workspace</div></td>
                        <td><a href="javascript:void(0)" class="closeSlideBar" onclick="closeSlideBar()"><div class="closeImg"></div></a></td>
                    </tr>
                </table>
                <hr/>
                <table class="table2">
                    <tr>
                        <td><span class="yourBoarders">Your boards:</span></td>
                        <td><a href="javascript:void(0)" class="addBoard">+</a></td>
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

        <!-- lists area -->
        <div class="lists">

            <div class="topBar">
                <p class="boardNameTopBar">Board Name</p>
            </div>

            <div class="flexBox">

                <div class="listsContainer">

                <div class="list draggableList" draggable="true">
                    <div class="listName">
                        Card Name
                    </div>

                    <div class="cardsContainer">
                        <div class="card draggableCard" draggable="true">Card one</div>
                        <div class="card draggableCard" draggable="true">Card one</div>
                        <div class="card draggableCard" draggable="true">Card two</div>
                        <div class="card draggableCard" draggable="true">Card three</div>
                    </div>

                    <div class="addCard">
                        <table class="addAnotherCardTable"><tr><td class="addSign-card">+</td><td class="addAnotherCard">Add Card</td></tr></table>
                        <form action="" method="post" class="addCardForm">
                            <input type="text" name="cardName" class="cardName" placeholder="Enter Card name:" /><br/>
                            <table>
                                <tr>
                                    <td><input type="submit" name="createCard" class="createCard" value="Create"/></td>
                                    <td><img src="../images/closeIcon.png" alt="close" class="closeCreateCard"/></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>

                <div class="list draggableList" draggable="true">
                    <div class="listName">
                        Card Name
                    </div>

                    <div class="cardsContainer">
                        <div class="card draggableCard" draggable="true">Card four</div>
                        <div class="card draggableCard" draggable="true">Card five</div>
                        <div class="card draggableCard" draggable="true">Card six</div>
                    </div>

                    <div class="addCard">
                        <table class="addAnotherCardTable"><tr><td class="addSign-card">+</td><td class="addAnotherCard">Add Card</td></tr></table>
                        <form action="" method="post" class="addCardForm">
                            <input type="text" name="cardName" class="cardName" placeholder="Enter Card name:" /><br/>
                            <table>
                                <tr>
                                    <td><input type="submit" name="createCard" class="createCard" value="Create"/></td>
                                    <td><img src="../images/closeIcon.png" alt="close" class="closeCreateCard"/></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>

                <div class="list draggableList" draggable="true">
                    <div class="listName">
                        Card Name
                    </div>

                    <div class="cardsContainer">
                        <div class="card draggableCard" draggable="true">Card four</div>
                        <div class="card draggableCard" draggable="true">Card five</div>
                        <div class="card draggableCard" draggable="true">Card six</div>
                    </div>

                    <div class="addCard">
                        <table class="addAnotherCardTable"><tr><td class="addSign-card">+</td><td class="addAnotherCard">Add Card</td></tr></table>
                        <form action="" method="post" class="addCardForm">
                            <input type="text" name="cardName" class="cardName" placeholder="Enter Card name:" /><br/>
                            <table>
                                <tr>
                                    <td><input type="submit" name="createCard" class="createCard" value="Create"/></td>
                                    <td><img src="../images/closeIcon.png" alt="close" class="closeCreateCard"/></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>

                    <div class="list draggableList" draggable="true">
                        <div class="listName">
                            Card Name
                        </div>

                        <div class="cardsContainer">
                            <div class="card draggableCard" draggable="true">Card four</div>
                            <div class="card draggableCard" draggable="true">Card five</div>
                            <div class="card draggableCard" draggable="true">Card six</div>
                        </div>

                        <div class="addCard">
                            <table class="addAnotherCardTable"><tr><td class="addSign-card">+</td><td class="addAnotherCard">Add Card</td></tr></table>
                            <form action="" method="post" class="addCardForm">
                                <input type="text" name="cardName" class="cardName" placeholder="Enter Card name:" /><br/>
                                <table>
                                    <tr>
                                        <td><input type="submit" name="createCard" class="createCard" value="Create"/></td>
                                        <td><img src="../images/closeIcon.png" alt="close" class="closeCreateCard"/></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>

            </div>

                <!-- add list div -->
                <div class="addList">
                    <table style="padding-left: 25px" class="addAnotherListTable"><tr><td class="addSign">+</td><td class="addAnotherList">Add another list</td></tr></table>
                    <form action="" method="post" class="addListForm">
                        <input type="text" name="listName" id="listName" placeholder="Enter List name:"/><br/>
                        <table>
                            <tr>
                                <td><input type="submit" name="createList" id="createList" value="Create"/></td>
                                <td><img src="../images/closeIcon.png" alt="close" id="closeCreateList"/></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>

        </div>  <!-- end of lists area -->

    </div>  <!-- end of container -->

    <!-- add board plane -->
    <div class="addBoardPlane">
        <div class="closeCreateBoardPanel"></div>

    </div>

<script type="text/javascript" src="../jscripts/lists.js"></script>
<script type="text/javascript" src="../jscripts/dragdrop.js"></script>
<script src="../jscripts/slideBar.js"></script>

</body>
</html>
