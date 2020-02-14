<?php
session_start();
if( !empty($_SESSION["logged_in_user"])){
    require "functions.php";
    connectDB();
    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty(($_POST["delete"])) && $_POST["delete"]==="Delete"){
      $filename=realpath(__DIR__."/".getfilenamefromid($_POST["imgid"]));
      if(file_exists($filename)){
          if($handle=opendir(realpath(__DIR__."/img"))){
              while(false !== ($img = readdir($handle))){
                  if($img!="." && $img !=".."){
                    unlink($filename);
                  }
              }
              closedir($handle);
          }
      }
      delete_img($_POST["imgid"]); 
      header("Location: index.php");

    }
        
} else{
    header("Location: login.php");
}