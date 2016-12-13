<?php
class RSSHandler{
	
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
	
	function publish($title, $description){
		$date = new DateTime();
		$f_date = $date->format('Y-m-d H:i:s');
		
		$link = "http://www.makeitfortheweb.com/challenge";
		
		$conn= self::connect();
		$sth = $conn->prepare("INSERT INTO rss (title, description, date, link) VALUES (:title, :description, :date, :link)");
		$sth->bindParam(':title', $title, PDO::PARAM_STR);
		$sth->bindParam(':description', $description, PDO::PARAM_STR);
		$sth->bindParam(':date', $f_date, PDO::PARAM_STR);
		$sth->bindParam(':link', $link, PDO::PARAM_INT);
		$sth->execute();
		
	}
}
?>