<?php

define('ROOT_PATH', dirname(__FILE__));
define('DB_PATH', ROOT_PATH . '/db.json');

function dd($variable) 
{
    echo '<pre>';
        die(var_dump($variable));
    echo '</pre>';
}