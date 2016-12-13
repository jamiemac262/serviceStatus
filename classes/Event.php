<?php
include_once("Comment.php");
include_once("RSSHandler.php");
class Event{
	
	function connect(){
		$servername = "localhost";
		$username = "makeitfo_challenge";
		$password = "7TIOI7B[wG(#";
		
		try {
			$conn = new PDO("mysql:host=$servername;dbname=makeitfo_challenge", $username, $password);
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
	
	function get($id){
		$event = Array();
		$conn = self::connect();//connect to the database
		
		//get the event for $id
		$sth = $conn->prepare("SELECT * FROM event where id = :id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->setFetchMode(PDO::FETCH_ASSOC); 
		$events = $sth->fetchAll();
		$event['event'] = $events[0];
		
		//get the comments for the event
		$comment = new Comment();
		$event['comments'] = $comment->getCommentsForEvent($id);
		
		return $event;
	}
	
	function getResolved(){
		$conn = self::connect();//connect to the database
		$sth = $conn->prepare("SELECT * FROM event where resolved = 1");
		$sth->execute();
		$result = $sth->setFetchMode(PDO::FETCH_ASSOC); 
		$events = $sth->fetchAll();
		
		return $events;
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
		$rss = new RSSHandler();
		$rss->publish($title, "A new event has been published by support");
		
	}
	
	function editEvent($id, $title, $message, $status){
		
		$date = new DateTime();
		$f_date = $date->format('Y-m-d H:i:s');
		
		$conn = self::connect();
		$sth = $conn->prepare("update event SET title=:title, message=:message, status=:status, date=:date WHERE id=:id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->bindParam(':title', $title, PDO::PARAM_STR);
		$sth->bindParam(':message', $message, PDO::PARAM_STR);
		$sth->bindParam(':status', $status, PDO::PARAM_INT);
		$sth->bindParam(':date', $f_date, PDO::PARAM_STR);
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
		
		$title = self::getTitle($id);
		$rss = new RSSHandler();
		$rss->publish($title, "Event's status has been changed to " . $status);
		
		
	}
	
	function resolve($id){
		
		$date = new DateTime();
		$f_date = $date->format('Y-m-d H:i:s');
		$conn = self::connect();
		
		$sth = $conn->prepare("UPDATE event SET resolved=1, resolveDate=:resolveDate WHERE id=:id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->bindParam(':resolveDate', $f_date, PDO::PARAM_STR);
		$sth->execute();
		self::updateStatus($id, 4);
		$title = self::getTitle($id);
		$rss = new RSSHandler();
		$rss->publish($title, "Event has been marked as resolved");
	}
	
	function getTitle($id){
		$conn = self::connect();
		$sth = $conn->prepare("SELECT title FROM event WHERE id=:id");
		$sth->bindParam(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->setFetchMode(PDO::FETCH_ASSOC); 
		$title = $sth->fetchAll();
		return $title[0]['title'];
		
	}
	
}

?>