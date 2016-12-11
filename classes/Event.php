<?php

class Event{
	
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
		echo "Event test complete";
		
	}
	
	function getAll(){
		
		$conn = self::connect();//connect to the database
		$sth = $conn->prepare("SELECT * FROM event where resolved = 0");
		$sth->execute();
		$result = $sth->setFetchMode(PDO::FETCH_ASSOC); 
		$events = $sth->fetchAll();
		
		return $events;
	}
	
	function get(){
		
		$conn = self::connect();//connect to the database
		$sth = $conn->prepare("SELECT * FROM event where id = :id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->setFetchMode(PDO::FETCH_ASSOC); 
		$events = $sth->fetchAll();
		
		return $events;
	}
	
	function getResolved(){
		$conn = self::connect();//connect to the database
		$sth = $conn->prepare("SELECT * FROM event where resolved = 1");
		$sth->execute();
		$result = $sth->setFetchMode(PDO::FETCH_ASSOC); 
		$events = $sth->fetchAll();
		
		return events;
	}
	
	function create($title, $message, $status){
		
		$date = new DateTime();
		$f_date = $date->format('Y-m-d H:i:s');
		
		$conn = self::connect();
		$sth = $conn->prepare("INSERT INTO event (title, message, date, status, resolved) VALUES (:title, :message, :date, :status, 0)");
		$sth->bindParam(':title', $title, PDO::PARAM_STR);
		$sth->bindParam(':message', $message, PDO::PARAM_STR);
		$sth->bindParam(':date', $f_date, PDO::PARAM_STR);
		$sth->bindParam(':status', $status, PDO::PARAM_INT);
		$sth->execute();
		
	}
	
	function editEvent($id, $title, $message, $status){
		
		$conn = self::connect();
		$sth = $conn->prepare("update event SET title=:title, message=:message, status=:status WHERE id=:id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->bindParam(':title', $title, PDO::PARAM_STR);
		$sth->bindParam(':message', $message, PDO::PARAM_STR);
		$sth->bindParam(':status', $status, PDO::PARAM_INT);
		$sth->execute();	
		
	}
	
	function deleteEvent($id){
		$conn = self::connect();//connect to the database
		$sth = $conn->prepare("DELETE FROM event where id = :id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		
		
	}
	
	function updateStatus($id, $status){
		
		$conn = self::connect();
		$sth = $conn->prepare("update event SET status=:status WHERE id=:id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->bindParam(':status', $status, PDO::PARAM_INT);
		$sth->execute();	
	}
	
	function resolve($id){
		$conn = self::connect();
		$sth = $conn->prepare("update event SET resolved=1 WHERE id=:id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}
	
}

?>
