<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset your password!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert{
            text-align: center !important;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<?php  require_once 'nav.php';?>

<?php
if(!isset($_GET['secode'])){
    echo'
    <main class="container m-auto" style="max-width: 760px !important;">
    <form method="POST">
    <label class="form-label" for="">email:</label>
    <input class="form-control" name="email4" type="email" required>
    <button class="btn btn-outline-dark mt-3 w-100" name="send" type="submit">send code</button>
 </form> 
    </main>';
}elseif(isset($_GET['secode'])&& isset($_GET['mail5'])){
 echo'<main class="container m-auto" style="max-width: 760px !important;">
 <form method="post">
  <label class="form-label" for="">Enter newe password:</label>
  <input  class="form-control" name="new" required type="password" placeholder="refresh the page to take a look at the password rules" >
  <button class="btn btn-outline-success mt-3 w-100" name="reset"> reset password</button>
 </form>
 </main>';

 echo'<script>
 alert("Password: allowed symbols: 0-9 and a-z and A-Z and @ and # and _. Your password should contain 8 or more characters and at least: one capital letter, one small letter, and one number.");
</script>';
}
 if(isset($_POST['send'])){
    $username='root';
    $password='';
    $database= new PDO("mysql:host=localhost; dbname=revision;",$username,$password);

    $check2=$database->prepare("SELECT * FROM users WHERE email = :mail");
    $check2->bindParam('mail',$_POST['email4']);
    $check2->execute();

    if($check2->rowCount() > 0){
        $code2=$check2->fetchObject();
        require_once "mailer.php";
        $mail->addAddress($_POST['email4']);
        $mail->Subject="Reset password!:";
       echo $mail->Body="click on the linck bello to reset your password:
        <br>
        "."<a href='https://192.168.1.12/revesion02/repass.php?secode=".$code2->code."&mail5=".$_POST['email4']."'>reset linck</a>";
        $mail->setFrom("koko33467890@gmail.com","codermomo");
        $mail->send();
        '<div id="alert" class="alert alert-success" role="alert">
        a linck has been sent to your email. check it pleas!
    </div>';             

    }else{
       echo'<div id="alert" class="alert alert-danger" role="alert">
           No such email found in our database!
       </div>
       ';  
    }
 }

 if(isset($_POST['reset'])){
    $username='root';
    $password='';
    $database= new PDO("mysql:host=localhost; dbname=revision;",$username,$password);
    
    $pattern = '/^[0-9a-zA-Z_@#]+$/';
    $paslen = strlen($_POST['new']);

    $up = false;
    $low = false;
    $num = false;

    for ($i = 0; $i < $paslen; $i++) {
        if (ctype_upper($_POST['new'][$i])) {
            $up = true;
        } elseif (ctype_lower($_POST['new'][$i])) {
            $low = true;
        } elseif (ctype_digit($_POST['new'][$i])) {
            $num = true;
        }
    }

    if($up && $low && $num && !empty($_POST['new']) && preg_match($pattern, $_POST['new'])){
        $pass4=md5($_POST['new']);
        $reserpass=$database->prepare("UPDATE users SET password =:pass4, code =:newCode WHERE email =:email6 and code =:secode");
        $reserpass->bindParam("secode",$_GET["secode"]);
        $newsec = password_hash(date("h:i:s"), PASSWORD_DEFAULT);
        $reserpass->bindParam("newCode", $newsec);

        $reserpass->bindParam('pass4',$pass4);
        $reserpass->bindParam('email6',$_GET['mail5']);
        
        if($reserpass->execute()){
            echo'<div id="alert" class="alert alert-success" role="alert">
            your password has been updated successfully.
            </div>' ;
        
        }else{
            echo'<div id="alert" class="alert alert-danger" role="alert">
            An error has ocuerd.
        </div>
        ';
        }
    }else{
        echo'<div id="alert" class="alert alert-danger" role="alert">
            The password is invalid!.
        </div>
        ';
    }
 }

?>

</body>
</html>