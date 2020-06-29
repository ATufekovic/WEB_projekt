<?php
session_start();
if(isset($_SESSION["username"])){
    header("Location: content.php");
}
require_once "connect.php";

$errorFlag = false;
$errorInfo = "";
$successInfo = "";

if (!$_SERVER["REQUEST_METHOD"] === "POST") {
    header("Location: index.php");
} else {
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        //ponovan unos podataka jer korisnik ne postoji
        $errorFlag = true;
        $errorInfo .= "User does not exist.";
    } else {
        $row = $result->fetch_assoc();
        $hashed = $row["password"];
        $userID = $row["id"];
        if (password_verify($password, $hashed)) {
            //login je u redu, nastavi sa radom
            $successInfo .= "Redirecting back to content... in 3 seconds";
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $password;
            $_SESSION["userID"] = $userID;
            header("refresh:3; url=content.php");
            //TODO: logika nakon uspjesnog logiranja

        } else {
            //login nije uspio, probaj ponovno
            $errorFlag = true;
            $errorInfo .= "Password does not match";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="jumbotron text-center">
        <h2>Login</h2>
    </div>
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="card">
                    <?php
                    if ($errorFlag) {
                        echo '<div class="card-header">
                                <h4>Try again</h4>
                            </div>';
                    } else {
                        echo '<div class="card-header">
                                <h4>Success</h4>
                            </div>';
                    }
                    ?>
                    <div class="card-body">
                        <?php
                        if ($errorFlag) {
                            $action = htmlspecialchars($_SERVER['PHP_SELF']);
                            $temp = '<form action="' . $action . '" method="post">
                                    <div class="form-group">
                                        <label for="username">Username:</label>
                                        <input class="form-control" type="text" name="username" id="inputUsername">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password:</label>
                                        <input class="form-control" type="password" name="password" id="inputPassword">
                                    </div>
                                    <button class="btn btn-primary" type="submit">Log in</button>
                                </form>';
                            echo $temp;
                        } else {
                            echo '<p>Login successful</p>';
                        }
                        ?>

                    </div>
                    <div class="card-footer">
                        <p class="text-success">
                            <?php echo $successInfo; ?>
                        </p>
                        <p class="text-danger">
                            <?php echo $errorInfo; ?>
                        </p>
                    </div>
                </div>
                <?php
                if ($errorFlag) {
                    echo '<a href="index.php" class="btn btn-outline-secondary mt-2">Go back</a>';
                }
                ?>
            </div>
            <div class="col"></div>
        </div>
    </div>
</body>

</html>