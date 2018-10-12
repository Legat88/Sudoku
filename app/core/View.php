<?php
/**
 * Created by PhpStorm.
 * User: legat
 * Date: 10.10.2018
 * Time: 9:36
 */

class View
{
    /**
     * @param $pageView - контентная область
     * @param $template - шаблон, использующийся на всех страницах
     * @param $data - массив данных
     */
    public function generate ($pageView, $template, $data){
        if(is_array($data)) {
            // преобразуем элементы массива в переменные
            extract($data);
        }
        include 'app/views/'.$template;
    }
}