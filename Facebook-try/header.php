<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <header class="container-fluid py-3">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h2>Aplicatie Foto</h2>
                    <?php if ($page_id != 'login' && $page_id !='register') {
                        echo '<a class="text-light" href="logout.php">Logout</a>';
                    } ?>
                </div>
            </div>
        </header>