<?php
 header("Access-Control-Allow-Origin: http://localhost:3000");
 $host_name = '34.150.130.46' ;
 $database_name = 'blurb';
 $username = 'root' ;
 $password = 'AwzlmleicOParaq0';
 $dsn = "mysql:host=$host_name;dbname=$database_name";


try 
{
   $db = new PDO($dsn, $username, $password);
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)     // handle a PDO exception (errors thrown by the PDO library)
{
   // Call a method from any object, use the object's name followed by -> and then method's name
   // All exception objects provide a getMessage() method that returns the error message 
   $error_message = $e->getMessage();        
   echo "<p>An error occurred while connecting to the database: $error_message </p>";
}
catch (Exception $e)       // handle any type of exception
{
   $error_message = $e->getMessage();
   echo "<p>Error message: $error_message </p>";
}






?>