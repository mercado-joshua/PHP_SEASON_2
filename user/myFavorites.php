<?php
session_start();
include("../connect.php");

// authenticate session
if(isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
} else {
    echo "<script>window.location.href='../'</script>";
}

// check box
$check = $checkErr = "";
if(isset($_POST["btnSubmit"])) {
    if(empty($_POST["check"])) {
        $checkErr = "* Select at least one (1)";
    } else {
        $check = $_POST["check"];
    }

    if($check) {
        echo "<br><br>";
        foreach($check as $new_check) {
            echo $new_check . "<br>";
        }
    }
}
?>
<html>
    <head>
        <style>
        .error {
            color: red;
        }
        </style>
    </head>
    <body>
    <?php include("nav.php"); ?>
    <h2>Check Box</h2>
        <span class="error"><?php echo $checkErr; ?></span>
        <form method="POST">
            <input type="checkbox" name="check[]" value="Beer">Beer <br>
            <input type="checkbox" name="check[]" value="San Mig Light Apple">San Mig Light Apple <br>
            <input type="checkbox" name="check[]" value="Alfonso Lights">Alfonso Lights <br>
            <input type="checkbox" name="check[]" value="Great Taste White Choco">Great Taste White Choco <br>
            <input type="submit" name="btnSubmit" value="Submit">
        </form>

        <hr>
        <h2>Dynamic Dropdown</h2>

        <select name="category" id="category" onChange="category(this.value);">
            <option value="">Select category by clicking here</option>
            <option value="Car">Car</option>
            <option value="Food">Food</option>
            <option value="Beer">Beer</option>
            <option value="Beverages">Beverages</option>
        </select>

        <select name="choice" id="choice">
            <option value="">Select Category</option>
        </select>

        <script>
        let categories = {
            "Car": ["Honda", "BMW", "Mustang"],
            "Food": ["Burger", "Maling", "Hotdog"],
            "Beer": ["Red Horse", "Colt 45", "San Mig Light Apple"],
            "Beverages": ["Coke", "Sarsi", "Royal"]
        };

        function category(value) {
            if(value.length == 0) {
                document.getElementById("choice").innerHTML = "<option></option>";
            } else {
                let category_options = "";

                for(category_name in categories[value]) {
                    category_options += "<option name='choice' value='" + categories[value][category_name] + "'>" + categories[value][category_name] + "</option>";
                }

                document.getElementById("choice").innerHTML = category_options;
            }
        }
        </script>
    </body>
</html>