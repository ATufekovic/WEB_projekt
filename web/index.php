<?php
require_once "connect.php";
session_start();
$noTasks = false;
$numOfPublicTasksToShow = 10;
$numOfFetchedTasks = 0;
$sql = "SELECT * FROM tasks WHERE private='0' LIMIT ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $numOfPublicTasksToShow);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0){
    $noTasks = true;
} else {
    while($row = $result->fetch_assoc()){
        $numOfFetchedTasks++;
        $taskIDs[] = $row["id"];
        $taskTitles[] = test_input($row["title"]);
        $taskTexts[] = test_input($row["text"]);
        $taskCreationDates[] = $row["creationDate"];
        $taskLastEditDates[] = $row["lastEditDate"];
        $taskPrivate[] = $row["private"];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task managment</title>
    <link rel="icon" href="icon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="jumbotron text-center">
        <h2>Title</h2>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-header">Take a look at some public tasks floating around</div>
                    <div class="card-body">
                        <?php
                        if(!$noTasks){
                            for ($i=0; $i < $numOfFetchedTasks; $i++) {
                                echoTask($taskIDs[$i], $taskTitles[$i], $taskTexts[$i], $taskCreationDates[$i], $taskLastEditDates[$i], $taskPrivate[$i], false);
                            }
                        } else {
                            echo "No public tasks seem to be around :(";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                        <?php
                        //ako je vec ulogiran samo prikazi tipku za njihove taskove
                        if(isset($_SESSION["username"])){
                            echo "
                                <div class='card'>
                                    <div class='card-header'>
                                        <h4>Logged in as {$_SESSION["username"]}</h4>
                                    </div>
                                    <div class='card-body'>
                                        <a href='content.php' class='btn btn-lg btn-primary m-1'>Go to your tasks</a>
                                        <a href='logout.php' class='btn btn-secondary m-2'>Log out</a>
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