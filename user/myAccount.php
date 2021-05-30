<?php
session_start();
include("../connect.php");

// authenticate session
if(isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
} else {
    echo "<script>window.location.href='../'</script>";
}

// idisplay ang img
$query_info = mysqli_query($connect, "SELECT * FROM `tbl_user` WHERE `email` = '$email'");
$my_info = mysqli_fetch_assoc($query_info);
$account_type = $my_info["account_type"];
$img = $my_info["img"];

if($img == "") {
    echo "No Photo";
} else {
    echo "<img src='$img' height='150px' width='200px'>";
}

// code sa pag-upload ng image
$uploadErr = "";
$target_directory = "img/";
if(isset($_POST["btnUpload"])) {
    $target_file = $target_directory . "/" . basename($_FILES["profile_pic"]["name"]);
    $uploadOk = 1;

    if(file_exists($target_file)) {
        $target_file = $target_directory . rand(1,9).rand(1,9).rand(1,9).rand(1,9) . "_" . basename($_FILES["profile_pic"]["name"]);
        $uploadOk = 1;
    }

    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    if($_FILES["profile_pic"]["size"] > 5000000000000000000000000000000000) {
        $uploadErr = "* Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if($imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "GIF") {
        $uploadErr = "* Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if($uploadOk == 1) {
        if(move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            mysqli_query($connect, "UPDATE `tbl_user` SET `img` = '$target_file' WHERE `email` = '$email'");
            echo "<script>window.location.href='myAccount?notify=$notify'</script>";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

}

if(!empty($_GET["notify"])) {
    echo $_GET["notify"];
}
?>
<html>
    <head>
        <style>
        .img {
            height: 150px;
        }
        </style>
    </head>
    <body>
    <?php include("nav.php"); ?>
    <h2>Upload Photo</h2>
        <form method="POST" enctype="multipart/form-data">
            <table border="1">
                <tr>
                    <td><span class="img" id="preview"></span></td>
                </tr>
                <tr>
                    <td><input type="file" id="profile_pic" name="profile_pic" onChange="displayPreview(this.files);"></td>
                </tr>
                <tr>
                    <td><input type="submit" name="btnUpload" value="Upload"></td>
                </tr>
            </table>
        </form>
        <span class="error"><?php echo $uploadErr; ?></span>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
        let _URL = window.URL || window.webkitURL;

        function displayPreview(files){
            let file = files[0];
            let img = new Image();
            let sizeKB = file.size / 1024;
            img.onload = function() {
                $('#preview').append(img);
            }

            img.src = _URL.createObjectURL(file);
        }
        </script>
    </body>
</html>