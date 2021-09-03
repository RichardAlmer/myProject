<?php
    session_start();
    if(isset($_SESSION['user'])){
        header("Location: home.php");
        exit;
    }

    if(!isset($_SESSION['admin']) && !isset($_SESSION['user'])){
        header("Location: ../index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>For Admin</h1>
    <a href="../components/logout.php?logout">Logout</a>
</body>
</html>