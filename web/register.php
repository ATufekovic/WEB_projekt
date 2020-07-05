<?php
session_start();
if (isset($_SESSION["username"])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" href="icon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="jumbotron text-center">
        <h2>Register</h2>
    </div>
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4>Register</h4>
                    </div>
                    <div class="card-body">
                        <form action="userRegister.php" method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input class="form-control" type="text" name="username" id="inputUsername">
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input class="form-control" type="password" name="password" id="inputPassword">
                            </div>
                            <button class="btn btn-primary" type="submit" id="buttonSubmit">Submit</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <p>Username has to be more than 8 and less than 20 characters long and may only contain letters, numbers and underscores. Password has to be atleast 8 characters and less than 50.</p>
                        <p class="text-warning" id="warningText"></p>
                    </div>
                </div>
                <a href="index.php" class="btn btn-outline-secondary mt-2">Go back</a>
            </div>
            <div class="col"></div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function() {
        let nicknameInput = document.querySelector("#inputUsername");
        let passwordInput = document.querySelector("#inputPassword");
        let warningText = document.querySelector("#warningText");
        let buttonSubmit = document.querySelector("#buttonSubmit");

        let nicknameFlag = false;
        let passwordFlag = false;

        buttonSubmit.disabled = true;

        nicknameInput.addEventListener("keyup", (e) => {
            var illegalChars = /\W/;
            if (nicknameInput.value == "") {
                warningText.innerHTML = "Not a valid username";
                nicknameFlag = false;
                buttonSubmit.disabled = true;
            } else if ((nicknameInput.value.length < 8) || (nicknameInput.value.length >= 20)) {
                warningText.innerHTML = "Breaking character limit";
                nicknameFlag = false;
                buttonSubmit.disabled = true;
            } else if (illegalChars.test(nicknameInput.value)) {
                warningText.innerHTML = "Contains forbidden letters";
                nicknameFlag = false;
                buttonSubmit.disabled = true;
            } else {
                warningText.innerHTML = "";
                nicknameFlag = true;
                checkInputs(nicknameFlag, passwordFlag);
            }
        });

        passwordInput.addEventListener("keyup", (e) => {
            if(passwordInput.value == ""){
                warningText.innerHTML = "Not a valid password";
                passwordFlag = false;
                buttonSubmit.disabled = true;
            } else if((passwordInput.value.length < 8) || (passwordInput.value.length > 50)){
                warningText.innerHTML = "Breaking character limit";
                passwordFlag = false;
                buttonSubmit.disabled = true;
            } else {
                warningText.innerHTML = "";
                passwordFlag = true;
                checkInputs(nicknameFlag, passwordFlag);
            }
        });

        function checkInputs (nickBool, passBool){
            if(nickBool && passBool){
                buttonSubmit.disabled = false;
            } else {
                buttonSubmit.disabled = true;
            }
        }
    });
</script>

</html>