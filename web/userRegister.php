<?php
session_start();
if(isset($_SESSION["username"])){
    header("Location: index.php");
}
require_once "connect.php";
$errorInfo = "";
$registerInfo = "";
if(!$_SERVER["REQUEST_METHOD"] === "POST"){
    header("Location: index.php");
}
else {
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);

    $sql = "SELECT username FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        //postoji takav unos, prekini rad
        $errorInfo .= "Username taken, going back in 3 seconds";
        header( "refresh:3; url=register.php" );
    }
    else {
        //ime nije zauzeto, nastavi s radom
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password) VALUES (?, ?);";
        $stmtReg = $conn->prepare($sql);
        $stmtReg->bind_param("ss", $username, $hashedPassword);
        $stmtReg->execute();
        if($stmtReg->affected_rows === 0){
            exit("Error in registering");
        }
        $stmtReg->close();
        $registerInfo .= "Operation successful, going back to index in 3 seconds";
        header( "refresh:3; url=index.php" );
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" href="icon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
<div class="jumbotron text-center">
        <h2>Register</h2>
    </div>
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4>Result</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-success">
                            <?php echo $registerInfo;?>
                        </p>
                        <p class="text-danger">
                            <?php echo $errorInfo;?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col"></div>
        </div>
    </div>
</body>

</html>