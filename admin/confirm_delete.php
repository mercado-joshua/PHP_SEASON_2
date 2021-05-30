<?php
include("../connect.php");

$user_id = $_GET["user_id"];
$sql = "SELECT * FROM `tbl_user` WHERE `id_user` = '$user_id'";
$get_record = mysqli_query($connect, $sql);

$row = mysqli_fetch_assoc($get_record);

$db_fname = $row["first_name"];
$db_mname = $row["middle_name"];
$db_lname = $row["last_name"];
$db_gender = $row["gender"];

$gender_preffix = "";

if($db_gender == "Male") {
    $gender_preffix = "Mr.";
} else {
    $gender_preffix = "Ms.";
}

$full_name = $gender_preffix . " " . ucfirst($db_fname) . "," . ucfirst($db_lname) . " " . ucfirst($db_mname[0]) . ".";

if(isset($_POST["btnDelete"])) {
    $sql = "DELETE FROM `tbl_user` WHERE `id_user` = '$user_id'";
    $encrypted = md5(rand(1,9));

    mysqli_query($connect, $sql);
    echo "<script>window.location.href='view?$encrypted&&notify=Record Has Been Deleted!'</script>";
}
?>
<htmL>
    <head>
    </head>
    <body>
        <form method="POST">
            <table border="1">
                <tr>
                    <td colspan="2">You are about to delete this user: <?php echo $full_name; ?></td>
                </tr>
                <tr>
                    <td><input type="submit" name="btnDelete" value="Delete"></td>
                    <td><a href="?"><button>Cancel</button></a></td>
                </tr>
            </table>
        </form>
    </body>
</html>