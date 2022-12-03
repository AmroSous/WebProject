<?php
session_start();

// if the user logged in go to workspaces page
if (isset($_SESSION['validUser']) and $_SESSION['validUser']){
    header('location: workspaces.php');
}elseif(((isset($_SESSION['partTwo']) and $_SESSION['partTwo']) or (isset($_SESSION['partThree']) and $_SESSION['partThree'])) and $_SERVER['REQUEST_METHOD'] != "POST"){
    // if the user refresh the window while verifying end verification
    session_unset();
    session_destroy();
}

if (!isset($_SESSION['partTwo'])) $_SESSION['partTwo'] = false;
if (!isset($_SESSION['partThree'])) $_SESSION['partThree'] = false;
$errors = "";
$success = "";
$email = "";

include '../common/functions.php';
include '../common/mail.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['submit'])){
    if ($_POST['submit'] == "Send" and !$_SESSION['partTwo']){
        if (!isset($_POST['email']))
            $errors .= "Email is required.<br/>";
        else{
            $email = test_input($_POST['email']);
            $result = checkEmail($email);
            if ($result !== true){
                $errors .= $result;
            }else{
                // check database
                try {
                    $prep = "select * from `users` where `email` = :email";
                    $conn = connectDB();
                    $stmt = $conn->prepare($prep);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $result = $stmt->fetchAll();
                    if (count($result) <= 0) {
                        $errors .= "Email not found.<br/>";
                    } else {
                        // send recover code.
                        $random = randomCode();
                        if (send_mail($email, "Verification Code", "Your verification code is : $random")) {
                            $_SESSION['userEmail'] = $email;
                            $_SESSION['tryCount'] = 0;
                            $_SESSION['random'] = $random;
                            $_SESSION['partTwo'] = true;
                        } else {
                            $errors .= "Sorry. Failed in send Verification code.<br/>";
                        }
                    }
                }catch (PDOException $e){
                    $errors .= "Failed in connection to database.<br/>";
                }
            }
        }
    }elseif ($_POST['submit'] == "Get Password" and $_SESSION['partTwo']){

        // check recovery input
        if (!isset($_POST['recoverCode'])){
            $errors .= "Enter recovery code.<br/>";
        }else {
            $input = test_input($_POST['recoverCode']);
            if ($input != $_SESSION['random']) {
                $_SESSION['tryCount']++;
                if ($_SESSION['tryCount'] == 3) {
                    $_SESSION['partTwo'] = false;
                    $_SESSION['partTwo'] = false;
                    unset($_SESSION['userEmail']);
                    unset($_SESSION['random']);
                    unset($_SESSION['tryCount']);
                    $errors .= "You lost your attempts.<br/>Try again.<br/>";
                } else {
                    $errors .= "Wrong Recovery code.<br/>Try again.<br/>";
                }
            }else {
                /// change password.
                $_SESSION['partTwo'] = false;
                $_SESSION['partThree'] = true;
            }
        }
    }elseif ($_POST['submit'] == "Change Password" and $_SESSION['partThree']){
        // check inputs
        $password = "";
        $rePassword = "";
        if (!(isset($_POST['password']) and isset($_POST['rePassword']))){
            $errors .= "Fill all fields.<br/>";
        }else{

            $password = test_input($_POST['password']);
            $rePassword = test_input($_POST['rePassword']);
            if ($password != $rePassword){
                $errors .= "Password and Confirm Password mismatch.<br/>";
            }else{
                try {
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $conn = connectDB();
                    $prep = "update `users` set `password` = :password where `email` = :email";
                    $stmt = $conn->prepare($prep);
                    $stmt->bindParam(':email', $_SESSION['userEmail']);
                    $stmt->bindParam(':password', $password);
                    $result = $stmt->execute();
                    $conn = NULL;
                    if ($result) {
                        $success .= "Your Password Updated successfully.<br/>";
                    } else {
                        $errors .= "Sorry. Failed in changing password.<br/>";
                    }
                }catch (PDOException $e){
                    $errors .= "Failed in connection to database.<br/>";
                }
                session_unset();
                session_destroy();
                unset($_POST['email']);
                unset($_POST['password']);
                unset($_POST['rePassword']);
            }
        }
    }
}

?>
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <base href="forgetPassword.php"/>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="../images/appIcon.png"/>
    <link rel="stylesheet" type="text/css" href="../styles/loginStyle.css"/> <!--style for login page-->
    <title>Change Password <?= Project::PROJ_NAME ?></title>
</head>
<body>

<?php include '../common/nav_bar.php'?>

<img src="../images/plan2.png" alt="plan image" class="backImgTwo"/>
<div class="middle">
    <table class="icon-title">
        <tr>
            <td><img src="../images/appIconBig.png" alt="app icon" id="appIcon"/></td>
            <td><h1 id="headerTitle"><?= Project::PROJ_NAME ?></h1></td>
        </tr>
    </table>

    <!--recover password form-->
    <div class="recoverPasswordCard">
        <?php if ((!isset($_SESSION['partTwo']) and !isset($_SESSION['partThree'])) or isset($_SESSION['partTwo']) and isset($_SESSION['partThree']) and !$_SESSION['partThree'] and !$_SESSION['partTwo']) {?>
        <div class="partOne">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <br/><br/>
                <label for="email" class="labelRecoverCode">Enter your email to recover the password.</label>
                <input autofocus type="email" name="email" id="email" placeholder="Enter your email" value="<?= $email ?>" required /><br/>
                <input type="submit" name="submit" class="submit" value="Send" /><br/>
            </form>
        </div>
        <?php }elseif(isset($_SESSION['partTwo']) and $_SESSION['partTwo']){?>
        <div class="partTwo">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="recoverCode" class="labelRecoverCode">Enter Recovery Code sent to your email.<br/></label>
                <input autofocus type="text" name="recoverCode" id="recoverCode" placeholder="2022" required /><br/>
                <input type="submit" name="submit" class="submit" value="Get Password" /><br/>
            </form>
        </div>
        <?php }elseif (isset($_SESSION['partThree']) and $_SESSION['partThree']){ ?>
            <div class="partThree">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <br/><br/>
                    <label for="password"></label><input type="password" name="password" id="password" placeholder="New Password" autofocus required />
                    <br/>
                    <label for="rePassword"></label><input type="password" name="rePassword" id="rePassword" placeholder="Confirm Password" required />
                    <br/>
                    <input type="submit" name="submit" class="submit" value="Change Password" />
                    <br/>
                </form>
            </div>
        <?php } ?>
        <a href="login.php" id="backToLogin"> Back to SIGN IN.</a>
        <p class="errors"><?= $errors ?></p>
        <p class="success"><?= $success ?></p>
    </div>
</div>

<!--Footer section-->
<?php include '../common/footer.php'?>

</body>
</html>