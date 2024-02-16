<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Info</title>
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
<?php
$username = 'root';
$password = '';
$database = new PDO("mysql:host=localhost; dbname=revision;", $username, $password);

if (isset($_SESSION['id'])) {
    // لازم تعدل اكثر على الأدمن والثغرة ذيك وسالفة تغيير الايميلات
    $edit = $database->prepare("SELECT * FROM users WHERE ID=:id");
    $edit->bindParam(':id', $_SESSION['id']);
    $edit->execute();
    $info2 = $edit->fetch(PDO::FETCH_OBJ);

    function sanitize($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    if($_SESSION["info"]->role == "admin"){
        if($info2->role == "user"){
            echo '
            <main class="container m-auto" style="max-width: 760px !important ;">
                <form method="POST">
                    <label class="form-label" for="">Name:</label>
                    <input class="form-control" type="text" value="' . sanitize($info2->name) . '" name="ename">
                    <label class="form-label" for="">Email:</label>
                    <input class="form-control" type="email" value="' . sanitize($info2->email) . '" name="eemail">
                    <label class="form-label" for="">Password:</label>
                    <input class="form-control" type="password" placeholder="refresh the page to take a look at the password rules" name="epass">
                    <label class="form-label" for="">Date of Birth:</label>
                    <input class="form-control" type="date" value="' . sanitize($info2->age) . '" name="edate">
                    <button class="mt-2 btn btn-success" name="update" type="submit" value="' . $info2->ID . '">Update</button>
                    <button class="mt-2 btn btn-dark" name="track">Home Page</button>
                </form>
            </main>';
        }else{
            header("location: https://192.168.1.12/revesion02/adminpage/admin.php", true);
        }
}else if($_SESSION["info"]->role == "devo"){
    echo '
        <main class="container m-auto" style="max-width: 760px !important ;">
            <form method="POST">
                <label class="form-label" for="">Name:</label>
                <input class="form-control" type="text" value="' . sanitize($info2->name) . '" name="ename">
                <label class="form-label" for="">Email:</label>
                <input class="form-control" type="email" value="' . sanitize($info2->email) . '" name="eemail">
                <label class="form-label" for="">Password:</label>
                <input class="form-control" type="password" placeholder="refresh the page to take a look at the password rules" name="epass">
                <label class="form-label" for="">Date of Birth:</label>
                <input class="form-control" type="date" value="' . sanitize($info2->age) . '" name="edate">
                <button class="mt-2 btn btn-success" name="update" type="submit" value="' . $info2->ID . '">Update</button>
                <button class="mt-2 btn btn-dark" name="track">Home Page</button>
            </form>
    </main>';
}else{
    echo"error";
}
} else {
    header("location: https://192.168.1.12/revesion02/adminpage/admin.php", true);
}

if (isset($_POST['update'])) {
    $verf = $database->prepare("SELECT * FROM users WHERE ID=:id");
    $verf->bindParam(':id', $_POST["update"]);
    $verf->execute();
    $isuser = $verf->fetch(PDO::FETCH_OBJ);
    
    if($isuser->role != "user" && $_SESSION["info"]->role != "devo"){
        header("location: https://192.168.1.12/revesion02/adminpage/admin.php", true);
    }else{
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

            $epass02 = hash("sha256",$_POST['epass']);
            if(!empty($_POST['epass'])){
                $upadate = $database->prepare("UPDATE users SET name = :name2, email = :email2, password=:pass2, age=:age2 WHERE ID=:id");
                $xssname=sanitize($_POST['ename']);
                $upadate->bindParam(":name2", $xssname);
                
                // Sanitize email using filter_var with FILTER_VALIDATE_EMAIL
                $email = filter_var($_POST['eemail'], FILTER_VALIDATE_EMAIL);
                $upadate->bindParam(":email2", $email);
                
                $upadate->bindParam(":pass2", $epass02);
                $upadate->bindParam(":age2", $_POST['edate']);
                $upadate->bindParam(":id", $_POST['update']);
            }else{
                $upadate = $database->prepare("UPDATE users SET name = :name2, email = :email2, age=:age2 WHERE ID=:id");
                $xssname=sanitize($_POST['ename']);
                $upadate->bindParam(":name2", $xssname);
                
                // Sanitize email using filter_var with FILTER_VALIDATE_EMAIL
                $email = filter_var($_POST['eemail'], FILTER_VALIDATE_EMAIL);
                $upadate->bindParam(":email2", $email);
                
                $upadate->bindParam(":age2", $_POST['edate']);
                $upadate->bindParam(":id", $_POST['update']);
            }
        
            if($email){
                if ($upadate->execute()) {
                
                    echo '<div id="alert" class="alert alert-success" role="alert">
                        Information has been updated successfully
                    </div>';
                    echo '<script>setTimeout(function(){ window.location.href = "https://192.168.1.12/revesion02/edit.php"; }, 3000);</script>';
                } else {
                    echo '<div id="alert" class="alert alert-danger" role="alert">
                    An error has occurred!
                    </div>';
                }
            }else{
                echo '<div id="alert" class="alert alert-danger" role="alert">
                make sure of email!
                </div>';
            }
        }else{
            echo'<div id="alert" class="alert alert-danger" role="alert">
            Password is invalid.
        </div>
        ';  
        }

    }
    
    
}
if (isset($_POST['track'])) {
    if ($_SESSION['info']->role === 'admin') {
        header("location: https://192.168.1.12/revesion02/adminpage/admin.php", true);
    } elseif ($_SESSION['info']->role === 'devo') {
        header("location: https://192.168.1.12/revesion02/devo/devo.php", true);
    }
}
?>
<script>
    alert("Password: allowed symbols: 0-9 and a-z and A-Z and @ and # and _. Your password should contain 8 or more characters and at least: one capital letter, one small letter, and one number.");
</script>
</body>
</html>