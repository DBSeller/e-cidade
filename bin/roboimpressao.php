<?php
$sock = socket_create_listen(4444);
socket_getsockname($sock, $addr, $port);
print " Robo escutando em  $addr:$port\n";
//$fp = fopen("/tmp/teste.txt", 'w');
while($c = socket_accept($sock)) {

  /* do something useful */
  socket_getpeername($c, $raddr, $rport);
  print "Recebi dados de $raddr:$rport\n";
  $buffer =  socket_read($c,100000,PHP_BINARY_READ);   
  echo ( $buffer);

}
socket_close($sock);
?> 
