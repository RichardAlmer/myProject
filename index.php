<?php
    session_start();
    if(isset($_SESSION['user'])){
        header("Location: php/home.php");
        exit;
    }

    if(isset($_SESSION['admin'])){
        header("Location: php/dashboard.php");
        exit;
    }

    require_once 'components/db_connect.php';

    $error = false;
    $errorEmail = $errorMSG = $errorPassword = "";

    if(isset($_POST["btnLogin"])){
        $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
        $password = htmlspecialchars(strip_tags(trim($_POST['password'])));

        if(empty($email)){
            $error = true;
            $errorEmail = 'Bitte gib deine Email oder deinen Nicknamen ein';
        }

        if(empty($password)){
            $error = true;
            $errorPassword = 'Bitte gib ein Passwort ein.';
        }
        $hashedPassword = hash('sha256', $password);

        if(!$error){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $query = "SELECT user_id, email, password, role FROM user WHERE email = '$email'";
                $result = mysqli_query($conn, $query);
                $count = mysqli_num_rows($result);
                $row = mysqli_fetch_assoc($result);
                if($count == 1 && $hashedPassword == $row['password']){
                    if($row['role'] == 'admin'){
                        $_SESSION['admin'] = $row['user_id'];
                        header("Location: php/dashboard.php");
                    }else if($row['role'] == 'user'){
                        $_SESSION['user'] = $row['user_id'];
                        header("Location: php/home.php");
                    }
                }else{
                    $errorMSG = 'Deine Email-Adresse oder dein Passwort ist falsch. Versuche es nochmal.';
                }
            }else{
                $query = "SELECT user_id, nick_name, password, role FROM user WHERE nick_name = '$email'";
                $result = mysqli_query($conn, $query);
                $count = mysqli_num_rows($result);
                $row = mysqli_fetch_assoc($result);
                if($count == 1 && $hashedPassword == $row['password']){
                    if($row['role'] == 'admin'){
                        $_SESSION['admin'] = $row['user_id'];
                        header("Location: php/dashboard.php");
                    }else if($row['role'] == 'user'){
                        $_SESSION['user'] = $row['user_id'];
                        header("Location: php/home.php");
                    }
                }else{
                    $errorMSG = 'Deine Email-Adresse oder dein Passwort ist falsch. Versuche es nochmal.';
                }
            }
        }
    }
    $conn->close();
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
                <label for="InputEmail" class="form-label">Email / Nickname: </label>
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