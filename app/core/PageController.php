<?php
/**
 * Created by PhpStorm.
 * User: legat
 * Date: 10.10.2018
 * Time: 9:36
 */

abstract class PageController
{
    public $view;
    public $model;
    function __construct()
    {
        $this->view= new View();
    }
    abstract function index();
}