<?php
require_once "connect.php";
session_start();
$successInfo = "";
$taskInfo = null;
//provjeri dali je korisnik ulogiran
if (!isset($_SESSION["username"])) {
    header("Location: content.php");
}

//korisnik je potvrdio brisanje, sada se POST koristi za mijenyaanje unosa baze, u oba slucaja ako nije GET ili POST korisnika baca na index.php
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $taskID = test_input($_POST["taskID"]);
    $sql = "SELECT * FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        exit("No such task found");
    } else {
        $taskInfo = $result->fetch_assoc();
        $stmt->close();
        //ako korisnik nije vlasnik baci ga natrag na index
        if ($_SESSION["userID"] != $taskInfo["ownerID"]) {
            header("Location: index.php");
        } else {
            //u ovom bloku vlasnik je potvrdio brisanje svog taska, nastavi s time
            $sql = "DELETE FROM tasks WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $taskID);
            $stmt->execute();
            if($stmt->affected_rows === 0){
                $stmt->close();
                exit("Something went wrong");
            } else {
                $stmt->close();
                $successInfo .= "Task deleted, going back to content";
                header("refresh:1; url=content.php");
            }
        }
    }
} else if($_SERVER["REQUEST_METHOD"] === "GET"){
    $taskID = test_input($_GET["id"]);
    $sql = "SELECT * FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        exit("No such task found");
    } else {
        $taskInfo = $result->fetch_assoc();
        $stmt->close();
        //ako je korisnik nije vlasnik baci ga natrag na index
        if ($_SESSION["userID"] != $taskInfo["ownerID"]) {
            header("Location: index.php");
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
    <title>New task</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="jumbotron text-center">
        <h2>Are you sure you want to delete this task?</h2>
    </div>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="row">
                <div class="col-xl-3"></div>
                <div class="col-xl-6">
                    <?php
                    //ako je GET samo pokaži task i pitaj za potvrdu, ništa se ne mijenja
                    if ($_SERVER["REQUEST_METHOD"] === "GET") {
                        $isPrivate = "No";
                        if($taskInfo["private"]){
                            $isPrivate = "Yes";
                        }
                        $temp = "<div class='card mt-2'>
                            <div class='card-header'>
                                <h5>{$taskInfo["title"]}</h5>
                            </div>
                            <div class='card-body'>
                                <p>{$taskInfo["text"]}</p>
                            </div>
                            <div class='card-footer'>
                                <div class='container-fluid'>
                                    <div class='row'>
                                        <div>
                                            <p>Created: {$taskInfo["creationDate"]}<p>
                                            <p>Last edited: {$taskInfo["lastEditDate"]}<p>
                                            <p>Private?  {$isPrivate}<p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type='submit' class='btn btn-danger' name='taskID' value='{$taskInfo["id"]}'>Yes i am sure</button>
                        <a href='content.php' class='btn btn-secondary'>No, go back</a>";
                        echo $temp;
                    } else {
                        echo "<div class='card'><p class='text-success'>{$successInfo}</p></div>";
                    }
                    ?>
                </div>
                <div class="col-xl-3">
                </div>
            </div>
        </form>
    </div>
</body>

</html>