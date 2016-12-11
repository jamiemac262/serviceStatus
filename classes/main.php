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
//Event functions
elseif($fn == "allEvent"){
	echo toJson($event->getAll());
}elseif($fn == "getEvent"){
	echo toJson($event->get($_GET['id']));
}elseif($fn == "getResolvedEvent"){
	echo toJson($event->getResolved($_GET[$id]));
}elseif($fn == "newEvent"){
	echo toJson($event->create($_GET["title"], $_GET["message"], $_GET["status"]));
}elseif($fn == "updateEvent"){
	echo toJson($event->updateEvent($_GET["title"], $_GET["message"], $_GET["status"]));
}elseif($fn == "delEvent"){
	echo toJson($event->deleteEvent($_GET["id"]));
}elseif($fn == "updateStatus"){
	echo toJson($event->updateStatus($_GET["id"], $_GET["status"]));
}elseif($fn == "resolve"){
	echo toJson($event->resolve($_GET["id"]));
}
//comment functions



function toJson($val){
	
	return json_encode($val);
	
}
?>