<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>devolper</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">

    <style>
     #sh{
            text-align: center !important;
            border: solid red 1px;
        } 

    #alert{
        text-align: center !important;
    }

    #btn {
        width: 90% !important;
    }

    #fo {
        display: flex;
        justify-content: center;
    }

    #for {
        display: flex !important;
    }

    #b1 {
        margin-right: 2px !important;
    }

    #more {
        width: 259px;
    }

    #dia {
        margin-top: 5px;
        /* width: 311.77px !important; */
    }

    #d1 {
        display: flex;
        margin-top: 2px;
    }

    #d2 {
        display: flex;

    }

    .wi {
        width: 130.7px;
    }

    #h {
        text-align: center;
        background-color: black;
        color: white;
    }

    #activ button {
        width: 273.77px;
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
                <a class="nav-link active" href="https://192.168.1.12/revesion02/devo/devo.php">home Page</a>
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
    <main class="m-auto container">

        <?php
session_start();
$username='root';
$password='';
$database= new PDO("mysql:host=localhost; dbname=revision;",$username,$password);

if(isset($_SESSION['info'])&& $_SESSION['info']->role==='devo'){
    $info2=$_SESSION['info'];

    echo'</br>';
    echo'<div id="sh" class="shadow p-3 mb-1 bg-body rounded"> Welcome'.' '.$info2->name.'</div>';

    echo'<form method="POST" id="fo">
    <button class="btn btn-outline-secondary mt-3 " id="btn" name="out" type="submit">log out</button>
    <a id="btn" class="btn btn-outline-warning mt-3" name="update" href="https://192.168.1.12/revesion02/user/profiel.php">update your information</a>
    </form>';
    
    echo'<form  method="post">
    <label class="form-label mt-4 ">SEARCH:</label>
        <input class="form-control " type="text" name="search" placeholder="email or name"/>
        <button class="btn btn-dark mt-1 w-100" type="submit" name="send01">search</button>
    </form>';
    
    if(isset($_POST['send01'])){
        $result=$database->prepare("SELECT * FROM users WHERE email LIKE :email0 OR name LIKE :email0 limit 250");
        $val="%".$_POST['search']."%";
        $result->bindParam('email0',$val);
        
        if($result->execute() && $result->rowCount()>0){
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
                 '</div>'.

                   '<form method="POST">
                 <button class="btn btn-success mt-1" value="'.$i['ID'].'" name="more" id="more">More setings</button>
                </form>'
                 .
                "</div>"
          

               ;
             }
        }else{
            echo'<div id="alert" class="alert alert-danger mt-2" role="alert">
                No data found or somthing went wrong.
            </div>
            ';
        }

       
    }
    if(isset($_POST['del'])){
        $del0=$database->prepare("DELETE FROM todo WHERE userid=:id0");
        $del0->bindParam("id0",$_POST['del']);
        $del0->execute();

        $del2=$database->prepare("DELETE FROM privet WHERE sender=:id0 OR recipient=:id0");
        $del2->bindParam("id0",$_POST['del']);
        $del2->execute();

        $del=$database->prepare("DELETE FROM users WHERE ID=:id");
        $del->bindParam("id",$_POST['del']);
        $del->execute();
        header("Refresh:2;");
    }

    if(isset($_POST['edit'])){
        $_SESSION['id']=$_POST['edit'];
        header("location:https://192.168.1.12/revesion02/edit.php",true);
    }
    if(isset($_POST['user'])){
        $rank=$database->prepare("UPDATE users SET role='user' WHERE ID=:id4");
        $rank->bindParam('id4',$_SESSION['moreid']);
        $rank->execute();
    }

    if(isset($_POST['admin'])){
        $rank02=$database->prepare("UPDATE users SET role='admin' WHERE ID=:id5");
        $rank02->bindParam('id5',$_SESSION['moreid']);
        $rank02->execute();
    }

    if(isset($_POST['devo'])){
        $rank03=$database->prepare("UPDATE users SET role='devo' WHERE ID=:id6");
        $rank03->bindParam('id6',$_SESSION['moreid']);
        $rank03->execute();
    }

    if(isset($_POST['acti'])){
        $act=$database->prepare("UPDATE users SET activated=true WHERE ID=:id7");
        $act->bindParam('id7',$_SESSION['moreid']);
        $act->execute();
    }

    if(isset($_POST['more'])){
     $_SESSION['moreid']=$_POST['more'];
        echo'
            <dialog  open="true" id="dia">
                <h3 id="h">More setings</h3>
                <div id="d1">
                    <form method="POST">
                        <button class="btn btn-warning  m-1 wi" value="" type="submit" name="user">SET AS USER</button>
                    </form>
                <form method="POST">
                <button class="btn btn-info mt-1 wi"  value="" tybe="submit" name="admin">SET AS ADMIN</button>
                </form>
                </div>
                <div id=d2>
                <form method="POST">
                <button class="btn btn-success m-1 wi" value="" tybe="submit" name="devo">SET AS DEVO</button>
                <button class="btn btn-danger wi" id="close">CLOSE</button>
                </form>
                </div>
                <form id="activ" method="post">
                <button class="btn btn-dark" name="acti">Activcate</button>
                </form>
        </dialog>
    ';
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
<!-- // var more=document.querySelectorAll("#more"); 
    // var close=document.getElementById("close");

    // for(var i=0; i<=more.length; ++i){
    //     more[i].addEventListener('click',()=>{
    //         document.getElementById('dia').setAttribute('open','true');
    //     });
    // };
    // for(var c=0; c<=more.length; ++c){
    //     close[c].addEventListener('click',()=>{
    //         document.getElementById('dia').removeAttribute('open');
    //     });
    // };
    // close.addEventListener('click',()=>{
    //     
    // })

    //  function close(){
    //     document.getElementById('dia').removeAttribute('open');
    //  } -->