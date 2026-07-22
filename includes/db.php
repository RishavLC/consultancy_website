<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "civil_consultancy";

$conn = mysqli_connect(
    $host,
    $user,
    $password,
    $database
);

if(!$conn){

die("Database Connection Failed.");

}

?>