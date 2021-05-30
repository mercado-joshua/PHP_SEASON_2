<?php
include("../connect.php");
?>
<html>
<head>
</head>
<body>
    <h2>View User Information</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Gender</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Password</th>
            <th colspan="2">Action</th>
        </tr>

<?php
$sql = "SELECT * FROM `tbl_user` WHERE `account_type` = '2'";
$retrieve_query = mysqli_query($connect, $sql);

while($row = mysqli_fetch_assoc($retrieve_query)){

    $user_id = $row["id_user"];

    $db_fname = $row["first_name"];
    $db_mname = $row["middle_name"];
    $db_lname = $row["last_name"];
    $db_gender = ucfirst($row["gender"]);
    $db_preffix = $row["preffix"];
    $db_seven_digit = $row["seven_digit"];
    $db_email = $row["email"];
    $db_password = $row["password"];

    $full_name = ucfirst($db_fname) . ", " . ucfirst($db_lname) . " " . ucfirst($db_mname[0]) . ".";
    $contact = $db_preffix.$db_seven_digit;

    $jScript = md5(rand(1,9));
    $newScript = md5(rand(1,9));
    $getUpdate = md5(rand(1,9));
    $getDelete = md5(rand(1,9));

    echo "<tr>
        <td>{$full_name}</td>
        <td>{$db_gender}</td>
        <td>{$contact}</td>
        <td>{$db_email}</td>
        <td>{$db_password}</td>
        <td><a href='view?jScript=$jScript&&newScript=$newScript&&getUpdate=$getUpdate&&user_id=$user_id'><button>Update</button></a></td>
        <td><a href='confirm_delete?jScript=$jScript&&newScript=$newScript&&getDelete=$getDelete&&user_id=$user_id'><button>Delete</button></a></td>
    </tr>";

}

?>
    </table>
</body>
</html>