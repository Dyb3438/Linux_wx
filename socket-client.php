<?php
 set_time_limit(0);

 $port = 1936;
 $ip = "127.0.0.1";

 $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket < 0) {
   echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
}

$result = socket_connect($socket, $ip, $port);
if ($result < 0) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
 }
$_GET['image']='123';
$send=$_GET['image'];
$out = '';

if(!socket_write($socket, $send, strlen($send))) {
   echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
 }

 while($out = socket_read($socket, 8192)) {
     echo "接受的内容为:",$out;
 }

socket_close($socket);
?>