<?php
require_once 'app/models/MainModel.php';
require_once 'UploadFileController.php';

class DataController
{
    public static function getData()
    {
        $upload = new UploadFileController();
        $file = $upload->Upload();
        $model = new MainModel();
        $array = $model->putFileToArray($file);
        $initial = $array;
        $result = $model->analyzeArrays($array);
        $json_array = array($initial, $result);
        $json = json_encode($json_array);
        echo $json;
    }
}