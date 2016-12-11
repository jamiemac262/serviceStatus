<?php


class Comment{
	//Connect to the database
	function connect(){
		$servername = "localhost";
		$username = "root";
		$password = "WY7tpAxJPeVapG5L";
		
		try {
			$conn = new PDO("mysql:host=$servername;dbname=iomart", $username);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn; 
		}
		catch(PDOException $e){
			echo "Connection failed: " . $e->getMessage();
		}
			
	}
	
	function test(){
		
		self::connect();
		echo "Comment test complete";
		
	}
	
	function getCommentsforEvent($id){
		$conn = self::connect();//connect to the database
		$sth = $conn->prepare("SELECT * FROM comment where eventId = :id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->setFetchMode(PDO::FETCH_ASSOC); 
		$comments = $sth->fetchAll();
		
		return $comments;

		
	}
	
	
	
}

?>