<?php

function addUser($email,$password)
{
	// db handler
	global $db;

	$nRows = (int)($db->query('select count(*) from UIDToUsername')->fetchColumn());
	$nRows ++;

	$uid = hash('ripemd128', $email);
	$hashpass = hash('ripemd160', $password);

	// write sql
	$query = "insert into UserInfo values(:email, :password, :uid)";

	// execute the sql
	$statement = $db->prepare($query);

	$statement->bindValue(':email', $email);
	$statement->bindValue(':password', $hashpass);
	$statement->bindValue(':uid', $uid);
	
	$statement->execute();
	
	// release; free the connection to the server so other sql statements may be issued
	if(!$statement){
	  die("add query failed");
	}else{
	  $statement->closeCursor();
	}

	// write sql
	$query2 = "insert into UIDToUsername values(:uid, :username)";

	$uName = "user";
	$username = ($uName . $nRows); 

	// execute the sql
	$statement2 = $db->prepare($query2);

	$statement2->bindValue(':uid', $uid);
	$statement2->bindValue(':username', $username);

	$statement2->execute();
	
	// release; free the connection to the server so other sql statements may be issued
	if(!$statement2){
	  die("add query failed");
	}else{
	  $statement2->closeCursor();
	}

	// write sql
	$query3 = "insert into UsernameToTotalLikes values(:username, :totalUserLikes)";

	// execute the sql
	$statement3 = $db->prepare($query3);

	$count = 0;

	$statement3->bindValue(':username', $username);
	$statement3->bindValue(':totalUserLikes', $count);

	$statement3->execute();
	
	// release; free the connection to the server so other sql statements may be issued
	if(!$statement3){
	  die("add query failed");
	}else{
	  $statement3->closeCursor();
	}
}

function checkUser($email,$password)
{
	// db handler
	global $db;

	// write sql
	$query = "select * FROM UserInfo where email = :email AND password =:password";

	// execute the sql
	$statement = $db->prepare($query);

	$hashpass = hash('ripemd160', $password);

	$statement->bindValue(':email', $email);
	$statement->bindValue(':password', $hashpass);
	
	$statement->execute();

	$exists = 'true';
	
	// release; free the connection to the server so other sql statements may be issued
	if(!$statement){
      die("add query failed");
	}else{
      $auth = $statement->fetch(PDO::FETCH_OBJ);
	  $statement->closeCursor();
	  if($auth == false){
		$exists = 'false';
		return $exists;
	  }else{
		return $exists;;
	  }
	}
}

function getUserByUID($usr)
{
        global $db;
        $query = "select username from UIDToUsername where uid = :uid";
	//$statement = $db->query($query);
	
	//1. prepare
	//2. bindValue & execute
	$statement = $db->prepare($query);
	$statement->bindValue(':uid', $usr);
	$statement->execute();
	
	// fetchAll() returns an array of all rows in the result set
	if(!$statement){
	   die("retrieve all query failed");
	}else{
	   $results = $statement->fetch(PDO::FETCH_OBJ);   

	   $statement->closeCursor();

	   return $results;
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
	$query = "select * from Post NATURAL JOIN UIDToUsername";
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

function getPostsByLikes()
{
	global $db;
	$query = "select * from Post NATURAL JOIN UIDToUsername ORDER BY numberOfLikes DESC";
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

function updatePostLikes($uid, $pid, $numberOfLikes)
{
   global $db;
   $newNumberOfLikes = $numberOfLikes + 1;
   echo $newNumberOfLikes;
   $query = "UPDATE Post SET numberOfLikes=:numberOfLikes where uid=:uid AND pid =:pid";
   $statement = $db->prepare($query);
   $statement->bindValue(':uid', $uid);
   $statement->bindValue(':pid', $pid);
   $statement->bindValue(':numberOfLikes',$newNumberOfLikes);
   $statement->execute();
   if(!$statement){
	die("add query failed");
   }else{
	$statement->closeCursor();
   }


   $query1 =  "select * from PostPopularityThreshold";
   $statement1 = $db->prepare($query1);
   $statement1->execute();
   if(!$statement1){
	die("add query failed");
   }else{
		$results = $statement1->fetchAll(PDO::FETCH_ASSOC);   
		$type = 'Normal';
		foreach($results as $row){
			$threshold = $row['likeThreshold'];
			if($newNumberOfLikes >= $threshold){
				$type = $row['popularityType'];
			}
		}
	   	$statement1->closeCursor();
   }

   $query2 = "DELETE FROM LikesToThreshold WHERE numberOfLikes=:numberOfLikes";
   $statement2 = $db->prepare($query2);
   $statement2->bindValue(':numberOfLikes', $numberOfLikes);
   $statement2->execute();
   if(!$statement2){
	die("add query failed");
   }else{
	$statement2->closeCursor();
   }
   
   $query3 = "INSERT INTO LikesToThreshold VALUES (:newNumberOfLikes,:popularityType)";
   $statement3 = $db->prepare($query3);
   $statement3->bindValue(':popularityType', $type);
   $statement3->bindValue(':newNumberOfLikes', $newNumberOfLikes);
   $statement3->execute();
   if(!$statement3){
	die("add query failed");
   }else{
	$statement3->closeCursor();
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