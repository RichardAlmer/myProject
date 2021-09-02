<?php
    require_once 'db_connect.php';

    $nick = $_GET['nickname'];

    $errorNickname = '';

    $query = "SELECT nick_name FROM user WHERE nick_name = '$nick'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);
    if($count != 0){
        $errorNickname = 'Der Nickname ist bereits vergeben.';
    }

    echo $errorNickname;
    $conn->close();
?>