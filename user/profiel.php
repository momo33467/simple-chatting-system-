<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit your information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #alert{
            text-align: center !important;
        }
    </style>
</head>
<body>
<?php  require_once '../nav.php';?>
<?php

if(isset($_SESSION['info'])){  // تم حذف تعديل الأيميل مؤقتا كيلا يحدث المستخد ايميله ويضع وهمي
    function sanitize($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    echo' 
 <main class="container m-auto" style="max-width:760px !important ;">
    <form method="POST" >
        <label class="form-label" for="">Name:</label>
        <input class="form-control" type="text" value="'.sanitize($_SESSION['info']->name).'" name="ename">
        
        <label  class="form-label" for="">password:</label>

        <input class="form-control" type="password" name="epass" placeholder="refresh the page to take a look at the password rules">
        <label  class="form-label" for="">Date of birth:</label>

        <input class="form-control" type="date"  value="'.sanitize($_SESSION['info']->age).'" name="edate">
        <button class=" mt-2 btn btn-success" name="update" type="submit"  value="'.sanitize($_SESSION['info']->ID).'">Update</button>
        <button class="mt-2 btn btn-dark" name="track">home page</button>
    </form>
</main>';
}else{
    header("location:https://192.168.1.12/revesion02/login.php",true);
}

if(isset($_POST['update'])){

    $pattern = '/^[0-9a-zA-Z_@#]+$/';
    $paslen = strlen($_POST['epass']);

    $up = false;
    $low = false;
    $num = false;

    for ($i = 0; $i < $paslen; $i++) {
        if (ctype_upper($_POST['epass'][$i])) {
            $up = true;
        } elseif (ctype_lower($_POST['epass'][$i])) {
            $low = true;
        } elseif (ctype_digit($_POST['epass'][$i])) {
            $num = true;
        }
    }

    if( ($up && $low && $num &&  preg_match($pattern, $_POST['epass'])) or empty($_POST['epass'])){
        if($_POST['update'] == $_SESSION["info"]->ID){
            $username='root';
            $password='';
            $database= new PDO("mysql:host=localhost; dbname=revision;",$username,$password);
            
            $epass02=hash("sha256",$_POST['epass']);

            if(!empty($_POST['epass'])){
                $upadate = $database->prepare("UPDATE users SET name = :name2, password=:pass2, age=:age2 WHERE ID=:id");
                $xssname=htmlspecialchars($_POST['ename']);
                $upadate->bindParam("name2", $xssname);
                // $upadate->bindParam("email2",$_POST['eemail']);  محذوف من فوق
                
                $upadate->bindParam("pass2",$epass02);
                $upadate->bindParam("age2",$_POST['edate']);
                $upadate->bindParam("id",$_POST['update']);
            }else{
                $upadate = $database->prepare("UPDATE users SET name = :name2, age=:age2 WHERE ID=:id");
                $xssname=htmlspecialchars($_POST['ename']);
                $upadate->bindParam("name2", $xssname);
                // $upadate->bindParam("email2",$_POST['eemail']);  محذوف من فوق
                
                $upadate->bindParam("age2",$_POST['edate']);
                $upadate->bindParam("id",$_POST['update']);
            }
           
            if($upadate->execute()){
                $upadates=$database->prepare("SELECT * FROM users WHERE ID=:id2");
                $upadates->bindParam("id2",$_POST['update']);
                $upadates->execute();
            
                $_SESSION['info']=$upadates->fetchObject();
                echo'<div id="alert" class="alert alert-success" role="alert">
                Your informations have been updated successfully
                </div>';  
                echo '<script>setTimeout(function(){ window.location.href = "https://192.168.1.12/revesion02/user/profiel.php"; }, 3000);</script>';
        
            }else{
                echo'<div id="alert" class="alert alert-danger" role="alert">
                An error has ocuerd!
                </div>
                ';
            }
        }else{
            echo'<div id="alert" class="alert alert-danger" role="alert">
            An error has ocuerd!!!!!
            </div>
            ';
        }
    }else{
        echo'<div id="alert" class="alert alert-danger" role="alert">
           Password is invalid.
       </div>
       ';  
    }
}
if(isset($_POST['track'])){
    if($_SESSION['info']->role==='user'){
        header("location:https://192.168.1.12/revesion02/user/userpage.php",true);
    }elseif($_SESSION['info']->role==='admin'){
        header("location:https://192.168.1.12/revesion02/adminpage/admin.php",true);
    }elseif($_SESSION['info']->role==='devo'){
        header("location:https://192.168.1.12/revesion02/devo/devo.php",true);
    }
}
?>
<script>
    alert("Password: allowed symbols: 0-9 and a-z and A-Z and @ and # and _. Your password should contain 8 or more characters and at least: one capital letter, one small letter, and one number.");
</script>
</body>
</html>