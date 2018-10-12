<?php
/**
 * Created by PhpStorm.
 * User: legat
 * Date: 11.10.2018
 * Time: 9:30
 */

class UploadFileController
{
    public function Upload() {
        $uploaddir =__DIR__ . '/../../upload/';
        $fullname = explode('.', basename($_FILES['file']['name']));
        $name = $fullname[0] . '_'.time();
        $fullname = $name. '.' . $fullname[1];
        $uploadfile = $uploaddir . $fullname;

//TODO: Убрать в конце отладочный текст
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            return $uploadfile;
        } else {
            echo "Возможная атака с помощью файловой загрузки!\n";
        }

        echo 'Некоторая отладочная информация:';
        print_r($_FILES);

    }

}