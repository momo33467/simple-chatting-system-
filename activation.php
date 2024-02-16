<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>activate your email!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert{
            text-align: center !important;
        }
    </style>
</head>
<body>
<?php  require_once 'nav.php';?>
    <main class="container">
    <?php
    if(isset($_GET['codes'])&& isset($_GET['mail'])){
        $username='root';
        $password='';
        $database= new PDO("mysql:host=localhost; dbname=revision;",$username,$password);
        
        $checkit=$database->prepare("SELECT code FROM users WHERE code = :code5 AND email = :email8"); // يفضل انك ترسل الأيميل وتتحقق منه هنا برضو عشان لو اثنين سجلوا في نفس الثانية
        $checkit->bindParam('code5',$_GET['codes']);
        $checkit->bindParam('email8',$_GET['mail']);
        $checkit->execute();
        if($checkit->rowCount()>0){
            $activeate=$database->prepare("UPDATE users SET code =:code2,activated=true WHERE  code = :code7 AND email = :email");
            $code3=password_hash(date("h:i:s"), PASSWORD_DEFAULT);
            $activeate->bindParam('code7',$_GET['codes']);
            $activeate->bindParam('code2',$code3);
            $activeate->bindParam('email',$_GET['mail']);
            if($activeate->execute()){
                echo'<div id="alert" class="alert alert-success" role="alert">
            Your account is active now!
            </div>';
            echo'<a class="btn btn-outline-warning" href="login.php">log in</a>';
            }else{
                echo'<div id="alert" class="alert alert-danger" role="alert">
                An error has ocuerd!
                </div>
                ';
            }
        }
    }else{
        echo'<div id="alert" class="alert alert-danger" role="alert">
        You have to chck your email first!!, or you should log in if you checked it already. 
        </div>
        ';
        echo '<div>';
        echo'<a class="btn btn-outline-danger " href="https://192.168.1.12/revesion02/regestration.php" style = "width:100%;">sign up</a>';
        echo '<br>';
        echo'<a class="btn btn-outline-warning" href="https://192.168.1.12/revesion02/login.php" style = "width:100%;">log in</a>';
        echo '</div>';
    }
    ?>
    </main>
</body>
</html>