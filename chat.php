<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
       
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: black;
        }

        #imdiv{
            display: hidden;
        }

        #mainDiv{
           
            margin-top: 10px;
            padding:  3px 2px;
            background-color: rgb(71 71 109 / 90%);
            display: inline-block;
    
            max-width: 260px;
            color: aliceblue;
            border-radius: 4px;
           
        }

       
        #mainDiv2 {
          
            margin-top: 10px ;
            padding: 3px 2px;
            background-color: rgb(4 79 101 / 90%);
            display: inline-block;
            max-width: 260px;
            color: aliceblue;
            border-radius: 4px;
           
        }

        #foot{
            border-top: solid 1px red;
            color: rgb(228, 211, 190);
        }

        #topp{
            color: rgba(116, 173, 190, 0.904);
            font-family: 'Times New Roman', Times, serif;
        }

        .content {
            background-color: black;
            flex: 1;
            overflow-y: auto;
            padding-bottom: 70px;
           
        }

        footer {
            display: flex !important;
            justify-content: center !important;
            position: fixed !important;
            bottom: 0;
            width: 100%;
            padding: 10px;
            background-color: black;
        }

        #for {
            width: 95%;
            display: flex;
            background-color: black;
            padding: 5px;
            margin: 0 !important;
        }

        #for2{
            width: 5%;
            display: flex;
            background-color: black;
            padding: 5px;
            margin: 0 !important;
        }

        #tex {
            width: 100%;
            background-color: #2a3942;
            border: none;
            color: white;
            max-height: 300px;
            
        }

        #btn {
            margin-left: 5px;
            width: 70px;
        }
        #btn2{
            margin: 0px;
            width: 50%;
            font-size: smaller;
        }

        /* CSS to remove the border around the trash icon button */
        .delete-button {
            border: none;
            background: none;
            padding: 0;
            
        }
     
       #conta{
        word-wrap: break-word;
        white-space: pre-wrap;
        margin: 0 !important;
       }

       #parent{
        display: flex;
        justify-content: flex-end;  /* Move to the right */
        align-items: center;  /* Center vertically */
       }
    </style>


</head>
<body>


    
    <!-- واتساب الغلابة -->
    <?php
 
       // غالبا سبب تعليق الفيديو هو انه الرابط شطوله. فممكن تستبدله بطريقة حفظ الملفات فالسيرفر
        ob_start();
        require_once 'nav.php';
        $username = 'root';
        $password = '';
        $database = new PDO("mysql:host=localhost; dbname=revision;", $username, $password);

        if (isset($_SESSION["privet"])) {
           $ste = $database->prepare("UPDATE privet SET isread = TRUE WHERE sender = :sender AND recipient  = :reciver");
           $ste->bindParam("sender", $_SESSION["privet"]);
           $ste->bindParam("reciver", $_SESSION["info"]->ID);
           if(!$ste->execute()){
            echo "ERROR";
           }

            echo '<div class="content">'; // Start content div

            $reciver = $database->prepare("SELECT name FROM users WHERE ID = :rec2");
            $reciver->bindParam("rec2", $_SESSION["privet"]);
            if (!$reciver->execute()) {
                echo '<div id="alert" class="alert alert-danger mt-2" role="alert">
                    something went wrong.
                </div>';
            }

            $rname = ($reciver->fetchObject())->name;
            $sname = $_SESSION["info"]->name;

            $messages = $database->prepare("SELECT *, COALESCE(files, '') AS files FROM privet WHERE (sender = :senid AND recipient = :rcid) OR (sender = :rcid AND recipient = :senid)");
            $messages->bindParam("senid", $_SESSION["info"]->ID);
            $messages->bindParam("rcid", $_SESSION["privet"]);

            if ($messages->execute()) {
                foreach ($messages as $message) {
                    $senderid = $message["sender"];
                    $tex = $message["text"];

                    if ($senderid == $_SESSION["info"]->ID) {
                        if($message["isread"] == 1){
                            if($message["files"] == ''){
                                $imageUrl = '';
                            }else{
                                $imageUrl = "data:".$message['Ftype'].";base64,".base64_encode($message['files']);
                                
                            }
                            
                            if(mb_substr($message["Ftype"], 0, 1) == "i"){
                                echo '<div id="mainDiv">
                                    <div style="display: flex;" id="topp">'.htmlspecialchars($sname, ENT_QUOTES, 'UTF-8').'
                                        <div>
                                            <i style="font-size:15px; margin-left: 3px;" class="fa">&#xf00c;</i>
                                        </div>
                                    </div>
    
                                    <div class="image-container"'. (!empty($imageUrl) ? '' : ' style="display: none;"') .'>
                                        <img style = "width:100%;"; src="'.htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8').'" >
                                    </div>

                                    <p id="conta">'.htmlspecialchars($message["text"], ENT_QUOTES, 'UTF-8').'
                                        <div style="display: flex; justify-content: center; align-items: center;" id="foot">'. htmlspecialchars($message["time"], ENT_QUOTES, 'UTF-8') . '
                                            <form id="myform" class="delete-form" method="post">
                                                <button type="submit" name="strush" class="delete-button">
                                                    <i style="font-size:24px; color:red; margin-left:5px" class="fa delete-icon">&#xf014;</i>
                                                </button>
                                                <input type="hidden" value="'.$message["ID"].'" name="mid">
                                            </form>
                                            <form method="post">
                                                <button type="submit" style="border: none; background: none; padding: 0;" name="edit45">
                                                    <i style="font-size:24px; margin-left:5px" class="fa">&#xf040;</i>
                                                </button>
                                                <input type="hidden" value="'.$message["ID"].'" name="mid2">
                                            </form>
                                            <form method="post">
                                                <button type="submit" style="border: none; background: none; padding: 0; margin-left:1px;" name="redirect">
                                                    <i style="font-size:24px" class="fa">&#xf0a9;</i>
                                                </button>
                                                <input type="hidden" value="'.$message["ID"].'" name="mid3">
                                            </form>
                                        </div>
                                    </p>
                                </div>
                                <br>
                                ';
                            }elseif(mb_substr($message["Ftype"], 0, 1) == "v" or empty($imageUrl)){
                                    echo '<div id="mainDiv">
                                    <div style="display: flex;" id="topp">'.htmlspecialchars($sname, ENT_QUOTES, 'UTF-8').'
                                        <div>
                                            <i style="font-size:15px; margin-left: 3px;" class="fa">&#xf00c;</i>
                                        </div>
                                    </div>

                                    <div class="image-container"'. (!empty($imageUrl) ? '' : ' style="display: none;"') .'>
                                        <video width="100%" controls>
                                            <source src="'.htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8').'" type="'.$message['Ftype'].'">
                                        </video>
                                    </div>

                                    <p id="conta">'.htmlspecialchars($message["text"], ENT_QUOTES, 'UTF-8').'
                                        <div style="display: flex; justify-content: center; align-items: center;" id="foot">'. htmlspecialchars($message["time"], ENT_QUOTES, 'UTF-8') . '
                                            <form id="myform" class="delete-form" method="post">
                                                <button type="submit" name="strush" class="delete-button">
                                                    <i style="font-size:24px; color:red; margin-left:5px" class="fa delete-icon">&#xf014;</i>
                                                </button>
                                                <input type="hidden" value="'.$message["ID"].'" name="mid">
                                            </form>
                                            <form method="post">
                                                <button type="submit" style="border: none; background: none; padding: 0;" name="edit45">
                                                    <i style="font-size:24px; margin-left:5px" class="fa">&#xf040;</i>
                                                </button>
                                                <input type="hidden" value="'.$message["ID"].'" name="mid2">
                                            </form>
                                            <form method="post">
                                                <button type="submit" style="border: none; background: none; padding: 0; margin-left:1px;" name="redirect">
                                                    <i style="font-size:24px" class="fa">&#xf0a9;</i>
                                                </button>
                                                <input type="hidden" value="'.$message["ID"].'" name="mid3">
                                            </form>
                                        </div>
                                    </p>
                                </div>
                                <br>
                                ';
                            }

                        }else{
                            if($message["files"] == ''){
                                $imageUrl = '';
                            }else{
                                $imageUrl = "data:".$message['Ftype'].";base64,".base64_encode($message['files']);
                            }

                            if(mb_substr($message["Ftype"], 0, 1) == "i" or empty($imageUrl)){
                                    echo'<div id="mainDiv">
                                <div style="display: flex;" id="topp">'.htmlspecialchars($sname, ENT_QUOTES, 'UTF-8').'</div>

                                    <div class="image-container"'. (!empty($imageUrl) ? '' : ' style="display: none;"') .'>
                                            <img width = "100%"  src="'.htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8').'">
                                    </div> 

                                    <p id="conta">' . htmlspecialchars($message["text"], ENT_QUOTES, 'UTF-8') . '
                                        <div style="display: flex; justify-content: center; align-items: center;" id="foot">'.htmlspecialchars($message["time"], ENT_QUOTES, 'UTF-8').'
                                            <form id="myform" class="delete-form" method="post">
                                            <button type="submit" name="strush" class="delete-button">
                                            <i style="font-size:24px; color:red; margin-left:5px" class="fa delete-icon">&#xf014;</i>
                                            </button>
                                            <input type="hidden" value="' . $message["ID"] . '" name="mid">
                                            </form>
                                            <form method="post">
                                            <button type="submit" style="border: none; background: none; padding: 0;" name="edit45">
                                            <i style="font-size:24px; margin-left:5px" class="fa">&#xf040;</i>
                                            </button>
                                            <input type="hidden" value="' . $message["ID"] . '" name="mid2">
                                            </form>
                                            <form method="post">
                                            <button type="submit" style="border: none; background: none; padding: 0; margin-left:1px;" name="redirect">
                                            <i style="font-size:24px" class="fa">&#xf0a9;</i>
                                            </button>
                                            <input type="hidden" value="' . $message["ID"] . '" name="mid3">
                                            </form>
                                    </p>
                                    </div>
                                </div>
                                <br>';
                            }elseif(mb_substr($message["Ftype"], 0, 1) == "v"){
                                echo'<div id="mainDiv">
                                <div style="display: flex;" id="topp">'.htmlspecialchars($sname, ENT_QUOTES, 'UTF-8').'</div>

                                    <div class="image-container"'. (!empty($imageUrl) ? '' : ' style="display: none;"') .'>
                                        <video width="100%" controls>
                                             <source src="'.htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8').'" type="'.$message['Ftype'].'">
                                        </video>
                                    </div> 

                                    <p id="conta">' . htmlspecialchars($message["text"], ENT_QUOTES, 'UTF-8') . '
                                        <div style="display: flex; justify-content: center; align-items: center;" id="foot">'.htmlspecialchars($message["time"], ENT_QUOTES, 'UTF-8').'
                                            <form id="myform" class="delete-form" method="post">
                                            <button type="submit" name="strush" class="delete-button">
                                            <i style="font-size:24px; color:red; margin-left:5px" class="fa delete-icon">&#xf014;</i>
                                            </button>
                                            <input type="hidden" value="' . $message["ID"] . '" name="mid">
                                            </form>
                                            <form method="post">
                                            <button type="submit" style="border: none; background: none; padding: 0;" name="edit45">
                                            <i style="font-size:24px; margin-left:5px" class="fa">&#xf040;</i>
                                            </button>
                                            <input type="hidden" value="' . $message["ID"] . '" name="mid2">
                                            </form>
                                            <form method="post">
                                            <button type="submit" style="border: none; background: none; padding: 0; margin-left:1px;" name="redirect">
                                            <i style="font-size:24px" class="fa">&#xf0a9;</i>
                                            </button>
                                            <input type="hidden" value="' . $message["ID"] . '" name="mid3">
                                            </form>
                                    </p>
                                    </div>
                                </div>
                                <br>';
                            }
                            
                        }
                        
                    } else if ($senderid == $_SESSION["privet"]) {
                        if($message["files"] == ''){
                            $imageUrl = '';
                        }else{
                            $imageUrl = "data:".$message['Ftype'].";base64,".base64_encode($message['files']);
                        }

                        if(mb_substr($message["Ftype"], 0, 1) == "i"){
                            echo '
                            <div id = "parent">
                                <div id="mainDiv2">
                                <div style="display: flex;" id="topp">'.htmlspecialchars($rname, ENT_QUOTES, 'UTF-8').'
                                </div>

                                <div class="image-container"'. (!empty($imageUrl) ? '' : ' style="display: none;"') .'>
                                        <img width = "100%"  src="'.htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8').'">
                                </div>

                                <p id="conta">'.htmlspecialchars($message["text"], ENT_QUOTES, 'UTF-8').'
                                    <div style="display: flex; justify-content: center; align-items: center;" id="foot">'.htmlspecialchars($message["time"], ENT_QUOTES, 'UTF-8').'
                                        <form method="post">
                                            <button type="submit" style="border: none; background: none; padding: 0; margin-left:1px;" name="redirect">
                                                <i style="font-size:24px" class="fa">&#xf0a9;</i>
                                            </button>
                                            <input type="hidden" value="' . $message["ID"] . '" name="mid3">
                                        </form>
                                    </div>
                                </div>
                            </p>
                        </div>
                        ';
                        }elseif(mb_substr($message["Ftype"], 0, 1) == "v" or empty($imageUrl)){
                                echo '
                                <div id = "parent">
                                    <div id="mainDiv2">
                                    <div style="display: flex;" id="topp">'.htmlspecialchars($rname, ENT_QUOTES, 'UTF-8').'
                                    </div>

                                    <div class="image-container"'. (!empty($imageUrl) ? '' : ' style="display: none;"') .'>
                                        <video width="100%" controls>
                                            <source src="'.htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8').'" type="'.$message['Ftype'].'">
                                        </video>
                                    </div>

                                    <p id="conta">'.htmlspecialchars($message["text"], ENT_QUOTES, 'UTF-8').'
                                        <div style="display: flex; justify-content: center; align-items: center;" id="foot">'.htmlspecialchars($message["time"], ENT_QUOTES, 'UTF-8').'
                                            <form method="post">
                                                <button type="submit" style="border: none; background: none; padding: 0; margin-left:1px;" name="redirect">
                                                    <i style="font-size:24px" class="fa">&#xf0a9;</i>
                                                </button>
                                                <input type="hidden" value="' . $message["ID"] . '" name="mid3">
                                            </form>
                                        </div>
                                    </div>
                                </p>
                            </div>
                            ';
                        } 
                    } else {
                        echo '<div id="alert" class="alert alert-danger mt-2" role="alert">
                            something went wrong.
                        </div>';
                    }
                }
            } else {
                echo '<div id="alert" class="alert alert-danger mt-2" role="alert">
                        something went wrong.
                    </div>';
            }

            echo '</div>'; 
            if(!isset($_SESSION["isedit"]) || $_SESSION["isedit"] == false){
                echo '<footer id="box">
                        <form class="container" id="for" method="post" enctype="multipart/form-data">
                            <textarea oninput="autoResize()" class="form-control" id="tex" name="message" rows="1" autocomplete="off"></textarea>
                            
                                <label style = "margin-left:3px;" for="fileInput" class="btn btn-outline-secondary ">
                                    <i style="font-size:15px" class="fa fa-upload"></i> 
                                </label>
                                <input type="file" id="fileInput" name = "files" style="display:none" accept=".jpg, .jpeg, .png, .gif, .bmp, .tiff, .webp, .svg, .mp4, .avi, .mkv, .wmv, .mov, .flv, .3gp, .webm, .mpeg, .rm, .swf, .ogv">
                            
                            <button id="btn" class="btn btn-outline-warning" name="send">Send</button>
                        
                        </form>
                        
                        <a style="text-decoration: none; display:block; margin-left:5px; width: 24px;" href="https://192.168.1.12/revesion02/chat.php#navers">
                            <i style = "width:100%;" class="fa fa-arrow-up" aria-hidden="true"></i>
                        </a>
                    </footer>';

            }else{
                    echo '<footer id="box">
                    <form class="container" id="for" method="post">
                        <textarea class="form-control" id="tex" name="message" rows="1" autocomplete="off">'.$_SESSION["cont"].'</textarea>
                        <button id="btn" class="btn btn-outline-warning" name="send">Send</button>
                    </form>
                    <form  id="for2" method="post">
                        <button type="submit" style="color:red; font-size:25px;" class="delete-button" name = "Cancel">x</button>
                    </form>
                </footer>';
                
            }
            
            if(isset($_POST["Cancel"])){
                $_SESSION["isedit"] = false;
                header("location: https://192.168.1.12/revesion02/chat.php", true);
            }
            
            if(isset($_POST["mid"])){ // btn does not appear in the request 
                $maid = $database->prepare("SELECT * FROM privet WHERE ID =:mid");
                $maid->bindParam("mid",$_POST["mid"]);
                $maid->execute();
                $mid2 = $maid->fetchObject();

                if($mid2->sender == $_SESSION["info"]->ID){
                    $del2 = $database->prepare("DELETE FROM privet WHERE ID = :mid");
                    $del2->bindParam("mid",$_POST["mid"]);
                    if(!$del2->execute()){
                        echo '<div id="alert" class="alert alert-danger mt-2" role="alert">
                        something went wrong during deletion.
                        </div>';
                    }else{
                        header("location: https://192.168.1.12/revesion02/chat.php", true);
                    }
                }else{
                    echo '<div id="alert" class="alert alert-danger mt-2" role="alert">
                        something went wrong.
                    </div>';
                }
            }

            if(isset($_POST["edit45"])){
                $mdaata = $database->prepare("SELECT * FROM privet WHERE ID = :mid2");
                $mdaata->bindParam("mid2",$_POST["mid2"]);
                $mdaata->execute();
                $mdaata2 = $mdaata->fetchObject();

                if($mdaata2->sender == $_SESSION["info"]->ID){
                    $_SESSION["cont"] = $mdaata2->text;
                    $_SESSION["isedit"] = true;

                    $_SESSION["eid"] = $mdaata2->ID;
                    header("location: https://192.168.1.12/revesion02/chat.php#last", true);
                }
            }

            if (isset($_POST["send"])) {
                if(isset($_SESSION["isedit"]) && $_SESSION["isedit"] == true){
                    $editq = $database->prepare("UPDATE privet SET text = :newtext WHERE ID=:mid3");
                    $editq->bindParam("newtext",$_POST["message"]);
                    $editq->bindParam("mid3",$_SESSION["eid"]);

                    if (!$editq->execute()) {
                        echo '<div id="alert" class="alert alert-danger mt-2" role="alert">
                            something went wrong during editing.
                        </div>';
                    } else {
                        $_SESSION["isedit"] = false;
                        header("location: https://192.168.1.12/revesion02/chat.php#last", true);
                    }
                }else{
                    $allowedExtensions = array("jpg", "jpeg", "png", "gif", "bmp", "tiff", "webp", "svg", "mp4", "avi", "mkv", "wmv", "mov", "flv", "3gp", "webm", "mpeg", "rm", "swf", "ogv");
                    $message = $_POST["message"];
                    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); // Sanitize the message input

                    $type=$_FILES['files']['type'];
                    $name=$_FILES['files']['name'];
                    $fiel=$_FILES['files']['tmp_name'];
                    $data=file_get_contents($fiel);

                    
                    $send = $database->prepare("INSERT INTO privet (text,sender,recipient,files,Fname,Ftype) VALUES (:tex,:sen,:rec,:f,:n,:t)");
                    $send->bindParam("tex", $message);
                    $send->bindParam("sen", $_SESSION["info"]->ID);
                    $send->bindParam("rec", $_SESSION["privet"]);

                    if (!empty($_FILES['files']['name'])) {
                        $fileType = $_FILES['files']['type'];
                        $fileName = $_FILES['files']['name'];
                        $fileTmpName = $_FILES['files']['tmp_name'];
                        $fileData = file_get_contents($fileTmpName);
                    
                        // Validate file type
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        if (in_array($fileExtension, $allowedExtensions)) {
                            $send->bindParam('f', $fileData);
                            $send->bindParam('n', $fileName);
                            $send->bindParam('t', $fileType);
                        } else {
                            $send->bindValue('f', null, PDO::PARAM_NULL);
                            $send->bindValue('n', null, PDO::PARAM_NULL);
                            $send->bindValue('t', null, PDO::PARAM_NULL);
                        }
                    }else{
                        $send->bindValue('f', null, PDO::PARAM_NULL);
                        $send->bindValue('n', null, PDO::PARAM_NULL);
                        $send->bindValue('t', null, PDO::PARAM_NULL);
                    }

                    if (!$send->execute()) {
                        echo '<div id="alert" class="alert alert-danger mt-2" role="alert">
                            something went wrong during sending.
                        </div>';
                    
                    } else {
                        header("location: https://192.168.1.12/revesion02/chat.php#last", true);
                    }
                }
                
            }

            if(isset($_POST["redirect"])){
                $check = $database->prepare("SELECT text,files,Ftype,COALESCE(files, '') AS files FROM privet WHERE (sender = :isSender OR recipient = :isrec) AND (ID=:mid3)");
                $check->bindParam("isSender",$_SESSION["info"]->ID);
                $check->bindParam("isrec",$_SESSION["info"]->ID);
                $check->bindParam("mid3",$_POST["mid3"]);

              
                $check->execute();
                if($check->rowCount() == 1){
                    $masdata = $check->fetchObject();
                    $_SESSION["mcontent"] = $masdata->text;
                    $_SESSION["isRedirected"] = true;

                    if($masdata->files == ''){
                        $_SESSION["imageUrl"] = '';
                    }else{
                        $_SESSION["imageUrl"] = $masdata->files;
                        $_SESSION["ftype"] = $masdata->Ftype;
                    }

                    header("location: https://192.168.1.12/revesion02/privet.php", true);        
                }
            }

        } else {
            header("location: https://192.168.1.12/revesion02/login.php", true);
        }
        ob_end_flush();
    ?>

    <div id = "last"></div>
<script>
    // Add a submit event listener to the specific form with the class 'delete-form'
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener("submit", function (event) {
            // Check if the form's button has the class 'delete-button'
            var deleteButton = document.querySelector('.delete-button');
            if (deleteButton) {
                event.preventDefault();
                var confirmation = confirm("Are you sure you want to delete this for all?");
                if (confirmation) {
                    // Submit the form
                    form.submit();
                }
            }
        });
    });

    if (screen.width > 500) {
    var elements = document.querySelectorAll("#mainDiv");

    elements.forEach(function(element) {
        element.style.marginLeft = '105px';
        element.style.maxWidth = '450px';
    });

    }

    if (screen.width > 500) {
        var elements = document.querySelectorAll("#mainDiv2");

        elements.forEach(function(element) {
            element.style.marginRight = '105px';
            element.style.maxWidth = '450px';
        });
    }

    var texar =document.getElementById("tex");
    texar.style.cssText = `height: ${texar.scrollHeight}px; overflow-y: hidden`;
    texar.addEventListener("input", function(){
        this.style.height ="auto";
        this.style.height = `${this.scrollHeight}px`;

    });

</script>

</body>
</html>
