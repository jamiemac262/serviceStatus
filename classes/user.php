<?php

class User{
	
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
		echo "User test complete";
		
	}
	
	function doLogin($name, $pass){
		//echo $name;
		//echo $pass;
		$conn = self::connect();//connect to the database
		$sth = $conn->prepare("SELECT * FROM user WHERE username=:name AND password=:pass");
		$sth->bindParam(':name', $name, PDO::PARAM_STR);
		$sth->bindParam(':pass', $pass, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->setFetchMode(PDO::FETCH_ASSOC); 
		$users = $sth->fetchAll();
		//echo count($users);
		if(count($users) == 1){
			return true;
		}else{
			return false;
		}
	}
}


?>