<?php
  
    $server='194.81.104.22'; 
    $user='team4'; 
    $pass='group4'; 

    $conn = mysql_connect($server, $user, $pass) or die ('Error connecting to mysql');

    $dbname = 'test';
    mysql_select_db($dbname);
  
?>