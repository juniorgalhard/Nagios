#!/usr/bin/php
<?php

  
$host=$argv[1];
$port=$argv[2];
$username=$argv[3];
$password=$argv[4];
$src=$argv[5];
$dst=$argv[6];
$repeat="10";

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

preg_match('/round-trip min\/avg\/max = (\d+)\/(\d+)\/(\d+) ms/', $data, $tr);

$packet_sucess=$match[1];

$min=$tr[1];
$avg=$tr[2];
$max=$tr[3];

$packet_loss=100-$packet_sucess;

fclose($stream);



switch ($packet_sucess) {
        case "$packet_sucess" ==  "100":
        print "OK - $packet_loss % packet loss  min=$min/avg=$avg/max=$max. | pl=$packet_loss;min=$min;avg=$avg;max=$max\n";
        exit(0);

        case "$packet_sucess" >= "90":
        print "WARNING - $packet_loss % packet loss  min=$min/avg=$avg/max=$max. | pl=$packet_loss;min=$min;avg=$avg;max=$max\n";
        exit(1);

        case "$packet_sucess" <= "90":
        print "CRITICAL - $packet_loss % packet loss  min=$min/avg=$avg/max=$max. | pl=$packet_loss;min=$min;avg=$avg;max=$max\n";
        exit(2);

        default:
        print "UNKNOWN - packet loss error.\n";
        exit(3);
}

?>

