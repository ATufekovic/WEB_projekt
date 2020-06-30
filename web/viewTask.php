<?php
require_once "connect.php";
session_start();
$successInfo = "";
$errorInfo = "";
$isUserOwner = false;
$isUserLoggedIn = false;
if (isset($_SESSION["username"])) {
    $isUserLoggedIn = true;
}
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $taskID = test_input($_GET["id"]);
    $sql = "SELECT * FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows === 0) {
        exit("No such task");
    } else {
        $row = $result->fetch_assoc();
        if($isUserLoggedIn){
            if ($row["ownerID"] == $_SESSION["userID"]) {
                $isUserOwner = true;
            }
        }
        if(!$isUserOwner){
            if($row["private"] == 1){
                header("Location: index.php");
            }
        }
    }
} else {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View task</title>
    <link rel="icon" href="icon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="jumbotron text-center">
        <h2>Tasks</h2>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-9">
                <?php
                if ($isUserOwner) {
                    echoTask($row["id"], $row["title"], $row["text"], $row["creationDate"], $row["lastEditDate"], $row["private"], true);
                } else {
                    echoTask($row["id"], $row["title"], $row["text"], $row["creationDate"], $row["lastEditDate"], $row["private"], false);
                }
                ?>
            </div>
            <div class="col-xl-3">
                <?php
                //ako je vec ulogiran samo prikazi tipku za njihove taskove
                if (isset($_SESSION["username"])) {
                    echo "
                <div class='card'>
                    <div class='card-header'>
                        <h4>Logged in as {$_SESSION["username"]}</h4>
                    </div>
                    <div class='card-body'>
                        <a href='content.php' class='btn btn-lg btn-primary m-1'>Go to your tasks</a>
                        <a href='logout.php' class='btn btn-secondary m-2'>Log out</a>
                        <a href='index.php' class='btn btn-info m-2'>Go back to index</a>
                    </div>
                </div>";
                } else {
                    $temp = "<div class='card'>
                <div class='card-header'>
                    <h4>Login</h4>
                </div>
                <div class='card-body'>
                <form action='userLogin.php' method='post'>
                    <div class='form-group'>
                        <label for='username'>Username:</label>
                        <input class='form-control' type='text' name='username' id='inputUsername'>
                    </div>
                    <div class='form-group'>
                        <label for='password'>Password:</label>
                        <input class='form-control' type='password' name='password' id='inputPassword'>
                    </div>
                    <button class='btn btn-primary' type='submit'>Submit</button>
                </form>
                </div>
                <div class='card-footer'>
                    <p>Not a member? <a href='register.php'>Register here!</a></p></div>";
                    echo $temp;
                }
                ?>
            </div>
        </div>
    </div>

</body>

</html>