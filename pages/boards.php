<?php
session_start();
include "../common/functions.php";
include "../common/List_Cards_pair.php";

// ---------- if the user signed in go to workspaces ------------------
if (!(isset($_SESSION['validUser']) and $_SESSION['validUser'])){
    die('Page Not Found !!');
}
// ------------ request method should be GET ---------------------------------
if ($_SERVER['REQUEST_METHOD'] !== "GET"){
    die('Page Not Found !!');
}
// ------------- id url parameter should be set --------------------
if (!isset($_GET['id'])){
    die("Board parameter Not Found !!");
}
// ------------- check board in database -------------------------------------
$conn = connectDB();
$prep = "select `boards`.`id` as 'board_id', `boards`.`name` as 'board_name', `workspaces`.`id` as 'ws_id', `workspaces`.`name` as 'ws_name' from " .
        "`boards`, `workspaces` where `boards`.`id` = :boardID and `workspaces`.`user_id` = :userID ".
        "and `boards`.`workspace_id` = `workspaces`.`id`";
$stmt = $conn->prepare($prep);
$stmt->bindParam(':boardID', $_GET['id']);
$stmt->bindParam(':userID', $_SESSION['userId']);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$result = $stmt->fetchAll();
if (count($result) == 1){
    $_SESSION['boardId'] = $result[0]['board_id'];
    $_SESSION['boardName'] = $result[0]['board_name'];
    $_SESSION['workspaceId'] = $result[0]['ws_id'];
    $_SESSION['workspaceName'] = $result[0]['ws_name'];
}else{
    die("Board not found");
}

//-------------- fetch all the information about board from database ---------------
$conn = connectDB();
$prep = "select * from `boards` where `workspace_id` = :id";
$stmt = $conn->prepare($prep);
$stmt->bindParam(':id', $_SESSION['workspaceId']);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$boards = $stmt->fetchAll();
$boardTemplate = '';
foreach ($boards as $board){
    if ($board['id'] == $_SESSION['boardId']){
        $boardTemplate = $board['template'];
    }
}
// ----------------------------
$prep = "select * from `lists` where `board_id` = :id order by `serial`";
$stmt = $conn->prepare($prep);
$stmt->bindParam(':id', $_SESSION['boardId']);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$lists = $stmt->fetchAll();
// ---------------------------------
$lists_cards = array();
$lists_cards = array_fill(0, count($lists)+1, -1);
foreach ($lists as $list){
    $prep = "select * from `cards` where `list_id` = :id order by `serial`";
    $stmt = $conn->prepare($prep);
    $stmt->bindParam(':id', $list['id']);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $cards = $stmt->fetchAll();
    $node = new List_Cards_pair($list, $cards);
    $lists_cards[$list['serial']] = $node;              // sorted array [serial] = object{list="", cards=""}
}
$conn = NULL;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="../styles/slideBarStyle.css" />
    <link rel="stylesheet" href="../styles/listsStyle.css" />
    <link rel="stylesheet" href="../<?= $boardTemplate ?>"/>


    <title><?= $_SESSION['boardName'] ?></title>
</head>
<body>

<?php
    include '../common/nav_bar.php';
?>

    <div class="container">

        <!-- slide bar -->
            <div class="closedBar"></div>
            <a href="javascript:void(0)" onclick="openSlideBar()" class="openSlideBar"><div class="openImg"></div></a>
            <div class="slideBar">
                <table class="table1">
                    <tr>
                        <td><div class="wsImage"><?= $_SESSION['workspaceName'][0] ?></div></td>
                        <td><div class="wsName"><?= $_SESSION['workspaceName'] ?></div></td>
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
                    <?php
                        foreach ($boards as $board ){
                            echo "<tr><td id='board". $board['id'] ."' data-id='". $board['id'] ."'>";
                            echo "<span>".$board['name']."</span><span class='ellipseSpan'>&#x22EF;</span>";
                            echo "</td></tr>";
                        }
                    ?>
                </table>
            </div>

        <!-- lists area -->
        <div class="lists">

            <div class="topBar">
                <p class="boardNameTopBar"><?= $_SESSION['boardName'] ?></p>
            </div>

            <div class="flexBox">

                    <div class="listsContainer">

                        <?php
                            foreach ($lists_cards as $pair){
                                if ($pair === -1) continue;
                                $list = $pair->list;
                                $cards = $pair->cards;
                        ?>

                            <div class="list draggableList" draggable="true" data-id="<?= $list['id'] ?>" id="list<?= $list['id'] ?>">
                                <div class="listName editable" contenteditable="true">
                                    <?= $list['name'] ?>
                                </div>

                                <div class="cardsContainer">

                                    <?php
                                        foreach ($cards as $card){
                                    ?>
                                        <div class="card draggableCard" draggable="true" data-id="<?= $card['id'] ?>" id="card<?= $card['id'] ?>">
                                            <div class="relativeCard" style="position: relative; overflow: hidden;">
                                                <div class="cardNameInput"><?= $card['name'] ?></div>
                                                <div class="cardContent">&#x2630;
                                                    <span class="rightEntityEdit">&#x270E;
                                                        <div class="cardSetting">
                                                            <div class="cardSettingDelete">
                                                                &#x2717;
                                                            </div>
                                                            <div class="cardSettingOpen">
                                                                &#x27AA;
                                                            </div>
                                                        </div>
                                                    </span></div>

                                            </div>
                                            <div class="cardPanel">
                                                <div class="closeCardPanel">&#x2613;</div>
                                                <div class="cardPanelTop">
                                                    <span style="font-size: 36px;">&#x2042;</span>
                                                    <div class="cardPanelTitle editable" contenteditable="true"><?= $card['name'] ?></div>
                                                    <div class="inList">
                                                        In list <?= $list['name'] ?>
                                                    </div>
                                                </div>
                                                <br/>
                                                <hr/>
                                                <div class="cardPanelDesc">
                                                    <span style="font-size: 36px;">&#x274F;</span><span style="margin-left: 10px; font-size: 26px;">Description</span>
                                                    <span style="margin-left: 50%"><button type="button" class="saveDesc">Save</button></span>
                                                    <div class="cardDescription" contenteditable="true">
                                                        <?= nl2br($card['description']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        }
                                    ?>

                                </div>

                                    <div class="addCard">
                                        <table class="addAnotherCardTable"><tr><td class="addSign-card">+</td><td class="addAnotherCard">Add Card</td></tr></table>
                                        <div class="addCardForm">
                                            <input type="text" name="cardName" class="cardName" placeholder="Enter Card name:" /><br/>
                                            <table>
                                                <tr>
                                                    <td><button type="button" class="createCard">Create</button></td>
                                                    <td><img src="../images/closeIcon.png" alt="close" class="closeCreateCard"/></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                            </div>

                        <?php
                            }
                        ?>

                    </div>

                    <!-- add list div -->
                    <div class="addList">
                        <table style="padding-left: 25px" class="addAnotherListTable"><tr><td class="addSign">+</td><td class="addAnotherList">Add another list</td></tr></table>
                        <div class="addListForm">
                            <input type="text" name="listName" id="listName" placeholder="Enter List name:"/><br/>
                            <table>
                                <tr>
                                    <td><button type="button" id="createList">Create</button></td>
                                    <td><img src="../images/closeIcon.png" alt="close" id="closeCreateList"/></td>
                                </tr>
                            </table>
                        </div>
                    </div>


            </div>

        </div>  <!-- end of lists area -->

    </div>  <!-- end of container -->

    <!-- add board plane -->
    <div class="addBoardPlane">
        <div class="closeCreateBoardPanel"></div>
        <div class="form">
            <label for="boardNameInput">Enter Board Name</label><br/>
            <input type="text" name="boardNameInput" id="boardNameInput"/><br/><br/>
            <div class="boardTemplateSection">
                <label for="boardTemplate">Choose Template:</label>
                <select name="boardTemplate" id="boardTemplate" required>
                    <option value="styles/templates/default.css" selected>default style</option>
                    <option value="styles/templates/sunset.css">sunset style</option>
                </select>
            </div><br/>
            <button type="button" id="submitCreateCard">Create</button>
        </div>
        <div class="addBoardErrors"></div>
    </div>

    <!-- card panel -->
    <div class="cardPanelGlass"></div>

    <!-- list copy for add list function -->
    <div class="list draggableList copySampleList" draggable="true" data-id="1" id="list1">
        <div class="listName editable" contenteditable="true">
            name
        </div>

        <div class="cardsContainer">
        </div>

        <div class="addCard">
            <table class="addAnotherCardTable"><tr><td class="addSign-card">+</td><td class="addAnotherCard">Add Card</td></tr></table>
            <div class="addCardForm">
                <input type="text" name="cardName" class="cardName" placeholder="Enter Card name:" /><br/>
                <table>
                    <tr>
                        <td><button type="button" class="createCard">Create</button></td>
                        <td><img src="../images/closeIcon.png" alt="close" class="closeCreateCard"/></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- card copy for add card function -->
    <div class="card draggableCard copySampleCard" draggable="true" data-id="1" id="card1">
        <div class="relativeCard" style="position: relative; overflow: hidden;">
            <div class="cardNameInput">name</div>
            <div class="cardContent">&#x2630;
                <span class="rightEntityEdit">
                    &#x270E;
                    <div class="cardSetting">
                        <div class="cardSettingDelete">
                            &#x2717;
                        </div>
                        <div class="cardSettingOpen">
                            &#x27AA;
                        </div>
                    </div>
                </span>
            </div>

        </div>
        <div class="cardPanel">
            <div class="closeCardPanel">&#x2613;</div>
            <div class="cardPanelTop">
                <span style="font-size: 36px;">&#x2042;</span>
                <div class="cardPanelTitle editable" contenteditable="true">name</div>
                <div class="inList" style="grid-column: 1/span2; font-size: 16px; padding: 10px 60px; color: rgba(10,10,10,0.6);">
                    In list LIST
                </div>
            </div>
            <br/>
            <hr/>
            <div class="cardPanelDesc">
                <span style="font-size: 36px;">&#x274F;</span><span style="margin-left: 10px; font-size: 26px;">Description</span>
                <span style="margin-left: 50%"><button type="button" class="saveDesc">Save</button></span>
                <div class="cardDescription" contenteditable="true">
                    description
                </div>
            </div>
        </div>
    </div>

    <div class="binContainer">
        <div class="binImage">
        </div>
    </div>

    <div class="contextBoards" data-source="">
        <div class="contextBoardsDelete">Delete</div>
        <hr/>
        <div class="contextBoardsRename">Rename</div>
    </div>

    <div class="boardRenamePane">
        <input type="text" id="boardRenameInput"  required/>
    </div>

    <div class="renameBoardGlass"></div>

    <!-- scripts type=javascript -->
    <script type="text/javascript" src="../jscripts/lists.js"></script>
    <script type="text/javascript" src="../jscripts/dragdrop.js"></script>
    <script src="../jscripts/slideBar.js"></script>
    <script src="../jscripts/ajaxLists.js"></script>
    <script src="../jscripts/cardSetting.js"></script>

</body>
</html>
