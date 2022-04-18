<?php
header("Access-Control-Allow-Origin: http://localhost:3000");

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

function createBlurb($description, $uid, $numberOfLikes, $date, $thread){
	// db handler
	global $db;
	echo "got to createBLurlb";
	$nRows = (int)($db->query('select count(*) from Post')->fetchColumn());
	$nRows ++;


	// write sql
	$query = "insert into Post values(:uid, :pid, :numberOfLikes, :description, :date)";

	// execute the sql
	$statement = $db->prepare($query);

	$statement->bindValue(':pid', $nRows);
	$statement->bindValue(':uid', $uid);
	$statement->bindValue(':numberOfLikes', $numberOfLikes);
	$statement->bindValue(':description', $description);
	$statement->bindValue(':date', $date);

	
	$statement->execute();
	
	// release; free the connection to the server so other sql statements may be issued
	if(!$statement){
	  //return echo "failed1";
	  die("add query failed");
	}else{
	  //return echo "here in the else";
	  $statement->closeCursor();
	}

	$query2 = "insert into Thread(name) SELECT :threadName WHERE NOT EXISTS (SELECT 1 FROM Thread WHERE name =:threadName) ";

	// execute the sql
	$statement2 = $db->prepare($query2);

	$statement2->bindValue(':threadName', $thread);

	$statement2->execute();
	
	// release; free the connection to the server so other sql statements may be issued
	if(!$statement2){
	  die("add query failed");
	}else{
	  $statement2->closeCursor();
	}
	
	/*$query4 = "insert into Thread values(:threadName)";

	// execute the sql
	$statement4 = $db->prepare($query4);

	$statement4->bindValue(':threadName', $thread);

	$statement4->execute();
	
	// release; free the connection to the server so other sql statements may be issued
	if(!$statement4){
	  die("add query failed");
	}else{
	  $statement4->closeCursor();
	}*/

	$query3 = "insert into Associated values(:thread, :uid, :pid)";

	// execute the sql
	$statement3 = $db->prepare($query3);
	echo "thread" . $thread;
	$statement3->bindValue(':pid', $nRows);
	$statement3->bindValue(':uid', $uid);
	$statement3->bindValue(':thread', $thread);

	
	$statement3->execute();
	
	// release; free the connection to the server so other sql statement3s may be issued
	if(!$statement3){
	  //echo "failed1";
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
	  /*if($auth == false){
		$exists = 'false';
		return $exists;
	  }else{
		return $exists;;
	  }*/
	  return $auth;
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

function getUserComments($uid)
{
        global $db;
        $query = "select * from Comment where uid = :uid";
		//$statement = $db->query($query);
	
	//1. prepare
	//2. bindValue & execute
	$statement = $db->prepare($query);
	$statement->bindValue(':uid', $uid);
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

function getUserInfo($uid)
{
        global $db;
        $query = "select * from UIDToUsername NATURAL JOIN UsernameToTotalLikes NATURAL JOIN LikesToTier where uid = :uid";
	//$statement = $db->query($query);
	
	//1. prepare
	//2. bindValue & execute
	$statement = $db->prepare($query);
	$statement->bindValue(':uid', $uid);
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
	$query = "select * from Post NATURAL JOIN UIDToUsername ORDER BY date DESC";
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

function getUserPosts($uid)
{
	global $db;
	$query = "select * from Post NATURAL JOIN UIDToUsername WHERE uid = :uid ORDER BY date DESC";
	//$statement = $db->query($query);
	
	//1. prepare
	//2. bindValue & execute
	$statement = $db->prepare($query);
	$statement->bindValue(':uid', $uid);
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


function updatePostLikes($uid, $pid, $likeUid)
{
	global $db;
	$numberOfLikes = 0;
	$query0 = "select numberOfLikes from Post where uid = :uid AND pid = :pid";
	//$statement = $db->query0($query0);

	//1. prepare
	//2. bindValue & execute
	$statement0 = $db->prepare($query0);
	$statement0->bindValue(':uid', $uid);
	$statement0->bindValue(':pid', $pid);
	$statement0->execute();

	// fetchAll() returns an array of all rows in the result set
	if(!$statement0){
		die("retrieve all query failed");
	}else{
		//$results = $statement0->fetch(PDO::FETCH_OBJ);  
		$numberOfLikes = (int)$statement0->fetchColumn();

		$statement0->closeCursor();

	//return $results;
	}


   
   $newNumberOfLikes = $numberOfLikes + 1;
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

   /*$query2 = "DELETE FROM LikesToThreshold WHERE numberOfLikes=:numberOfLikes";
   $statement2 = $db->prepare($query2);
   $statement2->bindValue(':numberOfLikes', $numberOfLikes);
   $statement2->execute();
   if(!$statement2){
	die("add query failed");
   }else{
	$statement2->closeCursor();
   }*/
   
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

   //user likes
   $totalUserLikes = 0;
   $username = '';
   $query4 = "SELECT * FROM UsernameToTotalLikes NATURAL JOIN UIDToUsername where uid=:uid";
   $statement4 = $db->prepare($query4);
   $statement4->bindValue(':uid', $likeUid);
   $statement4->execute();
   if(!$statement4){
	die("add query failed");
   }else{
	$results = ($statement4->fetch(PDO::FETCH_NUM));
	$username = $results[0];
	$totalUserLikes = (int)$results[1];
	echo "totalUserOfLikes: " . $totalUserLikes;
	$statement4->closeCursor();
   }
   
   $newUserOfLikes = $totalUserLikes + 1;
   $query5 = "UPDATE UsernameToTotalLikes SET totalUserLikes=:newUserOfLikes where username=:username";
   $statement5 = $db->prepare($query5);
   $statement5->bindValue(':username', $username);
   $statement5->bindValue(':newUserOfLikes', $newUserOfLikes);
   $statement5->execute();
   if(!$statement5){
	die("add query failed");
   }else{
	$statement5->closeCursor();
   }

   $type2 = NULL;

   
   $query6 =  "select * from UserTierThreshold";
   $statement6 = $db->prepare($query6);
   $statement6->execute();
   if(!$statement6){
	die("add query failed");
   }else{
		$results = $statement6->fetchAll(PDO::FETCH_ASSOC);   
		$type2 = 'Normal';
		foreach($results as $row){
			$threshold = $row['tierThreshold'];
			if($newUserOfLikes >= $threshold){
				$type2 = $row['tier'];
			}
		}
	   	$statement6->closeCursor();
   }


   $query7 = "INSERT INTO LikesToTier VALUES (:totalUserLikes,:tier)";
   $statement7 = $db->prepare($query7);
   $statement7->bindValue(':tier', $type2);
   $statement7->bindValue(':totalUserLikes', $newUserOfLikes);
   $statement7->execute();
   if(!$statement7){
	die("add query failed");
   }else{
	$statement7->closeCursor();
   }
}

function getFilterByThread($threadName){
	global $db;
	$query = "select * from Thread NATURAL JOIN Associated NATURAL JOIN Post WHERE name =:threadName AND threadName =:threadName";
	//$statement = $db->query($query);
	//1. prepare
	//2. bindValue & execute
	$statement = $db->prepare($query);
	$statement->bindValue(':threadName', $threadName);
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

function getRelatedTopics($threadName){
	global $db;
	$query = "select relatedTopics from RelatedTopics WHERE threadName =:threadName;";
	//$statement = $db->query($query);
	//1. prepare
	//2. bindValue & execute
	$statement = $db->prepare($query);
	$statement->bindValue(':threadName', $threadName);
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


function getUsers(){
	global $db;
	$x = NULL;
	$query = "CALL countUsers(@:x)";
	$query2 = "SELECT @:x as 'param1';$$";

	$statement = $db->prepare($query);
	$statement->bindValue(':x', $x);
	$statement->execute();
	if(!$statement){
		die("statement did not work");
	}else{
		$statement->closeCursor();
	}
		
	$statement1= $db->prepare($query2);
	$statement1->bindValue(':x', $x);
	$statement1->execute();
	if(!$statement1){
		die("advanced query failed");
	 }else{
		$results = $statement1->fetch(PDO::FETCH_OBJ);   
 
		$statement1->closeCursor();
 
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
function deletePost($uid, $pid)
{
        global $db;
        $query = "delete from Post where uid = :uid and pid =:pid";
		//$statement = $db->query($query);
	
	//1. prepare
	//2. bindValue & execute
	$statement = $db->prepare($query);
	$statement->bindValue(':uid', $uid);
	$statement->bindValue(':pid', $pid);
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


?>