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
    }


}