<?php
session_start();
include("../connect.php");

// authenticate session
if(isset($_SESSION["email"])) {
    $email = $_SESSION["email"];

    $authentication = mysqli_query($connect, "SELECT * FROM `tbl_user` WHERE `email` = '$email'");
    $fetch = mysqli_fetch_assoc($authentication);
    $acount_type = $fetch["account_type"];

    if($account_type != 1) {
        echo "<script>window.location.href='../forbidden'</script>";
    }
}

include("nav.php");
?>
<html>
<head>
</head>
<body>
<?php
    if(!empty($_GET["getDelete"])) {
        include("confirm_delete.php");
    }
    if(!empty($_GET["notify"])) {
        echo "<h3><span style='color: green;'>{$_GET["notify"]}</span></h3>";
    }
    if(empty($_GET["getUpdate"])) {
?>
    <div id="retriever"><?php include("retriever.php"); ?></div>
<?php
    } else {
        include("update_user.php");
    }
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        setInterval(() => {
            $("#retriever").load("retriever.php");
        }, 1000);
    </script>
</body>
</html>