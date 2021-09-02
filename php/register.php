<?php
    session_start();
    if(isset($_SESSION['user']) !=""){
        header("Location: php/home.php");
        exit;
    }

    if(isset($_SESSION['user']) !=""){
        header("Location: php/dashboard.php");
        exit;
    }

    require_once '../components/db_connect.php';

    $error = false;
    $errorEmail = $errorMSG = $errorName = $errorPassword1 = $errorPassword2 = $errorNickname = "";

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
    }
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
                <span class="text-danger"> <?php echo $errorName; ?> </span>
            </div>
            <div class="mb-3">
                <label for="InputLastName" class="form-label">Nachname: </label>
                <input type="text" name="lastName" class="form-control" id="InputLastName" placeholder="Nachname">
                <span class="text-danger"> <?php echo $errorName; ?> </span>
            </div>
            <div class="mb-3">
                <label for="InputBirthday" class="form-label">Geburtstag: </label>
                <input type="date" name="birthday" class="form-control" id="InputBirthday">
                <span class="text-danger"> <?php echo $errorName; ?> </span>
            </div>
            <div class="mb-3">
                <label for="InputEmail" class="form-label">Email: </label>
                <input type="email" name="email" class="form-control" id="InputEmail" placeholder="name@email.com">
                <span class="text-danger"> <?php echo $errorEmail; ?> </span>
            </div>
            <div class="mb-3">
                <label for="InputPassword1" class="form-label">Password: </label>
                <input type="password" name="password1" class="form-control" id="InputPassword1">
                <span class="text-danger"> <?php echo $errorPassword1; ?> </span>
            </div>
            <div class="mb-3">
                <label for="InputPassword2" class="form-label">Password wiederholen: </label>
                <input type="password" name="password2" class="form-control" id="InputPassword2">
                <span class="text-danger"> <?php echo $errorPassword2; ?> </span>
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
    </script>
</body>
</html>