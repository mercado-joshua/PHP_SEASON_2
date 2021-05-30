<?php
session_start();
include("connect.php");
include("nav.php");

if(isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
    $query_account_type = mysqli_query($connect, "SELECT * FROM `tbl_user` WHERE `email` = '$email'");
    $get_account_type = mysqli_fetch_assoc($query_account_type);

    $account_type = $get_account_type["account_type"];

    if($account_type == 1) {
        echo "<script>window.location.href='admin'</script>";
    } else {
        echo "<script>window.location.href='user'</script>";
    }
}

date_default_timezone_set('Asia/Manila');

// echo "current timezone: " . date_default_timezone_get() . "<br>";
// echo "current time: " . date("d-m-Y H:i:s");

$date_now = date("m/d/Y");
$time_now = date("h:i a");

$notify = $attempt = $log_time = "";
$email = $password = "";
$emailErr = $passwordErr = "";

$end_time = date("h:i A", strtotime("+15 minutes", strtotime($time_now))); // current time + 15 minutes

if(isset($_POST["btnLogin"])) {
    if(empty($_POST["email"])) {
        $emailErr = "* Required!";
    } else {
        $email = $_POST["email"];
    }

    if(empty($_POST["password"])) {
        $passwordErr = "* Required!";
    } else {
        $password = $_POST["password"];
    }

    if($email && $password) {
        $check_email = mysqli_query($connect, "SELECT * FROM `tbl_user` WHERE `email` = '$email'");
        $check_row = mysqli_num_rows($check_email);

        if($check_row > 0) {
            $fetch = mysqli_fetch_assoc($check_email);
            $db_password = $fetch["password"];
            $db_attempt = $fetch["attempt"];
            $db_log_time = strtotime($fetch["log_time"]);
            $my_log_time = $fetch["log_time"];
            $new_time = strtotime($time_now);
            $account_type = $fetch["account_type"];

            if($account_type == "1") { // para kay admin
                if($db_password == $password) {
                    $_SESSION["email"] = $email;
                    echo "<script>window.location.href='admin'</script>";
                } else {
                    $passwordErr = "* Hi Admin! your password is incorrect.";
                }
            } else { // para sa mga users
                if($db_log_time <= $new_time) {
                    if($db_password == $password) { // pag tama ung info
                        $_SESSION["email"] = $email;
                        mysqli_query($connect, "UPDATE `tbl_user` SET `attempt` = '', `log_time` = '' WHERE `email` = '$email'");
                        echo "<script>window.location.href='user'</script>";
                    } else {
                        $attempt = (int)$db_attempt + 1;
                        if($attempt >= 3) { // pag umabot o sumobra sa attempt
                            $attempt = 3;
                            mysqli_query($connect, "UPDATE `tbl_user` SET `attempt` = '$attempt', `log_time` = '$end_time' WHERE `email` = '$email'");
                            $notify = "You already reached three (3) attempts to login. Please Login after 15 minutes: <b>{$end_time}</b>";
                        } else { // kung may chance pa
                            mysqli_query($connect, "UPDATE `tbl_user` SET `attempt` = '$attempt' WHERE `email` = '$email'");
                            $passwordErr = "* Password is incorrect!";
                            $notify = "Login Attempt: <b>{$attempt}</b>";
                        }
                    }

                } else {
                    $notify = "* I'm sorry, you have to wait until: <b>{$my_log_time}</b> before login.";
                }
            }
        } else {
            $emailErr = "* email is not registered!";
        }
    }
}
?>
<html>
    <head>
        <style>
            .error {
                color: red;
                font-style: italic;
            }
        </style>
    </head>
    <body>
    <h2>Login</h2>
    <span class="error"><?php echo $notify; ?></span>
        <form method="POST">
            <table border="1">
                <tr>
                    <td colspan="2">
                        <input type="text" name="email" placeholder="Email" value="<?php echo $email; ?>">
                        <span class="error"><?php echo $emailErr; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="password" name="password" value="">
                        <span class="error"><?php echo $passwordErr; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" name="btnLogin" value="Login">
                    </td>
                    <td>
                        <a href="?forgot=<?php echo md5(1, 9); ?>"><button>Forgot Password</button></a>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>