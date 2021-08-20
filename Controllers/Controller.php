<?php
use Illuminate\Support;  // https://laravel.com/docs/5.8/collections - provides the collect methods & collections class
use LSS\Array2Xml;
require_once('classes/Exporter.php');
require_once('Services/GetPlayerStats.php');

class Controller {

    protected $args = [];

    public function __construct()
    {
        $this->args = collect($_REQUEST);
    }
}