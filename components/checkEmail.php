<?php
    require_once 'db_connect.php';

    $mail = $_GET['email'];

    $errorEmail = '';

    $query = "SELECT email FROM user WHERE email = '$mail'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);
    if($count != 0){
        $errorEmail = 'Diese Email-Adresse wird bereits verwendet.';
    }

    echo $errorEmail;
    $conn->close();
?>