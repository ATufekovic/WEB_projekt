<?php
require_once "connect.php";
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
}
$numOfTasks = 0;
$numOfPrivateTasks = 0;
$noTasks = false;
$sql = "SELECT * FROM tasks WHERE ownerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["userID"]);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    //ako nema taskova stvori tekst koji hinta na kreiranje novog taska
    $noTasks = true;
} else {
    while ($row = $result->fetch_assoc()) {
        $numOfTasks++;
        $taskIDs[] = $row["id"];
        $taskTitles[] = test_input($row["title"]);
        $taskTexts[] = test_input($row["text"]);
        $taskCreationDates[] = $row["creationDate"];
        $taskLastEditDates[] = $row["lastEditDate"];
        $taskPrivate[] = $row["private"];
        if ($row["private"]) {
            $numOfPrivateTasks++;
        }
    }
}
function echoTask($taskID, $taskTitle, $taskText, $taskCreationDate, $taskLastEditDate, $taskPrivacy)
{
    $taskText = nl2br($taskText);
    $temp = "<div class='card mt-2'>
        <div class='card-header'>
            <h5>{$taskTitle}</h5>
        </div>
        <div class='card-body'>
            <p>{$taskText}</p>
        </div>
        <div class='card-footer'>
            <div class='container-fluid'>
                <div class='row'>
                    <div class ='col-sm-6'>
                        <a class='btn btn-secondary mr-1' href='editTask.php?id={$taskID}'>Edit task</a>
                        <a class='btn btn-danger' href='deleteTask.php?id={$taskID}'>Delete task</a>
                    </div>
                    <div class ='col-sm-6 mt-1'>
                        <p>Created: {$taskCreationDate}<p>
                        <p>Last edited: {$taskLastEditDate}<p>
                    </div>
                </div>
            </div>
        </div>
    </div>";
    echo $temp;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="jumbotron text-center">
        <h2>Tasks</h2>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3">
                <div class="card">
                    <div class="card-header">
                        <h4>General task view</h4>
                    </div>
                    <div class="card-body">
                        <p>Click on a task to see more information about it.</p>
                        <p>Tasks can be public and private.</p>
                        <p>If you own a task you can edit it.</p>
                        <p>If a task is public others can see it but they can't edit it.</p>
                    </div>
                    <div class="card-footer">
                        <p><a href="newTask.php" class="btn btn-outline-dark">Click here to create a new task</a></p>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Task list</h5>
                    </div>
                    <div class="card-body">
                        <p>You can find all of your tasks below</p>
                        <?php
                        if($noTasks){
                            echo "<p>It appears you have no tasks, how about making one? Click the button to create a new task!</p>";
                        }
                        ?>
                    </div>
                </div>
                <?php
                for ($i = 0; $i < $numOfTasks; $i++) {
                    echoTask($taskIDs[$i], $taskTitles[$i], $taskTexts[$i], $taskCreationDates[$i], $taskLastEditDates[$i], $taskPrivate[$i]);
                }
                ?>
            </div>
            <div class="col-xl-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Logged in as <?php echo $_SESSION["username"]; ?></h4>
                    </div>
                    <div class="card-body">
                        <p>You currently have <?php echo $numOfTasks?> tasks, of which <?php echo $numOfPrivateTasks?> are private.</p>
                    </div>
                    <div class="card-footer">
                        <a href="logout.php" class="btn btn-secondary">Log out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>