<?php
use Workerman\Worker;
require_once __DIR__ . '/Workerman/Autoloader.php';


$http_worker = new Worker("websocket://0.0.0.0:2000");

$http_worker->count = 4;

$userid=array();

$http_worker->onConnect=function($connection){
    global $userid;
    if(!in_array($connection->id,$userid)){
        array_push($userid,$connection->id);
    }
    print_r($userid);
};
$http_worker->onMessage = function($connection, $data)
{
    $connection->send('hello '.$data);
};
Worker::runAll();