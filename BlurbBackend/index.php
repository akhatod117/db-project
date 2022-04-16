<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require('connect-gcp.php');
require('db_calls.php');

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$data = json_decode(file_get_contents("php://input"),true);

$list_of_users = NULL;

switch($method){
    case 'GET':
        $list_of_users = getAllUsers();
        break;
        
    case 'POST':
        //check inputs
	    if(!isset($data['uid']) || !isset($data['username']))
	    {
		   return $json = array("success" => false, "Info" => "Invalid Inputs");
	    } 
	    //sanitise inputs
	    $uid = htmlspecialchars(strip_tags($data['uid']));
	    $username = htmlspecialchars(strip_tags($data['username']));
        addUser($uid, $username);
        break;
}

if($method == "GET"){
   echo json_encode($list_of_users);
}else if($method == "POST"){
   //echo json_encode($result);
}
?>