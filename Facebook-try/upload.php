<?php
session_start();
if( !empty($_SESSION["logged_in_user"])){

    $page_id = "upload";

    include 'header.php';
    
    ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 py-5 d-flex justify-content-center">
                    <form action="index.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="file" name="img" id="img" required>
                        </div>
                        <div class="form-group mt-5">
                            <button type="submit" class="btn btn-success">Upload!</button>
                            <a class="btn btn-secondary" href="index.php">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
    </body>
    </html>
    <?php
} else{
    header("Location: login.php");
}
