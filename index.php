<?php
// 1
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 2
require 'vendor/autoload.php';

include("connect.php");

include("nav.php");

$first_name = $middle_name = $last_name = $gender = $preffix = $seven_digit = $email = "";
$first_nameErr = $middle_nameErr = $last_nameErr = $genderErr = $preffixErr = $seven_digitErr = $emailErr = "";

function random_password($length = 5) {
    $str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $shuffled = substr(str_shuffle($str), 0, $length);
    return $shuffled;
}

if(isset($_POST["btnRegister"])) {
    if(empty($_POST["first_name"])) {
        $first_nameErr = "* Required!";
    } else {
        $first_name = $_POST["first_name"];
    }

    if(empty($_POST["middle_name"])) {
        $middle_nameErr = "* Required!";
    } else {
        $middle_name = $_POST["middle_name"];
    }

    if(empty($_POST["last_name"])) {
        $last_nameErr = "* Required!";
    } else {
        $last_name = $_POST["last_name"];
    }

    if(empty($_POST["gender"])) {
        $genderErr = "* Required!";
    } else {
        $gender = $_POST["gender"];
    }

    if(empty($_POST["preffix"])) {
        $preffixErr = "* Required!";
    } else {
        $preffix = $_POST["preffix"];
    }

    if(empty($_POST["seven_digit"])) {
        $seven_digitErr = "* Required!";
    } else {
        $seven_digit = $_POST["seven_digit"];
    }

    if(empty($_POST["email"])) {
        $emailErr = "* Required!";
    } else {
        $email = $_POST["email"];
    }

    if($first_name && $middle_name && $last_name && $gender && $preffix && $seven_digit && $email) {
        if(!preg_match("/^[a-zA-Z ]*$/", $first_name)) {
            $first_nameErr = "* Letra lang at space ang kailangan";
        } else {
            $count_first_name_string = strlen($first_name);
            if($count_first_name_string < 2) {
                $first_nameErr = "* Masyadong maikli ang first name mo kapatid";
            } else {
                $count_middle_name_string = strlen($middle_name);
                if($count_middle_name_string < 2) {
                    $middle_nameErr = "* Masyadong maikli ang middle name mo kapatid";
                } else {
                    $count_last_name_string = strlen($last_name);
                    if($count_last_name_string < 2) {
                        $last_nameErr = "* Masyadong maikli ang last name mo kapatid";
                    } else {
                        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $emailErr = "* Invalid email format";
                        } else {
                            $count_seven_digit_string = strlen($seven_digit);
                            if($count_seven_digit_string < 7) {
                                $seven_digitErr = "* brad kulang ang seven digit mo";
                            } else {
                                $password = random_password(8);

                                // 3
                                // https://www.campcodes.com/tutorials/php-tutorials/8007/how-to-send-email-to-gmail-using-phpmailer-in-php/
                                $mail = new PHPMailer(true); //

                                //Server settings
                                $mail->isSMTP(); //
                                $mail->Host = 'smtp.gmail.com'; //
                                $mail->SMTPAuth = true; //
                                $mail->Username = 'mercado.joshua.web@gmail.com'; //
                                $mail->Password = '***********'; //
                                $mail->SMTPOptions = array(
                                    'ssl' => array(
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                    )
                                );
                                $mail->SMTPSecure = 'ssl'; //
                                $mail->Port = 465; //

                                //Send Email
                                $mail->setFrom('mercado.joshua.web@gmail.com'); // kanino galing

                                //Recipients
                                $mail->addAddress($email); // email ng pag-sesendan
                                $mail->addReplyTo('mercado.joshua.web@gmail.com');

                                //Content
                                $subject = "Your default password";
                                $message = "Your password is: {$password}";
                                $mail->isHTML(true);
                                $mail->Subject = $subject;
                                $mail->Body = $message;

                                // $mail->send(); // comment this, it duplicates the message

                                if(!$mail->send()) {
                                    echo 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
                                } else {
                                    $sql = "INSERT INTO `tbl_user` (`first_name`, `middle_name`, `last_name`, `gender`, `preffix`, `seven_digit`, `email`, `password`, `account_type`) VALUES ('$first_name', '$middle_name', '$last_name', '$gender', '$preffix', '$seven_digit', '$email', '$password', '2')";
                                    mysqli_query($connect, $sql);

                                    echo "<script>window.location.href='success.php'</script>";
                                }

                            }
                        }
                    }
                }
            }
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
    <h2>Add User Information</h2>
        <form method="POST">
            <table border="1">
                <tr>
                    <td>
                        <input type="text" name="first_name" placeholder="First Name" value="<?php echo $first_name; ?>">
                        <span class="error"><?php echo $first_nameErr; ?></span>
                    </td>
                    <td>
                        <input type="text" name="last_name" placeholder="Last Name" value="<?php echo $last_name; ?>">
                        <span class="error"><?php echo $last_nameErr; ?></span>
                    </td>
                    <td>
                        <input type="text" name="middle_name" placeholder="Middle Name" value="<?php echo $middle_name; ?>">
                        <span class="error"><?php echo $middle_nameErr; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <select name="gender">
                            <option name="gender" value="">Select Gender</option>
                            <option name="gender" value="Male" <?php if($gender == "Male") { echo "selected"; }?>>Male</option>
                            <option name="gender" value="Female" <?php if($gender == "Female") { echo "selected"; }?>>Female</option>
                        </select>
                        <span class="error"><?php echo $genderErr; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <select name="preffix">
                            <option name="preffix" id="preffix">Network Provider (Globe, Sun, Smart, TM, TNT)</option>
                            <option name="preffix" id="preffix" value="0936" <?php if($preffix == "0936") { echo "selected"; }?>>0936</option>
                            <option name="preffix" id="preffix" value="0902" <?php if($preffix == "0902") { echo "selected"; }?>>0902</option>
                            <option name="preffix" id="preffix" value="0905" <?php if($preffix == "0905") { echo "selected"; }?>>0905</option>
                            <option name="preffix" id="preffix" value="0948" <?php if($preffix == "0948") { echo "selected"; }?>>0948</option>
                        </select>
                        <span class="error"><?php echo $preffixErr; ?></span>
                    </td>
                    <td>
                        <input type="text" name="seven_digit" maxlength="7" onkeypress="return isNumberKey(event);" placeholder="Other Seven Digit" value="<?php echo $seven_digit; ?>">
                        <span class="error"><?php echo $seven_digitErr; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="email" name="email" placeholder="example@gmail.com" value="<?php echo $email; ?>">
                        <span class="error"><?php echo $emailErr; ?></span>
                    </td>
                    <td><input type="submit" name="btnRegister" value="Register"></td>
                </tr>
            </table>
        </form>
        <script>
            function isNumberKey(evt) {
                let charCode = (evt.which) ? evt.which : event.keyCode;

                if(charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }

                return true;
            }
        </script>
    </body>
</html>