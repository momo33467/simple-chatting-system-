<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        #sh{
            text-align: center !important;
            border: solid red 1px;
        }

        #alert{
            text-align: center !important;
        }
        
        #btn{
            width: 90% !important;
        }
        #fo{
            display: flex;
            justify-content: center;
        }
        #for{
            display: flex !important;
        }
        #b1{
            margin-right: 2px !important;
        }
        #more{
            width: 259px;
        }
        
        #logo{
            width: 50px;
        }
        #bar{
            width: 100% !important;
            justify-content: center;
            
        }
    
     </style>

</head>
<body>
        <!-- <?php  
    // require_once '../nav.php';
    
    ?>  -->

   

<nav class="navbar navbar-expand-lg bg-light mb-3">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
          <img id="logo" src="logo.png" alt="error">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;" id="bar">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="https://192.168.1.12/revesion02/regestration.php">sing up</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="https://192.168.1.12/revesion02/login.php">log in</a>
              </li>
          
              <li class="nav-item">
                <a class="nav-link active" href="https://192.168.1.12/revesion02/adminpage/admin.php">home Page</a>
              </li>

              <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="https://192.168.1.12/revesion02/privet.php">Privet messages</a>
            </li>

            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="https://192.168.1.12/revesion02/todo.php">Public chat</a>
            </li>
            </ul>
          </div>
        </div>
      </nav>

<main class="m-auto container" >
<?php
session_start();
$username='root';
$password='';
$database= new PDO("mysql:host=localhost; dbname=revision;",$username,$password);

if(isset($_SESSION['info'])&& $_SESSION['info']->role==='admin'){
    $info2=$_SESSION['info'];
    echo'</br>';
    echo'<div id="sh" class="shadow p-3 mb-1 bg-body rounded"> Welcome'.' '.$info2->name.'</div>';

    echo'<form id="fo" method="POST">
    <button class="btn btn-outline-secondary mt-3" id="btn" name="out" type="submit">log out</button>
    <a id="btn" class="btn btn-outline-warning mt-3" name="update" href="https://192.168.1.12/revesion02/user/profiel.php">update your information</a>
    </form>';

    echo'<form  method="post">
    <label class="form-label mt-4 ">SEARCH:</label>
        <input class="form-control " type="text" name="search" placeholder="email or name"/>
        <button class="btn btn-dark mt-1 w-100" type="submit" name="send01">search</button>
    </form>';
    
    //  $result=$database->prepare("SELECT * FROM users WHERE email = '" . $_POST['search'] . "'");

    if(isset($_POST['send01'])){
        $result=$database->prepare("SELECT * FROM users WHERE email LIKE :email0 OR name LIKE :email0 limit 250");
        $val="%".$_POST['search']."%";
        $result->bindParam('email0',$val);
        $result->execute();
        
        if($result->rowCount()>0){
            foreach($result AS $i){
                echo"<div class='shadow p-3 mb-2'>"
                ."<span>".
                'Name: '.$i['name'].
                '</span>'.
                '<br>'.
                '<span>'.
                'Email: '.$i['email'].
                '</span>'.
                '<br>'.
                '<span>'.
                'Role: '.$i['role'].
                '</span>'.
                '<div id="for">'.
                '<form method="post">
                <button  id="b1" class="btn btn-danger mt-1" name="del" value="'.$i['ID'].'">DELETE USER</button>
                <input type="hidden" value="'.$i['role'].'" name="role"/>
                
              </form>'.
                 '    <form method="post">
                 <button type="submit" value="'.$i['ID'].'" class="btn btn-info mt-1"  name="edit">Edit information</button>
                 <input type="hidden" value="'.$i['role'].'" name="role2"/>
                 </form>'.
                 '</div>'. '<form method="POST">
                 <button class="btn btn-success mt-1" value="'.$i['ID'].'" name="more" id="more">Activate</button>
                </form>'
                 .
                "</div>";
             }
        }else{
            echo'<div id="alert" class="alert alert-danger mt-2" role="alert">
                No data found
            </div>
            ';
        }

       
    }
    if(isset($_POST['del'])){
        if($_POST['role']==='user'){
            $del=$database->prepare("DELETE FROM users WHERE ID=:id");
            $del->bindParam("id",$_POST['del']);
            $del->execute();
            header("Refresh:2;");
        }else{
            echo'<div id="alert" class="alert alert-danger mt-2" role="alert">
            This user role is not "user"
        </div>
        ';
    }
    }

    if(isset($_POST['edit'])){
        if($_POST['role2']==='user'){
            $_SESSION['id']=$_POST['edit'];
            header("location:https://192.168.1.12/revesion02/edit.php",true);
        }else{
            echo"<div id='alert' class='alert alert-danger mt-2' role='alert'>
            This user's role is not 'user'
        </div>
        ";
    }
    }

    if(isset($_POST['more'])){
        $act2=$database->prepare("UPDATE users SET activated=true WHERE ID=:id8");
        $act2->bindParam('id8',$_POST['more']);
        $act2->execute();
    }

    if(isset($_POST['out'])){
        session_destroy();
        session_unset();
        header("location:https://192.168.1.12/revesion02/login.php",true);
    }
    
}else{
    header("location:https://192.168.1.12/revesion02/login.php",true);
    die("");
}

?> 
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>