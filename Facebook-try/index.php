<?php
session_start();
if( !empty($_SESSION["logged_in_user"])){
    require "functions.php";
    connectDB();

    $page_id = "home";

    if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES)){ 
        try {
    
            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (
                !isset($_FILES['img']['error']) ||
                is_array($_FILES['img']['error'])
            ) {
                throw new RuntimeException('Invalid parameters.');
            }
        
            // Check $_FILES['img']['error'] value.
            switch ($_FILES['img']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }
        
            // You should also check filesize here. 
            if ($_FILES['img']['size'] > 1000000) {
                throw new RuntimeException('Exceeded filesize limit.');
            }
        
            // DO NOT TRUST $_FILES['img']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                $finfo->file($_FILES['img']['tmp_name']),
                array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                ),
                true
            )) {
                throw new RuntimeException('Invalid file format.');
            }
        
            // You should name it uniquely.
            // DO NOT USE $_FILES['img']['name'] WITHOUT ANY VALIDATION !!
            // On this example, obtain safe unique name from its binary data.
            $filename = sprintf('img/%s.%s',
                sha1_file($_FILES['img']['tmp_name']),
                $ext
            );
            if (!move_uploaded_file(
                $_FILES['img']['tmp_name'],
                $filename
            )) {
                throw new RuntimeException('Failed to move uploaded file.');
            }
        
            $msg = 'File is uploaded successfully.';
            addphoto($filename,getid($_SESSION["logged_in_user"]));
        
        } catch (RuntimeException $e) {
        
            $msg = $e->getMessage();
        
        }     
    }

    $images=getimages();

    include 'header.php';
    
    ?>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="my-3">Welcome, <?php echo $_SESSION["logged_in_user"]; ?>!</h1>
                    <hr>
                    <a class="btn btn-success" href="upload.php">Upload Foto</a>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div id="image-list pt-4">
                        <?php
                        for($i=0;$i<count($images);$i++){
                            $image=$images[$i];
                            ?>
                            <div class="image-group mb-5">
                                <div class="image mb-2"><img class="img-fluid" src="<?php echo $image["filename"]; ?>" ></div>
                                <?php if(checklike($image["id"],getid($_SESSION["logged_in_user"]))){
                                    echo '<a class="unlike" href="#" data-img_id="'. $image["id"] .'"><span>Unlike</span><span> (' . $image["nr_likes"] . ')</span></a>';
                                } else {
                                    echo '<a class="like" href="#" data-img_id="'. $image["id"] .'"><span>Like</span><span> (' . $image["nr_likes"] . ')</span></a>';
                                }
            
                                if($image["user"]==getid($_SESSION["logged_in_user"])){
                                    ?>
                                    <a class="delete_but text-danger float-right" href="#">Delete</a>
                                    <form action="delete_img.php" method="post"  class="d-none del_form">
                                        <span>Are you sure? </span>
                                        <input class="btn btn-danger" type="submit" value="Delete" name="delete">
                                        <a class="btn btn-secondary cancel_btn" href="#">Cancel</a>
                                        <input type="hidden" name="imgid" value="<?php echo $image["id"];?>">
                                    </form>
                                    <?php
                                }
                                ?>
                                <div class="comments">
                                    <ul>
                                    <?php
                                    $comments=getcomments( $image["id"]);
                                    for($j=0;$j<count($comments);$j++){
                                        ?>
                                        <li>
                                            <h5><?php echo getuserfromid($comments[$j]["user_id"]);?></h5>
                                            <p><?php echo nl2br($comments[$j]["content"]) ?></p>
                                        </li>
                                        <?php
                                    }?>
                                    </ul>
                                    <div>
                                        <form action="" class="form-inline d-flex">
                                            <div class="form-group flex-fill">
                                                <textarea class="w-100 mr-4" name="comment_field" rows="3" placeholder="Write your comment here!" maxlength="500" required></textarea>
                                            </div>
                                            <input type="hidden" name="img_id" value="<?php echo $image["id"]; ?>">
                                            <div class="form-group">
                                                <button class="btn btn-success" type="submit">Add Comment</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                       <?php }//end img for ?>
                
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {

                $(document).on('click', '.like' , function(e){
                    e.preventDefault();
                    var $this = $(this);
                    $.ajax({
                        type: "POST",
                        url: "process_likes.php",
                        data: {
                            'img_id': $this.data('img_id'),
                            'like_unlike': 'like'
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.true_false){
                                $this.removeClass('like').addClass('unlike');
                                $this.children('span:first-child').text('Unlike');
                                $this.children('span:last-child').text(' (' + response.newlikes + ')' );
                            }
                        }
                    });
                });

                $(document).on('click', '.unlike' , function(e){
                    e.preventDefault();
                    var $this = $(this);
                    $.ajax({
                        type: "POST",
                        url: "process_likes.php",
                        data: {
                            'img_id': $this.data('img_id'),
                            'like_unlike': 'unlike'
                        },
                        dataType: "json",
                        success: function (response) {
                            if (response.true_false){
                                $this.removeClass('unlike').addClass('like');
                                $this.children('span:first-child').text('Like');
                                $this.children('span:last-child').text(' (' + response.newlikes + ')' );
                            }
                        }
                    });
                });

                $('.comments form').submit(function (e) {
                    e.preventDefault();
                    var commentform = $(this);

                    $.ajax({
                        type: "post",
                        url: "process_comments.php",
                        data: commentform.serialize(),
                        dataType: "json",
                        success: function (response) {
                            if (response.true_false){
                                commentform.parent().siblings("ul").append('<li><h5>' + response.author + '</h5><p>' + response.content + '</p></li>')
                                commentform.find('textarea').val('');
                            }
                        }
                    });
                    
                });

                $('.delete_but').on('click', function(e){
                    e.preventDefault();
                    $(this).siblings('.del_form').removeClass('d-none');
                });

                $('.cancel_btn').on('click', function(e){
                    e.preventDefault();
                    $(this).parent().addClass('d-none');
                });

            });
        </script>
    </body>
    </html>
    <?php
} else{
    header("Location: login.php");
}

