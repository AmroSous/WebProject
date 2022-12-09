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
                if (empty($_POST['content'])){
                    $response['error'] = 'Empty Field';
                    $response['status'] = 'NO';
                }else{
                    $conn = connectDB();
                    $prep = "insert into `boards` (`name`, `workspace_id`) values (:name, :ws_id)";
                    $stmt = $conn->prepare($prep);
                    $name = test_input($_POST['content']);
                    $ws_id = test_input($_SESSION['workspaceId']);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':ws_id', $ws_id);
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
            case ListOrder:
                echo "list order";
                break;
            //-------------------------------------------------------------------------
            case CardOrder:
                echo "card order";
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
        }
    }
}