<?php
ini_set('display_errors', 0); //Вывод ошибок

 //Подключаем Core классы
require_once 'app/core/Model.php';
require_once 'app/core/PageController.php';
require_once 'app/core/Router.php';
require_once 'app/core/View.php';

Router::start();

