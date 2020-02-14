<?php
session_start();
if( !empty($_SESSION["logged_in_user"])){
    require "functions.php";
    connectDB();
    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty(trim($_POST["comment_field"]))){
        $result = array(
            "true_false"=>false,
        );
        try {
            $result["true_false"] = addcomment(trim($_POST["comment_field"]), getid($_SESSION["logged_in_user"]), $_POST["img_id"]);
            $result["content"] = trim($_POST["comment_field"]);
            $result["author"]  =  $_SESSION["logged_in_user"];
        }catch (exception $e) {
            
        }

        echo json_encode($result);
    }
        
} else{
    header("Location: login.php");
}