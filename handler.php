<?php
ini_set('display_errors', 0); //Вывод ошибок

require_once 'app/core/Model.php';
require_once 'app/controllers/DataController.php';

DataController::getData();
