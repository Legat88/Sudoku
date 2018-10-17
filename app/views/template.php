<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../assets/css/normalize.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title><?=$title ?> | Судоку</title>
</head>
<body>
    <div class="wrapper">
        <div class="maincontent">
            <div class="container col col-md-8 col-xl-6">
                <h1 class="text-center mt-50">СУДОКУ</h1>
                <?php include 'app/views/'.$pageView; ?>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>