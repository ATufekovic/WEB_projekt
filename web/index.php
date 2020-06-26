<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
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
            <div class="col">Some info</div>
            <div class="col">More info</div>
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <form action="userLogin.php" method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input class="form-control" type="text" name="username" id="inputUsername">
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input class="form-control" type="password" name="password" id="inputPassword">
                            </div>
                            <div class="form-group form-check">
                                <label class="form-check-label" for="remember">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"> Remember me
                                </label>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <p>Not a member? <a href="register.php" target="_blank">Register here!</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>