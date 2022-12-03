<?php
session_start();

// if the user signed in go to workspaces
if (isset($_SESSION['validUser']) and $_SESSION['validUser']){
    header('location: workspaces.php');
}

require '../common/functions.php';
include '../common/mail.php';
$errors = "";
$success = "";
$email = "";
$name = "";
$password = "";
$rePassword = "";

if ($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET['resend']) and $_GET['resend']){
    unset($_GET['resend']);
    // generate random four digits and send it to email
    $random = randomCode();
    $_SESSION['tryCount'] = 0;
    $_SESSION['random'] = $random;
    send_mail($_SESSION['userEmail'], "Verification Code", "Your verification code is : $random");
    $success .= "Sent successfully.<br/>";

}elseif(isset($_SESSION['verify']) and $_SESSION['verify'] and $_SERVER['REQUEST_METHOD'] != "POST"){
    // if the user refresh the window while verifying end verification
    session_unset();
    session_destroy();
}

if (isset($_SESSION['verify']) and $_SESSION['verify'] and $_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['submit']) and $_POST['submit'] === 'Verify'){
    $input = test_input($_POST['verificationCode']);
    if ($input != $_SESSION['random']){
        $_SESSION['tryCount']++;
        if ($_SESSION['tryCount'] == 3){
            session_unset();
            session_destroy();
            $errors .= "You lost your attempts.<br/>Register again.<br/>";
        }else {
            $errors .= "Wrong Verification code.<br/>Try again.<br/>";
        }
    }else{
        try {

            $conn = connectDB();
            $prep = "insert into `users` (`email`, `name`, `password`) values (:email, :name, :password)";
            $stmt = $conn->prepare($prep);
            $stmt->bindParam(':email', $_SESSION['userEmail']);
            $stmt->bindParam(':name', $_SESSION['userName']);
            $stmt->bindParam(':password', $_SESSION['userPassword']);
            $_SESSION['userPassword'] = password_hash($_SESSION['userPassword'], PASSWORD_DEFAULT);
            $result = $stmt->execute();
            $conn = NULL;
            if ($result){
                $success .= "Your account has been created successfully.<br/>";
            }else{
                $errors .= "Sorry. Failed in database.<br/>";
            }
        }catch (PDOException $e){
            $errors .= "Sorry. Failed in database.<br/>";
        }
        session_unset();
        session_destroy();
    }
}

if (!isset($_SESSION['verify'])) $_SESSION['verify'] = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['submit']) and $_POST['submit'] === 'Continue'){

    // validate user inputs

    if (isset($_POST['email'])) $email = test_input($_POST['email']);
    if (isset($_POST['pass'])) $password = test_input($_POST['pass']);
    if (isset($_POST['rePass'])) $rePassword = test_input($_POST['rePass']);
    if (isset($_POST['name'])) $name = test_input($_POST['name']);

    if(empty($email)){
        $errors .= "Email is required.<br/>";
    }elseif (empty($name)){
        $errors .= "Name is required.<br/>";
    }elseif (!preg_match("/^[a-zA-Z]+\w{4,}/", $name)){
        $errors .= "Name must be at least 5 characters with leading letter.<br/>";
    }elseif (empty($password)){
        $errors .= "Password is required.<br/>";
    }elseif (empty($rePassword)){
        $errors .= "Retype Password is required.<br/>";
    }elseif (checkEmail($email) !== true){
        $errors .= checkEmail($email);
    }elseif (!preg_match("/\w{5,}/", $password)){
        $errors .= "Invalid email or password.<br/>";
    }elseif ($password !== $rePassword){
        $errors .= "Password and confirm password does not match.<br/>";
    }else{
        try { // if inputs valid check database
            $conn = connectDB();
            $prep = "select * from `users` where `email` = :email";
            $stmt = $conn->prepare($prep);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            if (count($result) > 0) $errors .= "This email is used<br/>";
            else {
                $prep = "select * from `users` where `name` = :name";
                $stmt = $conn->prepare($prep);
                $stmt->bindParam(':name', $name);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $result = $stmt->fetchAll();
                if (count($result) > 0) $errors .= "This name is used<br/>";
                else {
                    $_SESSION['verify'] = true;
                    $_SESSION['userEmail'] = $email;
                    $_SESSION['userName'] = $name;
                    $_SESSION['userPassword'] = $password;

                    // generate random four digits and send it to email
                    $random = randomCode();
                    $_SESSION['tryCount'] = 0;
                    $_SESSION['random'] = $random;
                    send_mail($email, "Verification Code", "Your verification code is : $random");
                }
            }
            $conn = NULL;
        }catch (PDOException $e){
            $errors .= "Sorry. Failed in connection to the database.<br/>";
        }catch (Exception $ex){
            $errors .= "Sorry. Failed in connection to the database.<br/>";
        }
    } // end else
} // end if (POST)

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
    <title>Sign up <?= Project::PROJ_NAME ?></title>
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

    <!--login form-->
    <div class="signupCard">
        <span class="desc">Sign up to use :<br/><strong><?= Project::PROJ_NAME ?></strong></span><br/>
        <?php if ($_SESSION['verify'] === false) {?>
        <div class="partOne">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="email"></label><input autofocus type="email" name="email" id="email" placeholder="Enter email" value="<?= $email ?>" required /><br/>
                <label for="name"></label><input type="text" name="name" id="name" placeholder="Enter your name" value="<?= $name ?>" required /><br/>
                <label for="pass"></label><input type="password" name="pass" id="pass" placeholder="Enter password" required /><br/>
                <label for="rePass"></label><input type="password" name="rePass" id="rePass" placeholder="Retype password" required /><br/>
                <input type="submit" name="submit" class="submit" value="Continue" /><br/>
            </form>
        </div>
        <?php }else{ ?>
        <div class="partTwo">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <p class="msgVerificationCode">We sent you a verification code to your email. type it here.</p>
                <a id="resend" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'].'?resend=ture'); ?>">send again</a>
                <label for="verificationCode"></label><input autofocus type="text" name="verificationCode" id="verificationCode" placeholder="2022" /><br/>
                <input type="submit" name="submit" class="submit" value="Verify"/>
            </form>
        </div>
        <?php } ?>
        <a id="signIn" href="login.php">sign in existing account.</a>
        <p class="errors"><?= $errors ?></p>
        <p class="success"><?= $success ?></p>
    </div>
</div>

<!--Footer section-->
<?php
include '../common/footer.php';
?>

</body>
</html>