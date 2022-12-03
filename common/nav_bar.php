
<!DOCTYPE html>

<link rel="stylesheet" href="../styles/nav_bar_style.css" />
<script src="../jscripts/popupMenu.js"></script>

<?php if (isset($_SESSION['validUser']) and $_SESSION['validUser']){ ?>
    <div class="nav_bar_one">
        <ul class="nav_bar">
            <li class="left home"><a class="active" href="../pages/home.php"><?= Project::PROJ_NAME ?></a></li>
            <li class="left"><a href="../pages/workspaces.php">Workspaces</a></li>
            <li class="right"><div class="userImg" title="Profile" onclick="showPopup(this, 'userProfile')"></div></li>
        </ul>
        <div class="userProfile popup">
            <div class="userInfo">
                <table>
                    <tr>
                        <td><div class="userImg" ></div></td>
                        <td>
                            <span class="userName"><?= $_SESSION['userName'] ?></span>
                            <br/>
                            <span class="userEmail"><?= $_SESSION['userEmail'] ?></span>
                        </td>
                    </tr>
                </table>
            </div>
            <hr/>
            <a class="profileAnchor" href="../pages/setting.php">Setting</a>
            <a class="profileAnchor" href="../common/logout.php">log out</a>
        </div>
    </div>
<?php }else{ ?>
    <div class="nav_bar_two">
        <ul class="nav_bar">
            <li class="left home active"><a class="active" href="../pages/home.php"><?= Project::PROJ_NAME ?></a></li>
            <li class="left"><a href="../pages/login.php">SIGN IN</a></li>
        </ul>
    </div>
<?php } ?>
