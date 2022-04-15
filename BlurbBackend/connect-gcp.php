<?php



 $host_name = '34.150.130.46' ;
 $database_name = 'blurb';
 $username = 'root' ;
 $password = 'AwzlmleicOParaq0';
 $dsn = "mysql:host=$host_name;dbname=$database_name";


try 
{
   $db = new PDO($dsn, $username, $password);
   
   // dispaly a message to let us know that we are connected to the database 
   //echo "<p>You are connected to the database --- dsn=$dsn, user=$username, pwd=$password </p>";
   //$query_test = $db->prepare("SELECT * FROM UIDToUsername");
   //$query_test->execute();
   // while ($result = $query_test->fetch(PDO::FETCH_ASSOC)) {
   //     echo $result['username']."<br/>";
   // }

   $method = $_SERVER['REQUEST_METHOD'];
   $request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

   switch($method){
        case 'GET':
            $sql = "SELECT * FROM UIDToUsername";
            break;
        
        case 'POST':
            $uid = $_POST['uid'];
            $username = $_POST['username'];
            $sql = "INSERT INTO UIDToUsername (uid, username) VALUES ($uid,$username))";


   }

   //try the sql statement
   $result = $db->prepare($sql);
   if(!$result){
    http_response_code(404);
    print_r($db->errorInfo());
    }else{
        $result -> execute();
    }

    if($method == "GET"){
        echo json_encode($result -> fetch(PDO::FETCH_OBJ));
    }elseif($method == "POST"){

        echo json_encode($result);
    }

   
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