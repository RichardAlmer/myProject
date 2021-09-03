<?php
    session_start();
    if(isset($_SESSION['user'])){
        header("Location: home.php");
        exit;
    }

    if(isset($_SESSION['admin'])){
        header("Location: dashboard.php");
        exit;
    }

    require_once '../components/db_connect.php';

    $error = false;
    $errorEmail = $errorMSG = $errorName = $errorPassword = $errorNickname = $errorFirstname = $errorLastname = $errorBirthday = "";
    $currentDate = date('Y-m-d');

    if(isset($_POST['btnRegister'])){
        $nickname = htmlspecialchars(strip_tags(trim($_POST['nickName'])));
        $firstname = htmlspecialchars(strip_tags(trim($_POST['firstName'])));
        $lastname = htmlspecialchars(strip_tags(trim($_POST['lastName'])));
        $birthday = htmlspecialchars(strip_tags(trim($_POST['birthday'])));
        $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
        $password1 = htmlspecialchars(strip_tags(trim($_POST['password1'])));
        $password2 = htmlspecialchars(strip_tags(trim($_POST['password2'])));

        if(empty($nickname)){
            $error = true;
            $errorNickname = 'Bitte gib einen Nicknamen ein.';
        }else if(strlen($nickname) < 3){
            $error = true;
            $errorNickname = 'Der Nickname muss mindestens 3 Zeichen lang sein.';
        }else if(!preg_match("/^[a-zA-Z0-9]+$/", $nickname)){
            $error = true;
            $errorNickname = 'Der Nickname darf nur Buchstaben und Zahlen enthalten.';
        }else{
            $query = "SELECT nick_name FROM user WHERE nick_name = '$nickname'";
            $result = mysqli_query($conn, $query);
            $count = mysqli_num_rows($result);
            if($count != 0){
                $error = true;
                $errorNickname = 'Der Nickname ist bereits vergeben.';
            }
        }

        if(empty($firstname)){
            $error = true;
            $errorFirstname = 'Bitte gib einen Vornamen ein.';
        }else if(strlen($firstname) < 3){
            $error = true;
            $errorFirstname = 'Der Vorname muss mindestens 3 Zeichen lang sein.';
        }else if(!preg_match("/^[a-zA-Z]+$/", $firstname)){
            $error = true;
            $errorFirstname = 'Der Vorname darf nur Buchstaben enthalten.';
        }

        if(empty($lastname)){
            $error = true;
            $errorLastname = 'Bitte gib einen Nachnamen ein.';
        }else if(strlen($lastname) < 3){
            $error = true;
            $errorLastname = 'Der Nachname muss mindestens 3 Buchstaben lang sein.';
        }else if(!preg_match("/^[a-zA-Z]+$/", $lastname)){
            $error = true;
            $errorLastname = 'Der Nachname darf nur Buchstaben einthalten.';
        }

        if(empty($birthday)){
            $error = true;
            $errorBirthday = 'Bitte gib dein Geburtsdatum an.';
        }else if($birthday > $currentDate){
            $error = true;
            $errorBirthday = 'Dein Gebutrsdatum kann nicht in der Zukunft liegen.';
        }

        if(empty($email)){
            $error = true;
            $errorEmail = 'Bitte gib eine Email-Adresse ein.';
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = true;
            $errorEmail = 'Bitte gib eine gültige Email-Adresse ein.';
        }else{
            $query = "SELECT email FROM user WHERE email = '$email'";
            $result = mysqli_query($conn, $query);
            $count = mysqli_num_rows($result);
            if($count != 0){
                $error = true;
                $errorEmail = 'Diese Email-Adresse wird bereits verwendet.';
            }
        }

        if(empty($password1) || empty($password2)){
            $error = true;
            $errorPassword = 'Bitte gib ein Passwort ein.';
        }else if(strlen($password1) < 6 || strlen($password2) < 6){
            $error = true;
            $errorPassword = 'Das Passwort muss mindestens 6 Zeichen lang sein.';
        }else if($password1 != $password2){
            $error = true;
            $errorPassword = 'Die eingegebenen Passwörter stimmen nicht überein';
        }
        $hashedPassword = hash('sha256', $password1);

        if(!$error){
            $query = "INSERT INTO user (email, password, first_name, last_name, nick_name, birthday) VALUES ('$email', '$hashedPassword', '$firstname', '$lastname', '$nickname', '$birthday')";
            $result = mysqli_query($conn, $query);
            if($result){
                $errorTyp = 'success';
                $errorMSG = "Du hast dich erfolgreich registriert. Weiter zum <a href='../index.php'>Login</a>";
                header("refresh:3;url=../index.php");
            }else{
                $errorTyp = 'danger';
                $errorMSG = 'Etwas ist schief gegangen. Bitte versuche es erneut.';
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
    <title>Register</title>
</head>
<body>
    <div class="col-12 text-center my_text_maincolor">
        <?php if (isset($errorMSG)) { ?>
            <div class="alert alert-<?php echo $errorTyp ?>">
                <p><?php echo $errorMSG; ?></p>
            </div>
        <?php } ?>
    </div>
    <div class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="mb-3">
                <label for="InputNickName" class="form-label">Nickname: </label>
                <input type="text" name="nickName" class="form-control" id="InputNickName" placeholder="Nickname11" onkeyup="checkNickname(this.value)">
                <span id="errorNick" class="text-danger"> <?php echo $errorNickname; ?> </span>
            </div>
            <div class="mb-3">
                <label for="InputFirstName" class="form-label">Vorname: </label>
                <input type="text" name="firstName" class="form-control" id="InputFirstName" placeholder="Vorname">
                <span class="text-danger"> <?php echo $errorFirstname; ?> </span>
            </div>
            <div class="mb-3">
                <label for="InputLastName" class="form-label">Nachname: </label>
                <input type="text" name="lastName" class="form-control" id="InputLastName" placeholder="Nachname">
                <span class="text-danger"> <?php echo $errorLastname; ?> </span>
            </div>
            <div class="mb-3">
                <label for="InputBirthday" class="form-label">Geburtsdatum: </label>
                <input type="date" name="birthday" class="form-control" id="InputBirthday">
                <span class="text-danger"> <?php echo $errorBirthday; ?> </span>
            </div>
            <div class="mb-3">
                <label for="InputEmail" class="form-label">Email: </label>
                <input type="email" name="email" class="form-control" id="InputEmail" placeholder="name@email.com" onkeyup="checkEmail(this.value)">
                <span id="errorEmail" class="text-danger"> <?php echo $errorEmail; ?> </span>
            </div>
            <div class="mb-3">
                <label for="InputPassword1" class="form-label">Passwort: </label>
                <input type="password" name="password1" class="form-control" id="InputPassword1" onkeyup="checkPassword((this.value).length)">
            </div>
            <div class="mb-3">
                <label for="InputPassword2" class="form-label">Passwort wiederholen: </label>
                <input type="password" name="password2" class="form-control" id="InputPassword2" onkeyup="checkPassword((this.value).length)">
                <span id="errorPassword" class="text-danger"> <?php echo $errorPassword; ?> </span>
            </div>
            <button type="submit" name="btnRegister" class="btn btn-primary">Registrieren</button>
        </form>
    </div>
    <script>
        function checkNickname(str){
            var rq = new XMLHttpRequest();
            rq.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("errorNick").innerHTML = this.responseText;
                }
            }
            rq.open("GET", "../components/checkNickname.php?nickname="+str, true);
            rq.send();
        }

        function checkEmail(mail){
            var rq = new XMLHttpRequest();
            rq.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("errorEmail").innerHTML = this.responseText;
                }
            }
            rq.open("GET", "../components/checkEmail.php?email="+mail, true);
            rq.send();
        }

        function checkPassword(passcount){
            var rq = new XMLHttpRequest();
            rq.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("errorPassword").innerHTML = this.responseText;
                }
            }
            rq.open("GET", "../components/checkPassword.php?passcount="+passcount, true);
            rq.send();
        }
    </script>
</body>
</html>