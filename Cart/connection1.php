<?php 
  
    $server="194.81.104.22"; 
    $user="team4"; 
    $pass="group4"; 
    $db="db_team4"; 
      
    // connect to mysql 
      
    mysql_connect($server, $user, $pass) or die("Sorry, can't connect to the mysql."); 
      
    // select the db 
      
    mysql_select_db($db) or die("Sorry, can't select the database."); 
  
?>