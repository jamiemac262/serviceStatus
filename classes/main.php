<?php

include_once("User.php");
include_once("Event.php");
include_once("Comment.php");


$user = new User();
$event = new Event();
$comment = new Comment();


$fn = $_GET["fn"];


//Login functions
if($fn == "login"){
	
	echo $user->doLogin($_GET["name"], $_GET["pass"]);
	
}

?>