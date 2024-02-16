<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>userpage</title>
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
            width: 50% !important;
        }

        #logo{
            width: 50px;
        }

        #bar{
            width: 100% !important;
            justify-content: center;
        }

        form{
            display: flex;
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
                <a class="nav-link active" href="https://192.168.1.12/revesion02/user/userpage.php">home Page</a>
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
if(isset($_SESSION['info'])&& $_SESSION['info']->role==='user'){
    $info2=$_SESSION['info'];

    echo'<div id="sh" class="shadow p-3 mb-1 bg-body rounded"> Welcome'.' '.$info2->name.'</div>';

    echo'<form method="POST">
    <button class="btn btn-outline-secondary mt-3 " id="btn" name="out" type="submit">log out</button>
    <a id="btn" class="btn btn-outline-warning mt-3" name="update" href="https://192.168.1.12/revesion02/user/profiel.php">update your information</a>
    </form>';
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