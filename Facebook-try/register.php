<?php
    session_start();
    if( empty($_SESSION["logged_in_user"])){
        require "functions.php";
        connectdb();

        $page_id = 'register';
        
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(!empty($_POST["username"]) && !empty($_POST["password"])){
                if(adduser($_POST["username"], $_POST["password"])){
                    header("Location: login.php");
                }
            }
                
        }

        include 'header.php';
?>

        
        <div class="d-flex flex-column align-items-center py-5">
            <h1>Please register your account!</h1>
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
                    <button class="btn btn-success" type="submit">Register</button>
                </div>
            </form>
            <a href="login.php">Login</a>
        </div>
    </body>
    </html>
    <?php
}else{
    header("Location: index.php");
    exit();
}