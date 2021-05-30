<?php
session_start();
include("../connect.php");
include("nav.php");

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
?>