<?php
 $host_name = '34.150.130.46' ;
 $database_name = 'blurb';
 $username = 'root' ;
 $password = 'AwzlmleicOParaq0';
 $dsn = "mysql:host=$host_name;dbname=$database_name";


try 
{
   $db = new PDO($dsn, $username, $password);
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $method = $_SERVER['REQUEST_METHOD'];
//    $request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

//    $data = json_decode(file_get_contents("php://input"),true);

//    $uid="";
//    $username="";

//    switch($method){
//         case 'GET':
//             $sql = "SELECT * FROM UIDToUsername";
//             break;
        
//         case 'POST':
//             //check inputs
// 		    if(!isset($data['uid']) || !isset($data['username']))
// 		    {
// 			   return $json = array("success" => false, "Info" => "Invalid Inputs");
// 		    } 
// 		    //sanitise inputs
// 		    $uid = htmlspecialchars(strip_tags($data['uid']));
// 		    $username = htmlspecialchars(strip_tags($data['username']));
//             $sql = "INSERT INTO UIDToUsername (uid, username) VALUES ('$uid','$username'))";
//             break;
//    }

//    //try the sql statement
//    $result = $db->prepare($sql);
   
//    if(!$result){
//     http_response_code(404);
//     print_r($db->errorInfo());
//     }else{
//         $result -> execute();
//     }

//     if($method == "GET"){
//         echo json_encode($result -> fetchAll(PDO::FETCH_OBJ));
//     }elseif($method == "POST"){
//         echo json_encode($result);
//     }

   
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