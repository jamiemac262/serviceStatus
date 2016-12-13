<?php

    header("Content-Type: application/rss+xml; charset=ISO-8859-1"); 
    $rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $rssfeed .= '<rss version="2.0">';
    $rssfeed .= '<channel>';

		$servername = "localhost";
		$username = "makeitfo_challenge";
		$password = "7TIOI7B[wG(#";
		
		try {
			$conn = new PDO("mysql:host=$servername;dbname=makeitfo_challenge", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			echo "Connection failed: " . $e->getMessage();
		}
		
		$sth = $conn->prepare("SELECT * FROM rss ORDER BY date DESC");
		$sth->execute();
		$result = $sth->setFetchMode(PDO::FETCH_ASSOC); 
		$feed = $sth->fetchAll();
    foreach($feed as $item){
        $rssfeed .= '<item>';
        $rssfeed .= '<title>' . $item['title'] . '</title>';
        $rssfeed .= '<description>' . $item['description'] . '</description>';
        $rssfeed .= '<link>' . $item['link'] . '</link>';
        $rssfeed .= '<pubDate>' . $item['date'] . '</pubDate>';
        $rssfeed .= '</item>';
    }
    $rssfeed .= '</channel>';
    $rssfeed .= '</rss>';
    echo $rssfeed;
?>