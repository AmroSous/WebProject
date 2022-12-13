<?php
session_start();

include '../common/functions.php';
include '../common/ws_boards_pair.php';

// if the user signed in go to workspaces
if (!(isset($_SESSION['validUser']) and $_SESSION['validUser'])){
    header('location: home.php');
}

// get user workspaces and boards from database
//-------------- fetch all the information about board from database ---------------
$conn = connectDB();
$prep = "select * from `workspaces` where `user_id` = :id";
$stmt = $conn->prepare($prep);
$stmt->bindParam(':id', $_SESSION['userId']);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$workspaces = $stmt->fetchAll();

// ---------------------------------
$wss_boards = array();
$wss_boards = array_fill(0, count($workspaces), -1);
$i = 0;
foreach ($workspaces as $ws){
    $prep = "select * from `boards` where `workspace_id` = :id";
    $stmt = $conn->prepare($prep);
    $stmt->bindParam(':id', $ws['id']);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $boards = $stmt->fetchAll();
    $node = new ws_boards_pair($ws, $boards);
    $wss_boards[$i] = $node;
    $i++;
}
$conn = NULL;

?>

<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<link rel="stylesheet" href="../styles/workspace.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
<?php include '../common/nav_bar.php'; ?>

<!-- Workspaces elements here -->

    <div class="content">
        <div class="video-wrapper">
            <video playsinline autoplay muted loop poster>
                <source src="../images/video.mp4" type="video/mp4">
            </video>
        </div>
        <div class="addWS">
            <label for="addWsInput">workspace name: </label><input id="addWsInput" type="text"><button type="button" id="addWsButton">ADD</button>
        </div>
        <div class="wsName">

            <?php
                for ($i = 0; $i < count($wss_boards); $i++){
            ?>
            <button class="workspaces" data-id="<?= $wss_boards[$i]->workspace['id'] ?>">
                <div class="wsSettingEllipse">&#x22EF;</div>
                <div class="wsSetting"><span class="wsSettingDelete">&#x2613;</span><span class="wsSettingEdit">&#x270E;</span><span class="wsSettingAdd">&#x271A;</span></div>
                <?= $wss_boards[$i]->workspace['name'] ?>
            </button>
            <ul class="carding" id="l<?= $wss_boards[$i]->workspace['id'] ?>">
                <?php
                    foreach ($wss_boards[$i]->boards as $board){
                ?>
                <li id="b<?= $board['id'] ?>" data-id="<?= $board['id'] ?>">
                    <a class="card" href="boards.php?id=<?= $board['id'] ?>"><?= $board['name'] ?></a>
                </li>
                <?php
                    }
                ?>
            </ul>
            <?php
                }
            ?>
        </div>

    </div>

    <div class="glassLayer"></div>

    <div class="addBoardPlane" data-id="">
        <div class="closeCreateBoardPanel"></div>
        <div class="form">
            <label for="boardNameInput">Enter Board Name</label><br/>
            <input type="text" name="boardNameInput" id="boardNameInput"/><br/><br/>
            <div class="boardTemplateSection">
                <label for="boardTemplate">Choose Template:</label>
                <select name="boardTemplate" id="boardTemplate" required>
                    <option value="styles/templates/default.css" selected>default style</option>
                    <option value="styles/templates/sunset.css">sunset style</option>
                    <option value="styles/templates/synthwave.css">synthwave style</option>
                </select>
            </div><br/>
            <button type="button" id="submitCreateCard">Create</button>
        </div>
        <div class="addBoardErrors"></div>
    </div>

    <script>
        document.querySelectorAll('.workspaces').forEach(work => {
            work.addEventListener('click', ()=>{
                const xx = work.nextElementSibling;
                if (xx.style.visibility == 'visible'){
                    xx.style.visibility = 'hidden';
                }else
                    xx.style.visibility = 'visible';
            })
        })
    </script>
    <script src="../jscripts/workspaces.js"></script>
    <script src="../jscripts/ajaxLists.js"></script>

</body>
</html>