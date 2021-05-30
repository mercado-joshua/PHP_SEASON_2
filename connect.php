<?php
$connect = mysqli_connect("localhost", "root", "", "my_database");

if(mysqli_connect_errno()) {
    echo "failed to connect to MySQL: " . mysqli_connect_error();
}