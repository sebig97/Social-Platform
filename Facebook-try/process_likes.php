<?php
session_start();
if( !empty($_SESSION["logged_in_user"])){
    require "functions.php";
    connectDB();
    if($_SERVER["REQUEST_METHOD"] == "POST" ){
        $result = array(
            "true_false"=>false,
        );
        try {
            if ($_POST['like_unlike'] == 'like') {
                $result["true_false"] = like($_POST['img_id'], getid($_SESSION["logged_in_user"]));
                
            } else {
                $result["true_false"]= unlike($_POST['img_id'], getid($_SESSION["logged_in_user"]));
            }
            $result["newlikes"]=getlikes($_POST['img_id']);
        }catch (exception $e) {
            
        }

        echo json_encode($result);
    }
        
} else{
    header("Location: login.php");
}