<?php
$successInfo = "";
session_start();
if(!isset($_SESSION["username"])){
    header("Location: index.php");
}
else {
    session_destroy();
    $successInfo .= "Successfully logged out, returning to index in 3 seconds";
    header("refresh:3; url=index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging out</title>
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
            <div class="col"></div>
            <div class="col">
                <div class="card">
                    <p class="text-success"><?php echo $successInfo; ?></p>
                </div>
            </div>
            <div class="col"></div>
        </div>
    </div>
</body>
</html>