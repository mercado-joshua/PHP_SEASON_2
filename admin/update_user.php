<?php
include("../connect.php");

$new_fname = $new_mname = $new_lname = $new_gender = $new_preffix = $new_seven_digit = $new_email = "";
$new_fnameErr = $new_mnameErr = $new_lnameErr = $new_genderErr = $new_preffixErr = $new_seven_digitErr = $new_emailErr = "";

$user_id = $_GET["user_id"];
$sql = "SELECT * FROM `tbl_user` WHERE `id_user` = '$user_id'";
$get_record = mysqli_query($connect, $sql);

while($row = mysqli_fetch_assoc($get_record)) {

    $db_fname = $row["first_name"];
    $db_mname = $row["middle_name"];
    $db_lname = $row["last_name"];
    $db_gender = ucfirst($row["gender"]);
    $db_preffix = $row["preffix"];
    $db_seven_digit = $row["seven_digit"];
    $db_email = $row["email"];
    $db_password = $row["password"];
}


if(isset($_POST["btnUpdate"])) {
    if(empty($_POST["new_fname"])) {
        $new_fnameErr = "* Required!";
    } else {
        $new_fname = $_POST["new_fname"];
        $db_fname = $new_fname;
    }

    if(empty($_POST["new_lname"])) {
        $new_lnameErr = "* Required!";
    } else {
        $new_lname = $_POST["new_lname"];
        $db_lname = $new_lname;
    }

    if(empty($_POST["new_mname"])) {
        $new_mnameErr = "* Required!";
    } else {
        $new_mname = $_POST["new_mname"];
        $db_mname = $new_mname;
    }

    if(empty($_POST["new_seven_digit"])) {
        $new_seven_digitErr = "* Required!";
    } else {
        $new_seven_digit = $_POST["new_seven_digit"];
        $db_seven_digit = $new_seven_digit;
    }

    if(empty($_POST["new_email"])) {
        $new_emailErr = "* Required!";
    } else {
        $new_email = $_POST["new_email"];
        $db_email = $new_email;
    }

    $db_gender = $_POST["new_gender"];
    $db_preffix = $_POST["new_preffix"];

    echo "okey";

    if($new_fname && $new_mname && $new_lname && $new_seven_digit && $new_email) {
        echo "okey rin ba?";
        if(!preg_match("/^[a-zA-Z ]*$/", $new_fname)) {
            $new_fnameErr = "* Letra lang at space ang kailangan";
        } else {
            $count_new_fname_string = strlen($new_fname);
            if($count_new_fname_string < 2) {
                $new_fnameErr = "* Masyadong maikli ang first name mo kapatid";
            } else {
                $count_new_mname_string = strlen($new_mname);
                if($count_new_mname_string < 2) {
                    $new_mnameErr = "* Masyadong maikli ang middle name mo kapatid";
                } else {
                    $count_new_lname_string = strlen($new_lname);
                    if($count_new_lname_string < 2) {
                        $new_lnameErr = "* Masyadong maikli ang last name mo kapatid";
                    } else {
                        if(!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                            $new_emailErr = "* Invalid email format";
                        } else {
                            $count_new_seven_digit_string = strlen($new_seven_digit);
                            if($count_new_seven_digit_string < 7) {
                                $new_seven_digitErr = "* brad kulang ang seven digit mo";
                            } else {

                                $sql = "UPDATE `tbl_user` SET
                                `first_name` = '$db_fname',
                                `middle_name` = '$db_mname',
                                `last_name` = '$db_lname',
                                `gender` = '$db_gender',
                                `preffix` = '$db_preffix',
                                `seven_digit` = '$db_seven_digit',
                                `email` = '$db_email'
                                WHERE `id_user` = '$user_id'";

                                $encrypted = md5(rand(1,9));

                                mysqli_query($connect, $sql);
                                echo "<script>window.location.href='view?$encrypted&&notify=Record Has Been Updated!'</script>";

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
    <h2>Update User Information</h2>
        <form method="POST">
            <table border="1">
                <tr>
                    <td>
                        <input type="text" name="new_fname" placeholder="First Name" value="<?php echo $db_fname; ?>">
                        <span class="error"><?php echo $new_fnameErr; ?></span>
                    </td>
                    <td>
                        <input type="text" name="new_lname" placeholder="Last Name" value="<?php echo $db_lname; ?>">
                        <span class="error"><?php echo $new_lnameErr; ?></span>
                    </td>
                    <td>
                        <input type="text" name="new_mname" placeholder="Middle Name" value="<?php echo $db_mname; ?>">
                        <span class="error"><?php echo $new_mnameErr; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <select name="new_gender">
                            <option name="new_gender" value="">Select Gender</option>
                            <option name="new_gender" value="Male" <?php if($db_gender == "Male") { echo "selected"; }?>>Male</option>
                            <option name="new_gender" value="Female" <?php if($db_gender == "Female") { echo "selected"; }?>>Female</option>
                        </select>
                        <span class="error"><?php echo $new_genderErr; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <select name="new_preffix">
                            <option name="new_preffix" id="new_preffix">Network Provider (Globe, Sun, Smart, TM, TNT)</option>
                            <option name="new_preffix" id="new_preffix" value="0936" <?php if($db_preffix == "0936") { echo "selected"; }?>>0936</option>
                            <option name="new_preffix" id="new_preffix" value="0902" <?php if($db_preffix == "0902") { echo "selected"; }?>>0902</option>
                            <option name="new_preffix" id="new_preffix" value="0905" <?php if($db_preffix == "0905") { echo "selected"; }?>>0905</option>
                            <option name="new_preffix" id="new_preffix" value="0948" <?php if($db_preffix == "0948") { echo "selected"; }?>>0948</option>
                        </select>
                        <span class="error"><?php echo $new_preffixErr; ?></span>
                    </td>
                    <td>
                        <input type="text" name="new_seven_digit" maxlength="7" onkeypress="return isNumberKey(event);" placeholder="Other Seven Digit" value="<?php echo $db_seven_digit; ?>">
                        <span class="error"><?php echo $new_seven_digitErr; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="email" name="new_email" placeholder="example@gmail.com" value="<?php echo $db_email; ?>">
                        <span class="error"><?php echo $new_emailErr; ?></span>
                    </td>
                    <td><input type="submit" name="btnUpdate" value="Update"></td>
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