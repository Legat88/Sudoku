<?php
/**
 * Created by PhpStorm.
 * User: legat
 * Date: 10.10.2018
 * Time: 9:44
 */

class MainPageController extends PageController
{
    function index()
    {
        $this->view->generate('mainpage.php', 'template.php', array(
            'title' => 'Главная'
        ));
//        if (count($_FILES) > 0) {
//            $this->showTable();
//        }
    }

    function showTable()
    {
        include 'UploadFileController.php';
        $upload = new UploadFileController();
        $file=$upload->Upload();
        $model = new MainModel();
        $array = $model->putFileToArray($file);
        $initial = $array;
        $result = $model->analyzeArrays($array);
//        for ($i = 0; $i < count($result); $i++) {
//            for ($j = 0; $j < count($result); $j++) {
//                if ($result[$i][$j] == "*") {
//                    $array=$this->analyzeArrays($array);
//                }
//            }
//        }
        $json_array = array($initial, $result);
        $json = json_encode($json_array);
        echo $json;
    }

}