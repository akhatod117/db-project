<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require('connect-gcp.php');
require('db_calls.php');

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$data = json_decode(file_get_contents("php://input"),true);

$list_of_users = NULL;
$list_of_posts = NULL;
$list_of_topics = NULL;

switch($method){
    case 'GET':
        if($uri[3] == 'user'){
            if($uri[4] == null){
                $list_of_users = getAllUsers();
                echo json_encode($list_of_users);
            }else{
                echo json_encode(getUserByUID($uri[4]));
            }
        }else if($uri[3] == 'post'){
            $list_of_posts = getAllPosts();
            echo json_encode($list_of_posts);
        }else if($uri[3] == 'postByLikes'){
            $list_of_posts = getPostsByLikes();
            echo json_encode($list_of_posts);
        }
        else if($uri[3] == 'getUsers'){
            echo json_encode(getUsers());
        }else if($uri[3] == 'filterByThread'){
            $list_of_posts = getFilterByThread($uri[4]);
            echo json_encode($list_of_posts);
        }else if($uri[3] == 'getRelatedTopics'){
            $list_of_topics = getRelatedTopics($uri[4]);
            echo json_encode($list_of_topics);
        }else if($uri[3] == 'getUserInfo'){
            if($uri[4] == null){
                
            }else{
                echo json_encode(getUserInfo($uri[4]));
            }
        }else if($uri[3] == 'getUserPosts'){
            if($uri[4] == null){
                
            }else{
                echo json_encode(getUserPosts($uri[4]));
            }
        }
        else if($uri[3] == 'getUserComments'){
            if($uri[4] == null){
                
            }else{
                echo json_encode(getUserComments($uri[4]));
            }
        }
        
        break;
        
    case 'POST':
        //check inputs
        if($uri[3] == 'createUser'){
            if(!isset($data['email']) || !isset($data['password']))
	        {
		    return $json = array("success" => false, "Info" => "Invalid Inputs");
	        }
            //sanitise inputs
	        $email = htmlspecialchars(strip_tags($data['email']));
	        $password = htmlspecialchars(strip_tags($data['password']));
            addUser($email, $password);
        }else if($uri[3] == 'authenticateUser'){
            if(!isset($data['email']) || !isset($data['password']))
	        {
		    return $json = array("success" => false, "Info" => "Invalid Inputs");
	        }
            //sanitise inputs
	        $email = htmlspecialchars(strip_tags($data['email']));
	        $password = htmlspecialchars(strip_tags($data['password']));
            $userChecking = array("check" => checkUser($email, $password));
            echo json_encode($userChecking);
        }else if($uri[3] == 'incrementLikes'){
            if(!isset($data['uid']) || !isset($data['pid']) || !isset($data['likeUid']))
	        {
		        return $json = array("success" => false, "Info" => "Invalid Inputs");
	        }
            $uid = htmlspecialchars(strip_tags($data['uid']));
	        $pid = htmlspecialchars(strip_tags($data['pid']));
            $likeUid = htmlspecialchars(strip_tags($data['likeUid']));
            updatePostLikes($uid, $pid, $likeUid);
        
        }else if($uri[3] == 'createBlurb'){
            
            $description = htmlspecialchars(strip_tags($data['description']));
            $uid = htmlspecialchars(strip_tags($data['uid']));
            $numberOfLikes = htmlspecialchars(strip_tags($data['numberOfLikes']));
            $date = htmlspecialchars(strip_tags($data['date']));
            $thread = htmlspecialchars(strip_tags($data['thread']));
            createBlurb($description, $uid, $numberOfLikes, $date, $thread);
        }
        else if($uri[3] == 'getComments'){
            
            $uid = htmlspecialchars(strip_tags($data['uid']));
            $pid = htmlspecialchars(strip_tags($data['pid']));
            getComments($uid, $pid);
        }
        else if($uri[3] == 'deletePost'){
            
            $uid = htmlspecialchars(strip_tags($data['uid']));
            $pid = htmlspecialchars(strip_tags($data['pid']));
            deletePost($uid, $pid);
        }
        break;
}
?>