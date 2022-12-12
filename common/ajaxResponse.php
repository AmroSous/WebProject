<?php
session_start();

// if the user signed in go to workspaces
if (!(isset($_SESSION['validUser']) and $_SESSION['validUser'])){
    die('not valid user');
}

include 'functions.php';

// ============= types of ajax request =======================
const ListOrder = 'ListOrder';
const CardOrder = 'CardOrder';
const ListName = 'ListName';
const CardName = 'CardName';
const CardDesc = 'CardDesc';
const AddBoard = 'AddBoard';
const AddCard = 'AddCard';
const AddList = 'AddList';
const DeleteBoard = 'DeleteBoard';
const DeleteList = 'DeleteList';
const DeleteCard = 'DeleteCard';
const RenameBoard = 'RenameBoard';
// ===========================================================

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if (isset($_POST['type']) && isset($_POST['content'])){
        switch ($_POST['type']){
            //-------------------------------------------------------------------------
            case AddBoard:
                $response = array(
                    'status' => '0',
                    'boardId' => '0',
                    'error' => '0'
                );
                $request = json_decode($_POST['content']);
                $request = (array)($request);
                $boardName = test_input($request['boardName']);
                $template = test_input($request['template']);
                if (empty($boardName) || empty($template)){
                    $response['error'] = 'Empty Field';
                    $response['status'] = 'NO';
                }else{
                    $conn = connectDB();
                    $prep = "insert into `boards` (`name`, `workspace_id`, `template`) values (:name, :ws_id, :template)";
                    $stmt = $conn->prepare($prep);
                    $ws_id = test_input($_SESSION['workspaceId']);
                    $stmt->bindParam(':name', $boardName);
                    $stmt->bindParam(':ws_id', $ws_id);
                    $stmt->bindParam(':template', $template);
                    $res = $stmt->execute();
                    if ($res){
                        $response['status'] = 'OK';
                        $response['boardId'] = $conn->lastInsertId();
                    }
                    else{
                        $response['status'] = 'NO';
                        $response['error'] = 'SQL Failed';
                    }
                }
                $conn = NULL;
                echo json_encode($response);
                break;
            //-------------------------------------------------------------------------
            case CardOrder:

                $response = array(
                    'error' => '',
                    'status' => '',
                    'listID' => '',
                    'listName' => '',
                    'cardID' => '',
                    'prevList' => ''
                );
                if (empty($_POST['content'])){
                    $response['error'] = 'Empty Request';
                    $response['status'] = 'NO';
                }else{
                    $request = json_decode($_POST['content']);
                    $request = (array)($request);
                    $card_serial = (array)$request['card_serial'];
                    $listID = (int)$request['listID'];
                    $cardID = (int)$request['cardID'];
                    $prevSibling = (int)($request['prevSibling']);
                    $response['listID'] = $listID;
                    $response['cardID'] = $cardID;

                    // verify list - user in database
                    $conn = connectDB();
                    // get the previous list id
                    $prep = "select `list_id` from `cards` where `id` = :id";
                    $stmt = $conn->prepare($prep);
                    $stmt->bindParam(':id', $cardID);
                    $res = $stmt->execute();
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $query = $stmt->fetchAll();
                    $prevList = $query[0]['list_id'];
                    $response['prevList'] = $prevList;

                    // get all cards of destination list
                    $prep = "select `cards`.`id` from `cards`, `lists`, `boards`, `workspaces`, `users` ".
                            "where `cards`.`list_id` = `lists`.`id` and `lists`.`board_id` = `boards`.`id` and `boards`.`workspace_id` = `workspaces`.`id` ".
                            "and `workspaces`.`user_id` = `users`.`id` and `users`.`id` = :userID and `lists`.`id` = :listID";
                    $stmt = $conn->prepare($prep);
                    $stmt->bindParam(':userID', $_SESSION['userId']);
                    $stmt->bindParam(':listID', $listID);
                    $res = $stmt->execute();
                    if ($res){
                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        $query = $stmt->fetchAll();
                        $ids = array();
                        if (count($query) > 0) {
                            for ($i = 0; $i < count($query); $i++) {
                                $ids[$i] = $query[$i]['id'];
                            }
                            $ids[$i+1] = $cardID;  // add new card
                        }else{
                            $ids[0] = $cardID;                 // new list cards + added card
                        }

                        // get the name of destination list
                        $sql = "select `name` from `lists` where `id` = ".$listID;
                        foreach ($conn->query($sql) as $row){
                            $response['listName'] = $row['name'];
                        }

                        // get id - serial pair of all cards in the previous card
                        $prep = "select `id`, `serial` from `cards` where `list_id` = :id order by `serial`";
                        $stmt = $conn->prepare($prep);
                        $stmt->bindParam(':id', $prevList);
                        $res = $stmt->execute();
                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        $result = $stmt->fetchAll();
                        $prev_card_serial = array();
                        for ($i = 0; $i < count($result); $i++){
                            $prev_card_serial[$result[$i]['id']] = $result[$i]['serial'];
                        }

                        // update list id of the dragged card
                        $conn->query("update `cards` set `list_id` = ". $listID ." where `id` = ".$cardID);

                        // update serial of all the cards in destination list
                        $prep = "update `cards` set `serial` = :serial where `id` = :id";
                        $conn->beginTransaction();
                        $stmt = $conn->prepare($prep);
                        $stmt->bindParam(':serial', $serial);
                        $stmt->bindParam(':id', $id);
                        $serial = '';
                        $id = '';
                        foreach ($card_serial as $id => $serial){
                            if (!in_array($id, $ids)){
                                $conn->rollBack();
                                $response['error'] = 'invalid card access';
                                $response['status'] = 'NO';
                                break;
                            }
                            $res = $stmt->execute();
                            if (!$res){
                                $conn->rollBack();
                                $response['error'] = 'SQL error';
                                $response['status'] = 'NO';
                                break;
                            }
                        }
                        if ($response['error'] == '') {
                            // edit source list
                            $conn->commit();
                            // update serial in previous list
                            $conn->beginTransaction();
                            $stmt = $conn->prepare($prep);
                            $stmt->bindParam(':serial', $serial);
                            $stmt->bindParam(':id', $id);
                            foreach ($prev_card_serial as $id => $serial) {
                                if ($prev_card_serial[$cardID] >= $serial) continue;
                                $serial--;
                                $res = $stmt->execute();
                                if (!$res) {
                                    $conn->rollBack();
                                    $response['error'] = 'SQL error';
                                    $response['status'] = 'NO';
                                    break;
                                }
                            }
                            if ($response['error'] == '') {
                                $response['status'] = 'OK';
                                $conn->commit();
                            }
                        }
                    }else{
                        $response['error'] = 'Empty Request';
                        $response['status'] = 'NO';
                    }
                }
                $conn = NULL;
                echo json_encode($response);
                break;
            //-------------------------------------------------------------------------
            case ListOrder:
                $response = array(
                    'status' => '',
                    'error' => ''
                );

                $request = json_decode($_POST['content']);
                $request = (array)($request);

                //--------------------  check if lists belong to this user -----
                $conn = connectDB();
                $prep = "select `lists`.`id` from `lists`, `boards`, `workspaces`, `users` ".
                        "where `lists`.`board_id` = `boards`.`id` and `boards`.`workspace_id` = `workspaces`.`id` ".
                        "and `workspaces`.`user_id` = `users`.`id` and `users`.`id` = :userID and `boards`.`id` = :boardID";
                $stmt = $conn->prepare($prep);
                $stmt->bindParam(':userID', $_SESSION['userId']);
                $stmt->bindParam(':boardID', $_SESSION['boardId']);
                $res = $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $query = $stmt->fetchAll();
                $checking_arr = array();
                for ($i = 0; $i < count($query); $i++){
                    $checking_arr[$i] = $query[$i]['id'];
                }

                // update serial for all lists
                $conn = connectDB();
                $conn->beginTransaction();
                $prep = "update `lists` set `serial` = :serial where `id` = :id";
                $stmt = $conn->prepare($prep);
                $stmt->bindParam(':serial', $serial);
                $stmt->bindParam(':id', $id);
                foreach ($request as $id => $serial){
                    $id = (int)($id);
                    if (!in_array($id, $checking_arr)){
                        $response['status'] = 'NO';
                        $response['error'] = 'invalid list access';
                        $conn->rollBack();
                        break;
                    }
                    $res = $stmt->execute();
                    if (!$res){
                        $response['status'] = 'NO';
                        $response['error'] = 'SQL failure';
                        $conn->rollBack();
                        break;
                    }
                }
                if ($response['error'] == ''){
                    $conn->commit();
                    $response['status'] = 'OK';
                }
                $conn = NULL;
                echo json_encode($response);
                break;
            //-------------------------------------------------------------------------
            case ListName:
                $response = array(
                    'status' => '',
                    'name' => '',
                    'error' => '',
                    'listID' => ''
                );
                $request = json_decode($_POST['content']);
                $request = (array)$request;
                $conn = connectDB();
                $prep = "select `lists`.`name` from lists, boards, workspaces " .
                        "where `lists`.`board_id` = `boards`.`id` and `boards`.`workspace_id` = `workspaces`.`id` and ".
                        "`workspaces`.`user_id` = :userID and `lists`.`id` = :listID";
                $stmt = $conn->prepare($prep);
                $listID = test_input($request['listID']);
                $response['listID'] = $listID;
                $name = test_input($request['name']);
                $stmt->bindParam(':listID', $listID);
                $stmt->bindParam(':userID', $_SESSION['userId']);
                $res = $stmt->execute();
                if ($res){
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $query = $stmt->fetchAll();
                    if (count($query) == 1){
                        if (empty($name)){
                            $response['status'] = 'NO';
                            $response['error'] = 'Empty field.';
                            $response['name'] = $query[0]['name'];
                        }else{
                            $prep = "update `lists` set `name` = :name where `id` = :id";
                            $stmt = $conn->prepare($prep);
                            $stmt->bindParam(':name', $name);
                            $stmt->bindParam(':id', $listID);
                            $res = $stmt->execute();
                            if ($res){
                                $response['status'] = 'OK';
                                $response['name'] = $name;
                            }else{
                                $response['status'] = 'NO';
                                $response['error'] = 'SQL Failed.';
                                $response['name'] = $query['name'];
                            }
                        }
                    }else{
                        $response['status'] = 'NO';
                        $response['error'] = 'Sorry. Error detected.';
                        $response['name'] = $query['name'];
                    }
                }else{
                    $response['status'] = 'NO';
                    $response['error'] = 'SQL Failed.';
                    $response['name'] = 'Oops!';
                }
                $conn = NULL;
                echo json_encode($response);
                break;
            //-------------------------------------------------------------------------
            case CardName:
                $response = array(
                    'status' => '',
                    'name' => '',
                    'error' => '',
                    'cardID' => ''
                );
                $request = json_decode($_POST['content']);
                $request = (array)$request;
                $conn = connectDB();
                $prep = "select `cards`.`name` from cards, lists, boards, workspaces " .
                        "where `cards`.`list_id` = `lists`.`id` and `lists`.`board_id` = `boards`.`id` and `boards`.`workspace_id` = `workspaces`.`id` and ".
                        "`workspaces`.`user_id` = :userID and `cards`.`id` = :cardID";
                $stmt = $conn->prepare($prep);
                $cardID = test_input($request['cardID']);
                $response['cardID'] = $cardID;
                $name = test_input($request['name']);
                $stmt->bindParam(':cardID', $cardID);
                $stmt->bindParam(':userID', $_SESSION['userId']);
                $res = $stmt->execute();
                if ($res){
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $query = $stmt->fetchAll();
                    if (count($query) == 1){
                        if (empty($name)){
                            $response['status'] = 'NO';
                            $response['error'] = 'Empty field.';
                            $response['name'] = $query[0]['name'];
                        }else{
                            $prep = "update `cards` set `name` = :name where `id` = :id";
                            $stmt = $conn->prepare($prep);
                            $stmt->bindParam(':name', $name);
                            $stmt->bindParam(':id', $cardID);
                            $res = $stmt->execute();
                            if ($res){
                                $response['status'] = 'OK';
                                $response['name'] = $name;
                            }else{
                                $response['status'] = 'NO';
                                $response['error'] = 'SQL Failed.';
                                $response['name'] = $query['name'];
                            }
                        }
                    }else{
                        $response['status'] = 'NO';
                        $response['error'] = 'Sorry. Error detected.';
                        $response['name'] = $query['name'];
                    }
                }else{
                    $response['status'] = 'NO';
                    $response['error'] = 'SQL Failed.';
                    $response['name'] = 'Oops!';
                }
                $conn = NULL;
                echo json_encode($response);
                break;
            //-------------------------------------------------------------------------
            case CardDesc:
                $response = array(
                    'status' => '',
                    'desc' => '',
                    'error' => '',
                    'cardID' => ''
                );
                $request = json_decode($_POST['content']);
                $request = (array)$request;
                $conn = connectDB();
                $prep = "select `cards`.`description` from cards, lists, boards, workspaces " .
                    "where `cards`.`list_id` = `lists`.`id` and `lists`.`board_id` = `boards`.`id` and `boards`.`workspace_id` = `workspaces`.`id` and ".
                    "`workspaces`.`user_id` = :userID and `cards`.`id` = :cardID";
                $stmt = $conn->prepare($prep);
                $cardID = test_input($request['cardID']);
                $response['cardID'] = $cardID;
                $desc = test_input($request['desc']);
                $stmt->bindParam(':cardID', $cardID);
                $stmt->bindParam(':userID', $_SESSION['userId']);
                $res = $stmt->execute();
                if ($res){
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $query = $stmt->fetchAll();
                    if (count($query) == 1){
                        $prep = "update `cards` set `description` = :desc where `id` = :id";
                        $stmt = $conn->prepare($prep);
                        $stmt->bindParam(':desc', $desc);
                        $stmt->bindParam(':id', $cardID);
                        $res = $stmt->execute();
                        if ($res){
                            $response['status'] = 'OK';
                            $response['desc'] = $desc;
                        }else{
                            $response['status'] = 'NO';
                            $response['error'] = 'SQL Failed.';
                            $response['desc'] = $query['desc'];
                        }
                    }else{
                        $response['status'] = 'NO';
                        $response['error'] = 'Sorry. Error detected.';
                        $response['desc'] = $query['desc'];
                    }
                }else{
                    $response['status'] = 'NO';
                    $response['error'] = 'SQL Failed.';
                    $response['desc'] = '';
                }
                $conn = NULL;
                echo json_encode($response);
                break;
            //-------------------------------------------------------------------------
            case AddCard:
                $response = array(
                    'status' => '',
                    'name' => '',
                    'error' => '',
                    'cardID' => '',
                    'listID' => '',
                    'listName' => '',
                    'position' => ''
                );
                $request = json_decode($_POST['content']);
                $request = (array)$request;
                $listID = test_input($request['listID']);
                $name = test_input($request['name']);
                $desc = '';
                $position = test_input($request['position']);
                $response['listID'] = $listID;
                $response['position'] = $position;
                $response['name'] = $name;
                if (!is_numeric($position) or !is_numeric($listID)){
                    $response['status'] = 'NO';
                    $response['error'] = 'Invalid data type.';
                }
                else {
                    $position = (int)($position);
                    $listID = (int)($listID);
                    $conn = connectDB();
                    $prep = "select `lists`.`id` from lists, boards, workspaces " .
                        "where `lists`.`board_id` = `boards`.`id` and `boards`.`workspace_id` = `workspaces`.`id` and " .
                        "`workspaces`.`user_id` = :userID and `lists`.`id` = :listID";
                    $stmt = $conn->prepare($prep);
                    $stmt->bindParam(':listID', $listID);
                    $stmt->bindParam(':userID', $_SESSION['userId']);
                    $res = $stmt->execute();
                    if ($res) {
                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        $query = $stmt->fetchAll();
                        if (count($query) == 1) {
                            if (empty($name)) {
                                $response['status'] = 'NO';
                                $response['error'] = 'Empty field.';
                            } else {
                                $sql = "select `name` from `lists` where `id` = ".$listID;
                                foreach ($conn->query($sql) as $row){
                                    $response['listName'] = $row['name'];
                                }
                                $prep = "insert into `cards` (`name`, `description`, `list_id`, `serial`) values (:name, :desc, :listID, :serial)";
                                $stmt = $conn->prepare($prep);
                                $stmt->bindParam(':name', $name);
                                $stmt->bindParam(':listID', $listID);
                                $stmt->bindParam(':desc', $desc);
                                $stmt->bindParam(':serial', $position);
                                $res = $stmt->execute();
                                if ($res) {
                                    $response['status'] = 'OK';
                                    $response['cardID'] = $conn->lastInsertId();
                                } else {
                                    $response['status'] = 'NO';
                                    $response['error'] = 'SQL Failed.';
                                }
                            }
                        } else {
                            $response['status'] = 'NO';
                            $response['error'] = 'Sorry. Error detected.';
                        }
                    } else {
                        $response['status'] = 'NO';
                        $response['error'] = 'SQL Failed.';
                    }
                }
                $conn = NULL;
                echo json_encode($response);
            break;
            //-------------------------------------------------------------------------
            case AddList:
                $response = array(
                    'status' => '',
                    'name' => '',
                    'error' => '',
                    'listID' => '',
                    'position' => ''
                );
                $request = json_decode($_POST['content']);
                $request = (array)$request;
                $name = test_input($request['name']);
                $position = test_input($request['position']);
                $boardID = $_SESSION['boardId'];
                $response['position'] = $position;
                $response['name'] = $name;
                if (!is_numeric($position)){
                    $response['status'] = 'NO';
                    $response['error'] = 'Invalid data type.';
                }
                else {
                    $position = (int)($position);
                    $conn = connectDB();
                    $prep = "select `boards`.`id` from boards, workspaces " .
                        "where `boards`.`workspace_id` = `workspaces`.`id` and " .
                        "`workspaces`.`user_id` = :userID and `boards`.`id` = :boardID";
                    $stmt = $conn->prepare($prep);
                    $stmt->bindParam(':boardID', $boardID);
                    $stmt->bindParam(':userID', $_SESSION['userId']);
                    $res = $stmt->execute();
                    if ($res) {
                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        $query = $stmt->fetchAll();
                        if (count($query) == 1) {
                            if (empty($name)) {
                                $response['status'] = 'NO';
                                $response['error'] = 'Empty field.';
                            } else {
                                $prep = "insert into `lists` (`name`, `board_id`, `serial`) values (:name, :boardID, :serial)";
                                $stmt = $conn->prepare($prep);
                                $stmt->bindParam(':name', $name);
                                $stmt->bindParam(':boardID', $boardID);
                                $stmt->bindParam(':serial', $position);
                                $res = $stmt->execute();
                                if ($res) {
                                    $response['status'] = 'OK';
                                    $response['listID'] = $conn->lastInsertId();
                                } else {
                                    $response['status'] = 'NO';
                                    $response['error'] = 'SQL Failed.';
                                }
                            }
                        } else {
                            $response['status'] = 'NO';
                            $response['error'] = 'Sorry. Error detected.';
                        }
                    } else {
                        $response['status'] = 'NO';
                        $response['error'] = 'SQL Failed.';
                    }
                }
                $conn = NULL;
                echo json_encode($response);
            break;
            //-------------------------------------------------------------------------
            case DeleteBoard:
                $response = array(
                    'status' => '',
                    'error' => '',
                    'boardID' => '',
                    'isCurrent' => ''
                );
                $request = json_decode($_POST['content']);
                $request = (array)$request;
                $boardID = test_input($request['boardID']);
                if ($boardID == $_SESSION['boardId'])
                    $response['isCurrent'] = 'YES';
                // check valid board access for this user
                $prep = "select `boards`.`id` from `boards`, `workspaces`, `users` where `boards`.`workspace_id` = `workspaces`.`id` ".
                    "and `workspaces`.`user_id` = `users`.`id` and `users`.`id` = :userID and `boards`.`id` = :boardID";
                $conn = connectDB();
                $stmt = $conn->prepare($prep);
                $stmt->bindParam(':userID', $_SESSION['userId']);
                $stmt->bindParam(':boardID', $boardID);
                $res = $stmt->execute();
                if ($res){
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $query = $stmt->fetchAll();
                    if (count($query) == 1){
                        $prep = "delete from `boards` where `id` = :id";
                        $stmt = $conn->prepare($prep);
                        $stmt->bindParam(':id', $boardID);
                        $stmt->execute();
                        $response['status'] = 'OK';
                        $response['boardID'] = $boardID;
                    }
                    else{
                        $response['status'] = 'NO';
                        $response['error'] = 'Sorry. Invalid board access';
                        $response['boardID'] = $boardID;
                    }
                }else{
                    $response['status'] = 'NO';
                    $response['error'] = 'SQL Failure';
                    $response['boardID'] = $boardID;
                }
                $conn = NULL;
                echo json_encode($response);
                break;
            //-------------------------------------------------------------------------
            case DeleteList:
                $response = array(
                    'status' => '',
                    'error' => '',
                    'listID' => ''
                );
                $request = json_decode($_POST['content']);
                $request = (array)$request;
                $listID = test_input($request['listID']);

                // check valid card access for this user
                $prep = "select `lists`.`id` from `lists`, `boards`, `workspaces`, `users` ".
                    "where `lists`.`board_id` = `boards`. `id` and `boards`.`workspace_id` = `workspaces`.`id` ".
                    "and `workspaces`.`user_id` = `users`.`id` and `users`.`id` = :userID and `lists`.`id` = :listID";
                $conn = connectDB();
                $stmt = $conn->prepare($prep);
                $stmt->bindParam(':userID', $_SESSION['userId']);
                $stmt->bindParam(':listID', $listID);
                $res = $stmt->execute();
                if ($res){
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $query = $stmt->fetchAll();
                    if (count($query) == 1){
                        $prep = "delete from `lists` where `id` = :id";
                        $stmt = $conn->prepare($prep);
                        $stmt->bindParam(':id', $listID);
                        $stmt->execute();
                        $response['status'] = 'OK';
                        $response['listID'] = $listID;
                    }
                    else{
                        $response['status'] = 'NO';
                        $response['error'] = 'Sorry. Invalid card access';
                        $response['listID'] = $listID;
                    }
                }else{
                    $response['status'] = 'NO';
                    $response['error'] = 'SQL Failure';
                    $response['listID'] = $listID;
                }
                $conn = NULL;
                echo json_encode($response);
                break;
            //-------------------------------------------------------------------------
            case DeleteCard:
                $response = array(
                    'status' => '',
                    'error' => '',
                    'cardID' => ''
                );
                $request = json_decode($_POST['content']);
                $request = (array)$request;
                $cardID = test_input($request['cardID']);

                // check valid card access for this user
                $prep = "select `cards`.`id` from `cards`, `lists`, `boards`, `workspaces`, `users` ".
                    "where `cards`.`list_id` = `lists`.`id` and `lists`.`board_id` = `boards`. `id` and `boards`.`workspace_id` = `workspaces`.`id` ".
                    "and `workspaces`.`user_id` = `users`.`id` and `users`.`id` = :userID and `cards`.`id` = :cardID";
                $conn = connectDB();
                $stmt = $conn->prepare($prep);
                $stmt->bindParam(':userID', $_SESSION['userId']);
                $stmt->bindParam(':cardID', $cardID);
                $res = $stmt->execute();
                if ($res){
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $query = $stmt->fetchAll();
                    if (count($query) == 1){
                        $prep = "delete from `cards` where `id` = :id";
                        $stmt = $conn->prepare($prep);
                        $stmt->bindParam(':id', $cardID);
                        $stmt->execute();
                        $response['status'] = 'OK';
                        $response['cardID'] = $cardID;
                    }
                    else{
                        $response['status'] = 'NO';
                        $response['error'] = 'Sorry. Invalid card access';
                        $response['boardID'] = $cardID;
                    }
                }else{
                    $response['status'] = 'NO';
                    $response['error'] = 'SQL Failure';
                    $response['boardID'] = $cardID;
                }
                $conn = NULL;
                echo json_encode($response);
                break;
            //-------------------------------------------------------------------------
            case RenameBoard:
                $response = array(
                    'status' => '',
                    'error' => '',
                    'boardName' => '',
                    'boardID' => '',
                    'isCurrent' => ''
                );
                $request = json_decode($_POST['content']);
                $request = (array)($request);
                $boardName = test_input($request['boardName']);
                $boardID = test_input($request['boardID']);
                // check valid board access
                $prep = "select `boards`.`name` from `boards`, `workspaces`, `users` where `boards`.`workspace_id` = `workspaces`.`id` ".
                        "and `workspaces`.`user_id` = `users`.`id` and `boards`.`id` = :boardID and `users`.`id` = :userID";
                $conn = connectDB();
                $stmt = $conn->prepare($prep);
                $stmt->bindParam(':boardID', $boardID);
                $stmt->bindParam(':userID', $_SESSION['userId']);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $query = $stmt->fetchAll();
                if (count($query) == 1){
                    $response['isCurrent'] = ($boardID == $_SESSION['boardId'])? 'YES' : 'NO';
                    if (empty($boardName)){
                        $response['status'] = 'NO';
                        $response['error'] = 'Empty field';
                        $response['boardName'] = $query[0]['name'];
                        $response['boardID'] = $boardID;
                    }
                    else{
                        $prep = "update `boards` set `name` = :name where `id` = :id";
                        $stmt = $conn->prepare($prep);
                        $stmt->bindParam(':name', $boardName);
                        $stmt->bindParam(':id', $boardID);
                        $stmt->execute();
                        $response['status'] = 'OK';
                        $response['boardName'] = $boardName;
                        $response['boardID'] = $boardID;
                    }
                }
                else{
                    $response['status'] = 'NO';
                    $response['error'] = 'Invalid board access';
                    $response['boardName'] = $boardName;
                    $response['boardID'] = $boardID;
                }
                $conn = NULL;
                echo json_encode($response);
                break;
            //-------------------------------------------------------------------------
        }
    }
}