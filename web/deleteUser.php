<?php
require_once "connect.php";
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["action"])){
        $userID = $_SESSION["userID"];
        //prvo obrisi sve taskove od korisnika
        $sql = "DELETE FROM tasks WHERE ownerID = ?";
        $stmtTasks = $conn->prepare($sql);
        $stmtTasks->bind_param("i", $userID);
        $stmtTasks->execute();
        $stmtTasks->close();

        //i tek onda obrisi samog korisnika
        $sql = "DELETE FROM users WHERE id = ?";
        $stmtUser = $conn->prepare($sql);
        $stmtUser->bind_param("i", $userID);
        $stmtUser->execute();
        if($stmtUser->affected_rows === 0){
            $stmtUser->close();
            exit("No user affected");
        }
        $stmtUser->close();

        //na kraju uništi sesiju i baci korisnika natrag na glavnu stranicu
        session_destroy();
        header("Location: index.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete user</title>
    <link rel="icon" href="icon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="jumbotron text-center">
        <h2>Delete user account</h2>
    </div>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Are you sure you want to delete your account?</h4>
                        </div>
                        <div class="card-body">
                            <button type="submit" name="action" value="fetus_deletus" class="btn btn-danger">Yes i'm sure</button>
                        </div>
                        <div class="card-footer">
                            <a href="content.php" class="btn btn-secondary">No, take me back</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4"></div>
            </div>
        </form>
    </div>
</body>
</html>