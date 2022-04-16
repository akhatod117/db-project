<?php

function addUser($uid,$username)
{
	// db handler
	global $db;

	// write sql
	$query = "insert into UIDToUsername values(:uid, :username)";

	// execute the sql
	$statement = $db->prepare($query);

	$statement->bindValue(':uid', $uid);
	$statement->bindValue(':username', $username);
	
	$statement->execute();
	
	// release; free the connection to the server so other sql statements may be issued
	if(!$statement){
	   die("add query failed");
	}else{
	   $statement->closeCursor();
	}
}

function getAllUsers()
{
	global $db;
	$query = "select * from UIDToUsername";
	
	//1. prepare
	//2. bindValue & execute
	$statement = $db->prepare($query);
	$statement->execute();
	
	// fetchAll() returns an array of all rows in the result set
	if(!$statement){
	   die("retrieve all query failed");
	}else{
	   $results = $statement->fetchAll(PDO::FETCH_OBJ);   

	   $statement->closeCursor();

	   return $results;
	}
}

function getAllPosts()
{
	global $db;
	$query = "select * from Post";
	//$statement = $db->query($query);
	
	//1. prepare
	//2. bindValue & execute
	$statement = $db->prepare($query);
	$statement->execute();
	
	// fetchAll() returns an array of all rows in the result set
	if(!$statement){
	   die("retrieve all query failed");
	}else{
	   $results = $statement->fetchAll(PDO::FETCH_OBJ);   

	   $statement->closeCursor();

	   return $results;
	}
}

function getFriend_byName($name)
{
        global $db;
        $query = "select * from friends where name = :name";
	//$statement = $db->query($query);
	
	//1. prepare
	//2. bindValue & execute
	$statement = $db->prepare($query);
	$statement->bindValue(':name', $name);
	$statement->execute();
	
	// fetchAll() returns an array of all rows in the result set
	if(!$statement){
	   die("retrieve all query failed");
	}else{
	   $results = $statement->fetch();   

	   $statement->closeCursor();

	   return $results;
	}
}

function updateFriend($name, $major, $year)
{
   global $db;
   $query = "UPDATE friends SET major=:major, year=:year where name=:name";
   $statement = $db->prepare($query);
   $statement->bindValue(':major', $major);
   $statement->bindValue(':year', $year);
   $statement->bindValue(':name', $name);
   $statement->execute();
   if(!$statement){
	die("add query failed");
   }else{
	$statement->closeCursor();
   }
}

function deleteFriend($name)
{
   global $db;
   $query = "DELETE from friends where name=:name";
   $statement = $db->prepare($query);
   $statement->bindValue(':name', $name);
   $statement->execute();
   if(!$statement){
	die("add query failed");
   }else{
	$statement->closeCursor();
   }
}

?>