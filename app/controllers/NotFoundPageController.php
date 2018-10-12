<?php
/**
 * Created by PhpStorm.
 * User: legat
 * Date: 10.10.2018
 * Time: 10:37
 */

class NotFoundPageController extends PageController
{
 function index()
 {
     $this->view->generate('404.php', 'template.php', array(
         'title' => 'Страница не найдена'
     ));
 }
}