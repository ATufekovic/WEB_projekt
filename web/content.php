<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
}
aaaaaaaaaaaaaaaaa
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
        <h2>Title</h2>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
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
                        <p><a href="newTask.php" class="btn btn-outline-dark">Click here</a> to create a new task</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6"></div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Logged in as <?php echo $_SESSION["username"]; ?></h4>
                    </div>
                    <div class="card-body">
                        <p>Some irrelevant info, TODO: change this</p>
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