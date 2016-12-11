<?php

include_once('Event.php');
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
	
	function create($id, $message, $status){
		
		$date = new DateTime();
		$f_date = $date->format('Y-m-d H:i:s');
		
		$conn = self::connect();
		$sth = $conn->prepare("INSERT INTO comment (eventID, message, date) VALUES (:eventID, :message, :date)");
		$sth->bindParam(':message', $message, PDO::PARAM_STR);
		$sth->bindParam(':eventID', $id, PDO::PARAM_INT);
		$sth->bindParam(':date', $f_date, PDO::PARAM_STR);
		$sth->execute();
		
		//update the status of the event
		$event = new Event();
		$event->updateStatus($id, $status);
	}
	
	function editComment($id, $message, $status){
		
		$conn = self::connect();
		$sth = $conn->prepare("update comment SET message=:message WHERE id=:id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->bindParam(':message', $message, PDO::PARAM_STR);
		$sth->execute();	
		
		//update the status of the event
		//gt the eventID
		$sth= $conn->prepare("SELECT eventID FROM comment WHERE id= :id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$eId = $sth->fetchAll()[0]['eventID'];
		print_r($eId);
		$event = new Event();
		$event->updateStatus($eId, $status);
	}
	
	function deleteComment($id){
		$conn = self::connect();//connect to the database
		$sth = $conn->prepare("DELETE FROM comment where id = :id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		
		
	}
	
	
}

?>