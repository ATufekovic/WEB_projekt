<?php
require_once "connect.php";
session_start();
$successInfo = "";
if (!isset($_SESSION["username"])) {
    header("Location: content.php");
}
if (!$_SERVER["REQUEST_METHOD"] === "GET") {
    header("Location: index.php");
} else {
    $taskID = test_input($_GET["id"]);

    $sql = "SELECT ownerID FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        exit("No such task found");
    }
    $ownerID = $result->fetch_assoc()["ownerID"];
    $stmt->close();
    if($_SESSION["userID"] == $ownerID){
        $sql = "DELETE FROM tasks WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $taskID);
        $stmt->execute();
        if($stmt->affected_rows === 0){
            exit("No such task found");
        }
        $stmt->close();
    }
aaaaaaaaaaaaaaaaaaaaaaaa
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New task</title>
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
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <p class="text-success"><?php echo $successInfo; ?></p>
                </div>
                <div class="col"></div>
            </div>
        </form>
    </div>
</body>

</html>