<?php
session_start();

// if the user already signed in
if (isset($_SESSION['validUser']) and $_SESSION['validUser']){
    header('location: workspaces.php');
}

require '../common/functions.php';

$errors = "";
$email = "";
$password = "";
$_SESSION['validUser'] = false;

if (!isset($_SESSION['userId']) or !isset($_SESSION['userName']) or !isset($_SESSION['userEmail'])){
    if($_SERVER['REQUEST_METHOD'] == "POST"){   // if user hits log in button

        // validate user inputs
        if (isset($_POST['email'])) $email = test_input($_POST['email']);
        if (isset($_POST['pass'])) $password = test_input($_POST['pass']);
        if(empty($email)){
            $errors += "Email is required.<br/>";
        }elseif (empty($password)){
            $errors .= "Password is required.<br/>";
        }elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors .= "Invalid email or password.<br/>";
        }elseif (!preg_match("/[a-zA-Z\d]{5,}/", $password)){
            $errors .= "Invalid email or password.<br/>";
        }else{
            try { // if inputs valid check database
                $conn = connectDB();
                $prep = "select * from `users` where `email` = :email";
                $stmt = $conn->prepare($prep);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $result = $stmt->fetchAll();
                $conn = NULL;
                if(count($result) == 1){
                    if (password_verify($password, $result[0]['password'])){
                        $_SESSION['validUser'] = true;
                        $_SESSION['userId'] = $result[0]['id'];
                        $_SESSION['userName'] = $result[0]['name'];
                        $_SESSION['userEmail'] = $result[0]['email'];
                        header("location: Workspaces.php");
                    }else{
                        $errors .= "Invalid email or password.";
                    }
                }else{
                    $errors .= "Invalid email or password.<br/>";
                }
            }catch (PDOException $e){
                $errors .= "Sorry. Failed in connection to the database.<br/>";
            }catch (Exception $ex){
                $errors .= "Sorry. Failed in connection to the database.<br/>";
            }
        } // end else
    } // end if (POST)
}
?>

<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <base href="login.php"/>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="../images/appIcon.png"/>
    <link rel="stylesheet" type="text/css" href="../styles/loginStyle.css"/> <!--style for login page-->
    <title>Log in <?= Project::PROJ_NAME ?></title>
</head>
<body>

    <?php include '../common/nav_bar.php'; ?>
    <img src="../images/plan2.png" alt="plan image" class="backImgTwo"/>
    <div class="middle">
        <table class="icon-title">
            <tr>
                <td><img src="../images/appIconBig.png" alt="app icon" id="appIcon"/></td>
                <td><h1 id="headerTitle"><?= Project::PROJ_NAME ?></h1></td>
            </tr>
        </table>

        <!--login form-->
        <div class="loginCard">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <span class="desc">Log in to continue to:<br/><strong><?= Project::PROJ_NAME ?></strong></span><br/>
                <label for="email"></label><input type="email" name="email" id="email" placeholder="Enter email" value="<?= $email ?>" required /><br/>
                <label for="pass"></label><input type="password" name="pass" id="pass" placeholder="Enter password" value="<?= $password ?>" required /><br/>
                <input type="submit" name="submit" class="submit" value="Continue" /><br/>
            </form>
            <div class="flexbox-container">
                <div class="flexbox-content">
                    <a href="forgetPassword.php"> Forget password ?</a>
                </div>
                <div class="flexbox-content">
                    <a href="signup.php"> Sign up for an account.</a>
                </div>
            </div>
            <p class="errors"><?= $errors ?></p>
        </div>
    </div>

    <!--Footer section-->
    <?php include '../common/footer.php'?>

</body>
</html>