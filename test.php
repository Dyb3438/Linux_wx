<?php
use Workerman\Worker;
require_once __DIR__ . '/Workerman/Autoloader.php';
date_default_timezone_set("Asia/Shanghai");

$http_worker = new Worker("websocket://0.0.0.0:2000");

$http_worker->count = 4;

$userid=array();

$http_worker->onConnect=function($connection){

};
$http_worker->onMessage = function($connection, $data)
{
    global $http_worker,$userid;
    foreach($userid as $key=>$value){
        if(!isset($http_worker->connections[$key])){
            unset($userid[$key]);
        }
    }
    if(!isset($userid[$connection->id])){
        $data = explode(',', $data);
        $userid[$connection->id]=array("id"=>$connection->id,"userid"=>$data[0],"name"=>$data[1]);
    } else {
        //此处为收到信息后的操作
        $data=array("name"=>$userid[$connection->id]['name'],"image"=>$data,"time"=>date("Y-m-d H:i:s"));
        $data=json_encode($data);
        foreach($userid as $key=>$value){
            $http_worker->connections[$key]->send($data);
        }


    }
};
Worker::runAll();