<?php
    session_start();
    if( empty($_SESSION["logged_in_user"])){
        require "functions.php";
        connectdb();

        $page_id = 'login';

        
        if($_SERVER["REQUEST_METHOD"] == "POST"){ 
            if(!empty($_POST["username"]) && !empty($_POST["password"])){
                if(checkuser($_POST["username"], $_POST["password"])){
                    $_SESSION["logged_in_user"]=$_POST["username"];
                    header("Location: index.php");
                
                }
            }
                
        }

        include 'header.php';
?>
    
        <div class="d-flex flex-column align-items-center py-5">
            <h1 class="mb-5">Please Log in to your account!</h1>
            <form action="" method="post" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input class="form-control" type="text" id="username" name="username" maxlength="64" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input class="form-control" type="password" name="password" id="password" maxlength="64" required>
                </div>
                <div class="form-group text-center mt-4">
                    <button class="btn btn-success" type="submit">Login</button>
                </div>
            </form>
            <a href="register.php">Register</a> 
        </div>  
    </body>
    </html>
    <?php
} else {
    header("Location: index.php");
    exit();
}