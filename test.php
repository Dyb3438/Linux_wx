<?php
use Workerman\Worker;
require_once __DIR__ . '/Workerman/Autoloader.php';
date_default_timezone_set("Asia/Shanghai");

$http_worker = new Worker("websocket://0.0.0.0:2000");

$http_worker->count = 4;



$http_worker->onConnect=function($connection)
{
    $jiekou=array();
    $userid=array();
};
$http_worker->onMessage = function($connection, $data)
{
    global $http_worker,$jiekou,$userid;
    if(is_array($jiekou)) {
        foreach ($jiekou as $key => $value) {
            if (!isset($http_worker->connections[$key])) {
                unset($jiekou[$key]);
            }
        }
    }
    if(!isset($jiekou[$connection->id])){
        $data =json_decode($data);
        $jiekou[$connection->id]=array("id"=>$connection->id,"name"=>$data[0],"tel"=>$data[1],"email"=>$data[2]);
        if(is_array($userid)) {
            $a=0;
            foreach ($userid as $key => $value) {
                if ($value['tel'] == $data[1]) {
                    $a = 1;
                }
            }
            if ($a == 1) {
                $http_worker->connections[$connection->id]->send("已预约！");
            }else{
                $userid[$key+1]=array(
                    "name"=>$data[0],
                    "tel"=>$data[1],
                    "email"=>$data[2]
                );
                $http_worker->connections[$connection->id]->send("预约成功！");
            }
        }else{
            $userid[0]=array(
                "name"=>$data[0],
                "tel"=>$data[1],
                "email"=>$data[2]
            );
            $http_worker->connections[$connection->id]->send("预约成功！");
        }
        print_r($jiekou);
    }
};
Worker::runAll();
