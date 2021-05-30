<?php
session_start();
include("../connect.php");

// authenticate session
if(isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
} else {
    echo "<script>window.location.href='../'</script>";
}

include("nav.php");