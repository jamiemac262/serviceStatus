var SERVICE_URL = "classes/main.php?"
var app = angular.module('app', []); 
var dumbEvents = [{"id":"3","title":"Unexpected power outage","message":"We experienced an unexpected power outage in our Glasgow data centre.","date":"2016-12-09 14:50:20","status":"1","resolved":"0", "comments":[{"id":"3","eventID":"3","message":"Test coment","date":"2016-12-12 10:10:00"}]},{"id":"3","title":"Unexpected power outage","message":"We experienced an unexpected power outage in our Glasgow data centre.","date":"2016-12-09 14:50:20","status":"3","resolved":"0", "comments":[]}]

app.controller("NavigationController", function($http, $httpParamSerializerJQLike){
	
	navCon = this;
	this.page = "login"
	this.user;
	
	this.changePage = function(p, l = true){//p for page l for "rquire login" (boolean)
		console.log("change " + l + p);
		if(l == true){
			console.log("l = true")
			if(navCon.user == ""||navCon.user == undefined){
				console.log("user undefined");
				navCon.page = "login"
			}else
				console.log("User defined. changing to: " + p);
				navCon.page = p;
		}else{
			console.log("l = false. changing to: " + p);
			navCon.page = p;
			console.log(navCon.page);
		}
		console.log(navCon.page);
	}
	
	this.doLogin = function(){
		data = {
			"fn" : "login",
			"name" : navCon.user.username,
			"pass" : navCon.user.password
		}
		$http({
			url: SERVICE_URL,
			method: 'POST',
			data: $httpParamSerializerJQLike(data),
			headers: {
			  'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(response) {
			if(response.data == "1"){
				navCon.changePage("unresolved");
			}else{
				alert("Your usrname or password was incorrect");
			}
		});
		
		
	}
	
});


app.controller('EventController', function($http, $httpParamSerializerJQLike){
	eventCon = this;
	this.events;
	this.resolvedEvents;
	
	this.getEvents = function(){
		
		data = {
			"fn" : "allEvent"
		}
		$http({
			url: SERVICE_URL,
			method: 'POST',
			data: $httpParamSerializerJQLike(data),
			headers: {
			  'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(response) {
			eventCon.events = response.data;
			console.log(eventCon.events);
		});
		
	}
	this.getResolvedEvents = function(){
		
		data = {
			"fn" : "getResolvedEvent"
		}
		$http({
			url: SERVICE_URL,
			method: 'POST',
			data: $httpParamSerializerJQLike(data),
			headers: {
			  'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(response) {
			console.log(response.data);
			eventCon.resolvedEvents = response.data;
		});
		
	}
	this.getComments = function(id, i, resolved){//resolved = boolean: Are we adding comments to a resolved event?
		data = {
			"fn" : "getComment",
			"id" : id
		}
		$http({
			url: SERVICE_URL,
			method: 'POST',
			data: $httpParamSerializerJQLike(data),
			headers: {
			  'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(response) {
			console.log(response);
			if(resolved == false){
				eventCon.events[i].comments = response.data;
			}else{
				eventCon.resolvedEvents[i].comments = response.data;
			}
		});
		
	}
	this.toggle = function(e, i, r = false){// e for event, i for index, r for resolved - default to false
		console.log(e);
		if(e.show == true){
			e.show = false;
			console.log(e.show);
		}else{
			eventCon.getComments(e.id, i, r);
			e.show = true;
			
			console.log(e);
		}
	}
	this.toggleComments = function(e){
		console.log(e);
		if(e.showC == true){
			e.showC = false;
			console.log(e.showC);
		}else{
			e.showC = true;
			console.log(e.showC);
		}
	}
	
	//the following functions should only be used by member.html
	
	this.addEvent = function(){
		newEvent = eventCon.newEvent;
		if(newEvent == undefined || newEvent.title == undefined || newEvent.message == undefined || newEvent.status == undefined){
			alert("An error has occured. Please check the information you have submitted and try again.")
		}else{
		data = {
			"fn" : "newEvent",
			"title" : eventCon.newEvent.title,
			"message" : eventCon.newEvent.message,
			"status" : eventCon.newEvent.status
		}
		$http({
			url: SERVICE_URL,
			method: 'POST',
			data: $httpParamSerializerJQLike(data),
			headers: {
			  'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(response) {
			console.log(response);
			if(response.data == "null"){
				eventCon.getEvents();
				eventCon.newEvent = undefined;
			}else{
				alert("An error has occured. Please check the information you have submitted and try again.")
			}
		});
		
		
		}
	}
	
	
	this.updateEvent = function(e){
		
		
		data = {
			"fn" : "newComment",
			"id" : e.id,
			"message" : e.newComment,
			"status" : e.status
		}
		$http({
			url: SERVICE_URL,
			method: 'POST',
			data: $httpParamSerializerJQLike(data),
			headers: {
			  'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(response) {
			console.log(response);
			if(response.data == "null"){
				eventCon.getEvents();
				eventCon.newEvent = undefined;
			}else{
				alert("An error has occured. Please check the information you have submitted and try again.")
			}
		});
		
		
	}
	
	this.resolve = function(e){
		
		data = {
			"fn" : "resolve",
			"id" : e.id
			
		}
		$http({
			url: SERVICE_URL,
			method: 'POST',
			data: $httpParamSerializerJQLike(data),
			headers: {
			  'Content-Type': 'application/x-www-form-urlencoded'
			}
		}).then(function(response) {
		console.log(response);
		if(response.data == "null"){
			eventCon.getEvents();
		}else{
			alert("An error occured. Please try again. If the problem persists please contact the Webmaster.")
		}
		});
	}
	
	this.toggleOptions = function(e){
		console.log(e);
		if(e.showO == true){
			e.showO = false;
			console.log(e.showO);
		}else{
			e.showO = true;
			console.log(e.showO);
		}
	}
	
	this.updateMode = function(e){
		console.log(e);
		if(e.showC == true){
			console.log(e.showC);
		}else{
			e.showC = true;
			console.log(e.showC);
		}
		if(e.showU == true){
			e.showU = false;
			console.log(e.showU);
		}else{
			e.showU = true;
			console.log(e.showU);
		}
	}
	
	
	
	this.getEvents();
	this.getResolvedEvents();
	
});