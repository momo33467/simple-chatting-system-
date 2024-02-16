<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Public chat</title>

    <style>
        .content {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 70px;
        }

        #btn {
            margin-left: 5px;
            width: 70px;
        }

        #name {
            font-size: larger;
            font-weight: 100;
        }

        #alert {
            text-align: center !important;
            margin-top: 5px;
        }
        #replay{
            margin-left: 3px;
            color: #41e0e0;
            font-size: 19px;
            cursor: pointer;
            /* font-family: 'Times New Roman', Times, serif; */
        }
        #rep{
            border: none;
            background: none;
            padding: 0;
            
        }

        #conta{
            word-wrap: break-word;
            white-space: pre-wrap;
            margin: 0 !important;
        }

        #for {
            width: 95%;
            display: flex;
            background-color: white;
            padding: 5px;
            margin: 0 !important;
        }

        footer {
            display: flex !important;
            justify-content: center !important;
            position: fixed !important;
            bottom: 0;
            width: 100%;
            padding: 10px;
            background-color: white;
        }

    </style>
</head>

<body>
  
    <?php require_once 'nav.php'; ?>
   
    <?php
    if(!isset($_SESSION['replay'])){
        $_SESSION['replay'] = -1;
    }
    $username = 'root';
    $password = '';
    $database = new PDO("mysql:host=localhost; dbname=revision;", $username, $password);
    $id = $_SESSION['info']->ID;
    if (isset($_SESSION['info'])) {
        if (isset($_POST['addpost'])) {
            $postText = htmlspecialchars($_POST['post'], ENT_QUOTES, 'UTF-8'); // Sanitize the post text input
            $add = $database->prepare("INSERT INTO todo(text,userId,replay) VALUES(:text2,:id1,:replay)");
            $add->bindParam('text2', $postText);
            $add->bindParam('id1', $id);
            $add->bindParam('replay', $_SESSION["replay"]);
            if ($add->execute()) {
                echo '<script>window.location.href = "https://192.168.1.12/revesion02/todo.php";</script>';
            } else {
                echo '<div id="alert" class="alert alert-danger" role="alert">
                 An error has occurred!
            </div>
        ';
            }
        }
    } else {
        header("location:https://192.168.1.12/revesion02/login.php", true);
    }
    if (isset($_SESSION['info'])) {
        if($_SESSION["replay"] == -1){
            $mylist = $database->prepare("SELECT todo.ID,time,name,text FROM users JOIN todo ON todo.userid = users.id WHERE replay = :replay2 ORDER BY todo.ID DESC limit 50");
            $mylist->bindParam("replay2", $_SESSION["replay"]);
         }else{
            $mylist = $database->prepare("SELECT todo.ID,time,name,text FROM users JOIN todo ON todo.userid = users.id WHERE replay = :replay2 ORDER BY todo.ID DESC limit 1000");
            $mylist->bindParam("replay2", $_SESSION["replay"]);
         }
        
        if($_SESSION["replay"]!= -1){
            if(!isset($_SESSION["index"]) || count($_SESSION["arr2"]) == 0 || !$_SESSION["back"]){
                    echo'
                        <form method="post">
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-warning" id="sub" style="width: 40% !important; margin: 8px;" type="submit" name="back">back</button>
                            </div>
                        </form>

                        </br>
                        <div style = "border:dotted red; width:auto; margin-left:auto; margin-right:auto; margin-top:0px; margin-bottom:5px; padding: 10px;" class="d-flex justify-content-center">
                            <main>'.$_SESSION["cont04"].'</main>
                        </div>
                   ';
            }else{
                echo'
                <form method="post">
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-warning" id="sub" style="width: 40% !important; margin: 8px;" type="submit" name="back">back</button>
                    </div>
                </form>

                </br>
                <div style = "border:dotted red; width:auto; margin-left:auto; margin-right:auto; margin-top:0px; padding: 10px;" class="d-flex justify-content-center">
                    <main>'.$_SESSION["arr2"][$_SESSION["index"]].'</main>
                </div>
            ';
            }
            
        }
        if ($mylist->execute()) {
            echo '<div class="content">';
                foreach ($mylist as $i) {
                    echo '<div style = "margin-bottom:15px" class="card text-center">
                <div id="name" class="card-header">
                name: ' . htmlspecialchars($i["name"], ENT_QUOTES, 'UTF-8') . '
                </div>
                <div class="card-body">
                <p id = "conta" class="card-text">' . htmlspecialchars($i["text"], ENT_QUOTES, 'UTF-8') . '</p>
                </div>
                <div class="card-footer text-muted">
                ' . htmlspecialchars($i["time"], ENT_QUOTES, 'UTF-8') . '
                '.' 
                <form method="post">
                    <button id = "rep" name = "replaybtn" type = "submit" value = "'.$i["ID"].'">
                        <p id = "replay">replay</p>
                    </button>
                    <input type="hidden" name = "mescont" value = "'.$i["text"].'">
                </form>
                '.'
                </div>
            </div>';
                }
            } else {
                echo '<div id="alert" class="alert alert-danger" role="alert">
            An error has occurred!
            </div>
            ';
            }
        echo '</div>';

        echo '<footer id="box">
                <form class="container" id="for" method="post">
                    <textarea style="width:100%;" class="form-control" id="tex" name="post" rows="1" autocomplete="off"></textarea>
                    <button id="btn" class="btn btn-outline-dark" name="addpost">Post</button>
                </form>

                <a style="text-decoration: none; display:block; margin-left:5px; width: 24px;" href="https://192.168.1.12/revesion02/todo.php#navers">
                    <i style = "width:100%;" class="fa fa-arrow-up" aria-hidden="true"></i>
                </a>
             </footer>';
    
    } else {
        header("location:https://192.168.1.12/revesion02/login.php", true);
    }

    if(isset($_POST["back"])){
        $_SESSION["back"] = true; 
        $index = 0;
        for($i = 0; $i<count($_SESSION["arr"]); $i++){
            if($_SESSION["arr"][$i] == $_SESSION["replay"]){
                $index = $i;
                break;
            }
        }
        $_SESSION["index"] = $index-1;

        if($index == 0){
            $_SESSION["arr"] = [];
            $_SESSION["arr2"] = [];
            $_SESSION["replay"] = -1;
        }else{
            $_SESSION["replay"] = $_SESSION["arr"][$index - 1];
        }

        unset($_SESSION["arr"][$index]);
        $_SESSION["arr"] = array_values($_SESSION["arr"]);
        unset($_SESSION["arr2"][$index]);
        $_SESSION["arr2"] = array_values($_SESSION["arr2"]);

        echo '<script>window.location.href = "https://192.168.1.12/revesion02/todo.php";</script>';
    }
    if(isset($_POST["replaybtn"])){
        $_SESSION["back"] = false;
        $_SESSION["replay"] = $_POST["replaybtn"];
        $_SESSION["cont04"] = $_POST["mescont"];
        $_SESSION["arr"][] = $_POST["replaybtn"];
        $_SESSION["arr2"][] = $_POST["mescont"];
        echo '<script>window.location.href = "https://192.168.1.12/revesion02/todo.php";</script>';
    }
    ?>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

</body>

</html>
