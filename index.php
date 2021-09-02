<?php
    session_start();
    if(isset($_SESSION['user']) !=""){
        header("Location: php/calender.php");
    }

    require_once 'components/db_connect.php';

    $error = false;
    $errorEmail = $errorMSG = $errorPassword = "";

    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <title>myProject</title>
</head>
<body>
    <div class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="mb-3">
                <label for="InputEmail" class="form-label">Email: </label>
                <input type="email" name="email" class="form-control" id="InputEmail" placeholder="your@email.com">
                <span class="text-danger"> <?php echo $errorEmail; ?> </span>
            </div>
            <div class="mb-3">
                <label for="InputPassword" class="form-label">Passwort: </label>
                <input type="password" name="password" class="form-control" id="InputPassword">
                <span class="text-danger"> <?php echo $errorPassword; ?> </span>
                <span class="text-danger"> <?php echo $errorMSG; ?> </span>
            </div>
            <a href="php/register.php">Registrieren</a><br>
            <button type="submit" name="btnLogin" class="btn btn-primary">Sign in</button>
        </form>
    </div>
</body>
</html>