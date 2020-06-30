<?php
require_once "connect.php";
session_start();
$successInfo = "";
$errorInfo = "";
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $taskTitle = test_input($_POST["taskTitle"]);
    $taskText = test_input($_POST["taskText"]);
    $mysqltime = date("Y-m-d H:i:s"); //MySQL DATETIME format
    $ownerID = $_SESSION["userID"];
    $taskPrivate = 0;
    if (isset($_POST["private"])) {
        $taskPrivate = 1;
    }

    $sql = "INSERT INTO tasks (title, text, creationDate, lastEditDate, ownerID, private) VALUES (?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $taskTitle, $taskText, $mysqltime, $mysqltime, $ownerID, $taskPrivate);
    $stmt->execute();
    if($stmt->affected_rows === 0){
        $errorInfo .= "Something went wrong, task wasn't saved";
        die();
    } else {
        $successInfo .= "Task successfully saved";
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New task</title>
    <link rel="icon" href="icon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="jumbotron text-center">
        <h2>New task</h2>
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
                                    <input class="form-check-input" type="checkbox" name="private" id="private">Private task
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
                                <input class="form-control" type="text" name="taskTitle" id="taskTitle">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="taskText">Define task:</label>
                                <textarea class="form-control" name="taskText" id="taskText" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="buttonSubmit">Submit new task</button>
                            <a href="content.php" class="btn btn-outline-secondary">Go back</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
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
                        <div class="card-footer">
                            <p class="text-warning" id="warningText">Title may not be empty</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
<script>
    $(document).ready(function() {
        let titleInput = document.querySelector("#taskTitle");
        let textInput = document.querySelector("#taskText");
        let warningText = document.querySelector("#warningText");
        let buttonSubmit = document.querySelector("#buttonSubmit");
        buttonSubmit.disabled = true;
        let titleBool = false;
        let textBool = true;

        titleInput.addEventListener("keyup", (e) => {
            if (titleInput.value == "") {
                warningText.innerHTML = "Title may not be empty";
                buttonSubmit.disabled = true;
                titleBool = false;
            } else if (titleInput.value.length >= 120) {
                warningText.innerHTML = "Title must be less than 120 characters long";
                buttonSubmit.disabled = true;
                titleBool = false;
            } else {
                warningText.innerHTML = "";
                titleBool = true;
                checkSubmitCondition();
            }
        });

        textInput.addEventListener("keyup", (e) => {
            console.log(textInput.value.length);
            if (textInput.value.length >= 500) {
                warningText.innerHTML = "Text must be less than 500 characters long";
                buttonSubmit.disabled = true;
                textBool = false;
            } else {
                warningText.innerHTML = "";
                textBool = true;
                checkSubmitCondition();
            }
        });

        function checkSubmitCondition(){
            console.log(titleBool);
            console.log(textBool);
            if(titleBool && textBool){
                buttonSubmit.disabled = false;
            }
        }
    });
</script>
</html>