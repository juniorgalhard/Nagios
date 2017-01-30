#!/usr/bin/php
<?php

  
$host=$argv[1];
$port=$argv[2];
$username=$argv[3];
$password=$argv[4];
$src=$argv[5];
$dst=$argv[6];
$repeat="1000";

$ssh = ssh2_connect($host, $port);
  
if(ssh2_auth_password ($ssh, $username, $password)){
    
}

$cmd = 'ping ' . $dst . ' source ' . $src . ' repeat ' . $repeat;

$stream = ssh2_exec($ssh, $cmd);
  
stream_set_blocking ($stream, true);  
  
$data = '';

while($buffer = fread($stream, 4096)){

	$data .= $buffer;
}

preg_match('/Success rate is (\d+)/', $data, $match);

$packet_sucess=$match[1];

$packet_loss=100-$packet_sucess;

fclose($stream);



switch ($packet_sucess) {
        case "$packet_sucess" ==  "100":
        print "OK  - $packet_loss % packet loss. | pl=$packet_loss\n";
        exit(0);

        case "$packet_sucess" < "100":
        print "WARNING - $packet_loss % packet loss. | pl=$packet_loss\n";
        exit(1);

        default:
        print "UNKNOWN - erro test packet loss.\n";
        exit(3);
}

?>

