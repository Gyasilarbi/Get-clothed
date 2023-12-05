<?php
  session_start();
  $servername = "localhost"; //127.0.0.1
  $database = "getclothed";
  $username="root";
  $password="root";
  
  try{
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // echo "Connected successfully";

  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
  
?>