<?php

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_projekt";

$conn = new mysqli($servername, $username, $password, $dbname);
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

function echoTask($taskID, $taskTitle, $taskText, $taskCreationDate, $taskLastEditDate, $taskPrivacy, $editable)
{
    $taskText = nl2br($taskText);
    $cardPrivacyIndicator = "";
    if($taskPrivacy){
        $cardPrivacyIndicator = "bg-secondary";
    }
    if($editable){
        $temp = "
        <div class='card mb-2'>
            <div class='card-header {$cardPrivacyIndicator}'>
                <a class='text-body' href='viewTask.php?id={$taskID}'><h5>{$taskTitle}</h5></a>
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
    } else {
        $temp = "
        <div class='card mb-2'>
            <div class='card-header {$cardPrivacyIndicator}'>
                <a class='text-body' href='viewTask.php?id={$taskID}'><h5>{$taskTitle}</h5></a>
            </div>
            <div class='card-body'>
                <p>{$taskText}</p>
            </div>
            <div class='card-footer'>
                <div class='container-fluid'>
                    <div class='row'>
                        <div class ='col-sm-6 mt-1'>
                            <p>Created: {$taskCreationDate}<p>
                            <p>Last edited: {$taskLastEditDate}<p>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
    }
    
    echo $temp;
}