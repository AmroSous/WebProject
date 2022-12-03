<!DOCTYPE html>

<!--style footer-->
<link rel="stylesheet" href="../styles/footerStyle.css" />

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