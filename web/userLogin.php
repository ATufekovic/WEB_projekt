<?php
require_once "connect.php";

if(!$_SERVER["REQUEST_METHOD"] === "POST"){
    header("Location: index.php");
}
else {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $remember = $_POST["remember"];
    echo $username . " " . $password . " " . $remember;

    $sql = "SELECT * FROM users WHERE username = '{$username}'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        header("Location: index.php");
    }
}