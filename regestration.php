
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sign up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert {
            text-align: center !important;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <?php require_once 'nav.php'; ?>
    <div class="container">
        <form method="POST">
            <label class="form-label" for="">Name:</label>
            <input maxlength="30" class="form-control" type="text" name="name" required>
            <br>
            <label class="form-label" for="">Date of birth:</label>
            <input class="form-control" type="date" name="age" required>
            <br>
            <label class="form-label" for="">Email:</label>
            <input class="form-control" placeholder="please use a real email" name="email" type="email" required>
            <br>
            <label class="form-label" for="">Password:</label>
            <input class="form-control" name="password" type="password" placeholder="refresh the page to take a look at the password rules" required>
            <br>
            <button class="btn btn-outline-dark" type="submit" name="reg_sub" id="mo">register</button>
            <a class="btn btn-outline-success" href="https://192.168.1.12/revesion02/login.php">sign in instead</a>
        </form>
    </div>
    <?php
    
    $username = 'root';
    $password = '';
    $database = new PDO("mysql:host=localhost; dbname=revision;", $username, $password);

    


    if (isset($_POST['reg_sub'])) {
        $pattern = '/^[0-9a-zA-Z_@#]+$/';
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $name =  htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        
        $password2 = $_POST["password"];
        $age = $_POST["age"];
        $paslen = strlen($password2);

        $check4 = $database->prepare("SELECT * FROM users WHERE email=:email");
        $check4->bindParam('email', $email);

        $check4->execute();
        $isact2 = $check4->fetchObject();

        $up = false;
        $low = false;
        $num = false;

        for ($i = 0; $i < $paslen; $i++) {
            if (ctype_upper($password2[$i])) {
                $up = true;
            } elseif (ctype_lower($password2[$i])) {
                $low = true;
            } elseif (ctype_digit($password2[$i])) {
                $num = true;
            }
        }

        if (
            !$email or !$name or empty($_POST["name"]) or empty($_POST['age']) or !isset($_POST["name"]) or !isset($_POST["email"]) or !isset($_POST['age']) or !isset($_POST['password']) or empty($password2) or !preg_match($pattern, $password2) or
            !$num or !$up or !$low or $paslen < 8
        ) {
            echo '<div id="alert" class="alert alert-danger" role="alert">' .
                htmlspecialchars("Invalid data!!", ENT_QUOTES, 'UTF-8') . '</div>';
        } else {
            $check = $database->prepare("SELECT * FROM users WHERE email=:email");
            $check->bindParam('email', $email);
            $check->execute();
            $isact = $check->fetchObject();
            if ($check->rowCount() > 0 && $isact->activated == 1 ) {
                echo '<div id="alert" class="alert alert-danger" role="alert">' .
                    htmlspecialchars("This email is already used!", ENT_QUOTES, 'UTF-8') . '</div>';
            } else {

                if($isact2){
                    
                    if($isact2->activated == 0){

                        $delete = $database->prepare("DELETE FROM users WHERE email =:email");
                        $delete->bindParam('email', $email);
                        $delete->execute();

                    }
                }

                $password1 = hash("sha256",$password2);
                $code =  password_hash(date("h:i:s"), PASSWORD_DEFAULT);
                $insert = $database->prepare("INSERT INTO users(name,age,password,email,code,role) VALUES(:name,:age,:password,:email,:code,'user')");
                $insert->bindParam('code', $code);
                $insert->bindParam('name', $name);

                $insert->bindParam('age', $age);
                $insert->bindParam('password', $password1);
                $insert->bindParam('email', $email);

                if ($insert->execute()) {

                    echo '<div id="alert" class="alert alert-success" role="alert">' .
                        htmlspecialchars("You signed up successfully; check your email!", ENT_QUOTES, 'UTF-8') . '</div>';
                    require_once "mailer.php";
                    $mail->addAddress($email);
                    $mail->Subject = "Verification code of your email:";
                    $mail->Body = '<h1>Thank you for registering on our website!</h1>' . "<div>Click on the link below to activate your account!</div>" .
                        "<a href='https://192.168.1.12/revesion02/activation.php?codes=" . $code . "&mail=" . $email . "'>" .
                        "https://192.168.1.12/revesion02/activation.php" . "?codes=" . $code . "&mail=" . $email . "</a>";
                    $mail->setFrom("koko33467890@gmail.com", "codermomo");
                    $mail->send();

                } else {
                    echo '<div id="alert" class="alert alert-danger" role="alert">' .
                        htmlspecialchars("An error has occurred!", ENT_QUOTES, 'UTF-8') . '</div>';
                }
            }
        }

        echo '<script>setTimeout(function(){ window.location.href = "https://192.168.1.12/revesion02/regestration.php"; }, 3000);</script>';
    }
    ?>
    <script>
        alert("Password: allowed symbols: 0-9 and a-z and A-Z and @ and # and _. Your password should contain 8 or more characters and at least: one capital letter, one small letter, and one number.");
    </script>
</body>

</html>
