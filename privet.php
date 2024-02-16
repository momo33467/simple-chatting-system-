<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privet Room</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
         #alert{
            text-align: center !important;
        }
        #dsearch{
            display: flex;
            justify-content: center;
            margin-top: 10px !important;
        }
        #bt{
            display: flex;
            justify-content: center;
            margin-top: 5px;
        }
        #result{
            text-align: center !important;
            width: 100% !important;
            justify-content: center;
            margin: 15px auto !important;
        }

        #bt2{
            width: 50% !important;
        }
      
    </style>
   
</head>
<body>
   
<?php require_once 'nav.php'; ?>


    <?php
       
        $username='root';
        $password='';
        $database= new PDO("mysql:host=localhost; dbname=revision;",$username,$password);
        
        if(isset($_SESSION["info"])){
            echo '<form  method="post">
                    <div id="dsearch">
                        <input style="width: 70% !important;" class="form-control " type="text" name="search" placeholder="email or name"/>
                    </div>
                    <div id="bt">
                        <button style="width: 50% !important;" class="btn btn-dark mt-1 w-100" type="submit" name="send01">search</button>
                    </div>
                </form>';

            if(isset($_SESSION["isRedirected"]) && $_SESSION["isRedirected"] == true){
                    echo' <form method="post">
                            <div id="bt">
                                <button style="width: 50% !important;" class="btn btn-danger mt-1 w-100" type="submit" name="cancel_redirection">Cancel redirection</button>
                            </div>
                            <br>
                    </form>';
             }

            if(isset($_POST['send01'])){
                $results=$database->prepare("SELECT id,name FROM users WHERE email LIKE :email0 OR name LIKE :email0 limit 50");
                $val="%".$_POST['search']."%";
                $results->bindParam('email0',$val);
                if(!$results->execute()){
                    echo'<div id="alert" class="alert alert-danger mt-2" role="alert">
                        somthing went wrong!
                    </div>
                    ';
                }else{
                    foreach($results as $result){
                        if(!isset($_SESSION["isRedirected"]) || $_SESSION["isRedirected"] == false){
                            echo"<div id='result' class='shadow-sm p-3 mb-2'>"
                                ."<span>".
                                'Name: '.$result['name'].
                                '</span>
                                <form method="post">
                                    <button id="bt2" type="submit" name="send02" class="btn btn-success mt-3" value="'.$result["id"].'">message</button>
                                </form>
                            </div>
                            ';
                        }else{
                            echo"<div id='result' class='shadow-sm p-3 mb-2'>"
                                ."<span>".
                                'Name: '.$result['name'].
                                '</span>
                                <form method="post">
                                    <button id="bt2" type="submit" name="send03" class="btn btn-warning mt-3" value="'.$result["id"].'">redirect</button>
                                </form>
                            </div>
                            ';
                        }
                    }
                }

            }

            if(isset($_POST["cancel_redirection"])){
                $_SESSION["isRedirected"] = false;
                echo '<script>window.location.href = "https://192.168.1.12/revesion02/chat.php#last";</script>';

            }
            if(isset($_POST["send02"])){
                $_SESSION["privet"]=$_POST["send02"];
                echo '<script>window.location.href = "https://192.168.1.12/revesion02/chat.php#last";</script>';
            }
            if(isset($_POST["send03"])){
                if(isset($_SESSION["isRedirected"]) && $_SESSION["isRedirected"] == true){
                    $creatm = $database->prepare("INSERT INTO privet (text,sender,recipient,files,Ftype) VALUES (:tex,:sen,:rec,:img,:ftype)");
                    $creatm->bindParam("tex",$_SESSION["mcontent"]);
                    $creatm->bindParam("sen",$_SESSION["info"]->ID);
                    $creatm->bindParam("rec",$_POST["send03"]);

                    if(isset($_SESSION["imageUrl"]) && $_SESSION["imageUrl"] != ''){
                        $creatm->bindParam("img",$_SESSION["imageUrl"]);
                        $creatm->bindParam("ftype",$_SESSION["ftype"]);
                    }else{
                        $creatm->bindValue(":img", null, PDO::PARAM_NULL);
                        $creatm->bindValue(":ftype", null, PDO::PARAM_NULL);
                    }
                    
                    if($creatm->execute()){
                        $_SESSION["privet"]=$_POST["send03"];
                        $_SESSION["isRedirected"] = false;
                        echo '<script>window.location.href = "https://192.168.1.12/revesion02/chat.php#last";</script>';
                    }else{
                        // $errorInfo = $creatm->errorInfo();
                        // . $errorInfo[2] . 
                        echo '<div id="alert" class="alert alert-danger mt-2" role="alert">
                                Something went wrong.
                            </div>';
                    }
                }
            }
        }else{
            header("location:https://192.168.1.12/revesion02/login.php",true);
        }
    ?>
</body>
</html>