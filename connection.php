<?php 

try{
    $host = "localhost";
    $port = 3308;  //note: my xampp mysql port is on port 3308 instead of port 3306
    $dbname = "QuizWebdb";
    $username = "root";  
    $password = "";

    $connection = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

    echo "Connected Successfully!";

} catch (\Throwable $e) {
    
    echo "Connection failed: " . $e->getMessage(); 
}

?>