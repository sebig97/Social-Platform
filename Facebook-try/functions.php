<?php


function connectDB(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    global $conn;
    $conn = new PDO("mysql:host=$servername;dbname=app", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}


function adduser($username, $password){
    global $conn;
    $sql = 'INSERT INTO user(username, `password`) VALUES(:username, :pass)';
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        'username' => $username,
        'pass' => $password
    ]    
    );

    return true;
}

function getid($username){
    global $conn;
    $sql = 'SELECT id FROM user WHERE username=:user';
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        'user' => $username,
    ]);
    return $stmt -> fetchColumn();
}
function getuserfromid($id){
    global $conn;
    $sql = 'SELECT username FROM user WHERE id=:id';
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        'id' => $id,
    ]);
    return $stmt -> fetchColumn();
}

function getfilenamefromid($id){
    global $conn;
    $sql = 'SELECT filename FROM imagini WHERE id=:id';
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        'id' => $id,
    ]);
    return $stmt -> fetchColumn();
}

function checkuser($username, $password){
    global $conn;
    $sql='SELECT COUNT(id) FROM user WHERE username=:user AND `password`=:pass';
    $stmt = $conn -> prepare($sql);
    $stmt ->execute([
        'user' => $username,
        'pass' => $password
    ]);
    $nr = $stmt -> fetchColumn();
    
    if($nr==1) {
        return true;
    } 
    else { 
    return false;
    } 
}

function addphoto($filename, $user){
    global $conn;
    $sql = 'INSERT INTO imagini(`filename`, user) VALUES(:flname, :user)';
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        'flname' => $filename,
        'user' => $user
    ]    
    );

    return true;
}

function delete_img($img_id){
try {
    global $conn;
    $sql =  'DELETE FROM imagini WHERE id=:imgid';
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        'imgid' => $img_id
    ]);
    return true;
} catch (exception $e) {
    return false;
}
}
function getimages($user=null){
    global $conn;
    $sql='SELECT i.id, i.user, i.filename, i.date, COUNT(l.id) as nr_likes FROM imagini i
    LEFT JOiN likes l on l.id_img=i.id GROUP BY i.id ORDER BY nr_likes DESC, i.date DESC';
   
    $stmt = $conn -> prepare($sql);
    $stmt ->execute();
    
    return $stmt -> fetchAll(\PDO::FETCH_ASSOC);
    

}



function addcomment($content, $user_id, $img_id){
    try {
    global $conn;
    $sql = 'INSERT INTO comentarii(content, img_id, `user_id`) VALUES(:content, :imgid, :userid)';
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        'content' => $content,
        'userid' => $user_id,
        'imgid' => $img_id
    ]    
    );

    return true;
    }catch (exception $e) {
         return false;
    }
}

function getcomments($img_id){
    global $conn;
    $sql='SELECT c.id, c.content, c.user_id FROM comentarii c WHERE c.img_id = :imgid ';

    $stmt = $conn -> prepare($sql);
    $stmt ->execute(['imgid' => $img_id]);

    return $stmt -> fetchAll(\PDO::FETCH_ASSOC);
}


function checklike($img_id,$user_id){
    try {
        global $conn;
        $sql = 'SELECT COUNT(id) FROM likes WHERE id_img=:img_id AND id_user=:userid';
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([
            'img_id' => $img_id,
            'userid'=> $user_id

        ]    
        );
        $nr = $stmt -> fetchColumn();
    
        if($nr==1) {
            return true;
        } 
        else { 
            return false;
        } 
    } catch (exception $e) {
        return false;
    }
}

function like($img_id, $user_id){
    try {
    global $conn;
    $sql =  'INSERT INTO likes(id_img,id_user) VALUES(:imgid,:userid)';
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        'imgid' => $img_id,
        'userid' =>$user_id
    ]    
    );

    return true;
    } catch (exception $e) {
        return false;
    }
}

function unlike($img_id, $user_id){
   
try {
    global $conn;
    $sql =  'DELETE FROM likes WHERE id_img=:imgid AND id_user=:userid';
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        'imgid' => $img_id,
        'userid' =>$user_id
    ]    
    );
    return true;
} catch (exception $e) {
    return false;
}

}
function getlikes($img_id){
    global $conn;
    $sql='SELECT  COUNT(id) as nr_likes FROM likes
     WHERE id_img=:imgid';
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        'imgid' => $img_id,
    ]    
    );
    
    return $stmt -> fetchColumn();
}