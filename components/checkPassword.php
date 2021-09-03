<?php
    $passcount = $_GET['passcount'];
    $errorPassword = '';

    if($passcount < 6){
        $errorPassword = 'Das Passwort muss mindestens 6 Zeichen lang sein.';
    }

    echo $errorPassword;
?>