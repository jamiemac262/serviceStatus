<?php

include_once("User.php");
include_once("Event.php");
include_once("Comment.php");


$user = new User();
$event = new Event();
$comment = new Comment();


$fn = $_POST["fn"];


//Login functions
if($fn == "login"){
	
	echo $user->doLogin($_POST["name"], $_POST["pass"]);
	
}
//Event functions
elseif($fn == "allEvent"){
	echo toJson($event->getAll());
}elseif($fn == "getEvent"){
	echo toJson($event->get($_POST['id']));
}elseif($fn == "getResolvedEvent"){
	echo toJson($event->getResolved($_POST[$id]));
}elseif($fn == "newEvent"){
	echo toJson($event->create($_POST["title"], $_POST["message"], $_POST["status"]));
}elseif($fn == "updateEvent"){
	echo toJson($event->updateEvent($_POST["title"], $_POST["message"], $_POST["status"]));
}elseif($fn == "delEvent"){
	echo toJson($event->deleteEvent($_POST["id"]));
}elseif($fn == "updateStatus"){
	echo toJson($event->updateStatus($_POST["id"], $_POST["status"]));
}elseif($fn == "resolve"){
	echo toJson($event->resolve($_POST["id"]));
}
//comment functions
elseif($fn == "getComment"){
	echo toJson($comment->getCommentsForEvent($_POST["id"]));
}elseif($fn == "newComment"){
	echo toJson($comment->create($_POST["id"], $_POST['message'], $_POST['status']));
}elseif($fn == "editComment"){
	echo toJson($comment->editComment($_POST["id"], $_POST['message'], $_POST['status']));
}elseif($fn == "deleteComment"){
	echo toJson($comment->deleteComment($_POST["id"]));
}


function toJson($val){
	
	return json_encode($val);
	
}
?>