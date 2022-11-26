<?php
require '../common/project.php';
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
    <link rel="stylesheet" type="text/css" href="../styles/footerStyle.css"/> <!--style for footer-->
    <title><?= Project::PROJ_NAME ?></title>
</head>
<body>
    <img src="../images/plan2.png" alt="plan image" class="backImgTwo"/>
    <div class="middle">
        <table>
            <tr>
                <td><img src="../images/appIconBig.png" alt="app icon" id="appIcon"/></td>
                <td><h1 id="headerTitle"><?= Project::PROJ_NAME ?></h1></td>
            </tr>
        </table>

        <!--login form-->
        <div class="loginCard">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <span class="desc">Log in to continue to:<br/><strong><?= Project::PROJ_NAME ?></strong></span><br/>
                <input type="email" name="email" id="email" placeholder="Enter email" required /><br/>
                <input type="password" name="pass" id="pass" placeholder="Enter password" required /><br/>
                <input type="submit" name="submit" id="submit" value="Continue" /><br/>
            </form>
            <table>
                <tr>
                    <td><a href="">Forget password?</a></td>
                    <td><a href="">Sign up for an account</a></td>
                </tr>
            </table>
        </div>
    </div>

    <!--Footer section-->
    <footer class="footer">
        <table>
            <tr>
                <td><img src="../images/appIconBig.png" alt="app icon" id="footerIcon"/></td>
                <td><span id="footerTitle"><?= Project::PROJ_NAME ?></span></td>
            </tr>
        </table>
        <a href="" class="seeMore">About <?= Project::PROJ_NAME ?><br/><span>what's behind the boards.</span></a>
        <a href="" class="seeMore">Jobs<br/><span>Learn about open roles on the <?= Project::PROJ_NAME ?> team.</span></a>
        <a href="" class="seeMore">Apps<br/><span>Download the <?= Project::PROJ_NAME ?> App for your Desktop or Mobile Devices.</span></a>
        <a href="" class="seeMore">Contact us<br/><span>Need anything? Get in touch and we can help.</span></a>
        <div class="flexbox"></div>
        <hr/>
        <span class="copyrights">Copyright &copy; <?= date("Y") . " " . Project::PROJ_NAME; ?></span>
    </footer>
</body>
</html>