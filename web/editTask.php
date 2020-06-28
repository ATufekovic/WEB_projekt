<?php
require_once "connect.php";
session_start();
$successInfo = "";
$errorInfo = "";
if (!isset($_SESSION["username"])) {
    header("Location: content.php");
}
//ako je korisnik dosao preko edit task button-a odradi GET na predani ID i obradi podatke
if($_SERVER["REQUEST_METHOD"] === "GET"){
    //ako je korisnik samo upisao adresu php stranice izbaci ga natrag
    if(isset($_GET["id"])){
        $taskID = test_input($_GET["id"]);
    } else {
        header("Location: index.php");
    }
    $sql = "SELECT * FROM tasks WHERE id = {$taskID}";
    $result = $conn->query($sql);
    if(!$result){
        $errorInfo .= "Something went wrong";
        die();
    } else {
        $successInfo .= "Task successfully fetched";
    }
    $row = $result->fetch_assoc();
    //da nebi neregistrirana osoba probala napraviti smetnje
    if($row["ownerID"] != $_SESSION["userID"]){
        header("Location: index.php");//yeet
    }
    $private = $row["private"];
    $taskTitle = $row["title"];
    $taskText = $row["text"];
}

//ako je korisnik na ovoj stranici stisnuo button za POST onda obradi podatke na sljedeci nacin
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $taskID = $_POST["taskID"];
    $taskTitle = $_POST["taskTitle"];
    $taskText = $_POST["taskText"];
    $private = 0;
    if (isset($_POST["private"])) {
        $private = 1;
    }
    $mysqltime = date("Y-m-d H:i:s"); //MySQL DATETIME format
    $sql = "UPDATE tasks SET title='{$taskTitle}', text='{$taskText}', lastEditDate='{$mysqltime}', private='{$private}' WHERE id='{$taskID}';";
    $result = $conn->query($sql);
    if(!$result){
        $errorInfo .= "Something went wrong";
        die();
    } else {
        $successInfo .= "Task successfully saved";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit task</title>
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
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-header">
                            <p>Options</p>
                        </div>
                        <div class="card-body">
                            <div class="form-check">
                                <label class="form-check-label" for="private">
                                    <input class="form-check-input" type="checkbox" name="private" id="private" <?php
                                    if($private == 1){
                                        echo "checked";
                                    }
                                    ?>>Private task
                                </label>
                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="text-muted">Private tasks won't be visible to anyone but you.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="form-group">
                                <label for="taskTitle">Task title: </label>
                                <input class="form-control" type="text" name="taskTitle" id="taskTitle" value="<?php echo $taskTitle; ?>">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="taskText">Define task:</label>
                                <textarea class="form-control" name="taskText" id="taskText" rows="10" ><?php echo $taskText; ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" name="taskID" value="<?php echo $taskID; ?>" >Save</button>
                            <a href="content.php" class="btn btn-outline-secondary">Go back</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <p class="text-success">
                        <?php
                        echo $successInfo;
                        ?>
                    </p>
                    <p class="text-danger">
                        <?php
                        echo $errorInfo;
                        ?>
                    </p>
                </div>
            </div>
        </form>
    </div>
</body>

</html>