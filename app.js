var SERVICE_URL = "classes/main.php?"
var app = angular.module('app', []); 

app.controller("NavigationController", function($http, $httpParamSerializerJQLike){
	
	navCon = this;
	this.page = "login"
	this.user;
	
	this.changePage = function(p, l = true){//p for page l for "rquire login" (boolean)
		if(l == true){
			if(navCon.user == ""||navCon.user == undefined){
				navCon.page = "login"
			}else
				navCon.page = p;
		}else{
			navCon.page = p;
		}
	}
	
	this.doLogin = function(){
		if(navCon.user == ""||navCon.user == undefined){
			alert("Your username or password was incorrect");
		}else{
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
					alert("Your username or password was incorrect");
				}
			});
		
		}
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
			if(resolved == false){
				eventCon.events[i].comments = response.data;
			}else{
				eventCon.resolvedEvents[i].comments = response.data;
			}
		});
		
	}
	this.toggle = function(e, i, r = false){// e for event, i for index, r for resolved - default to false
		if(e.show == true){
			e.show = false;
		}else{
			eventCon.getComments(e.id, i, r);
			e.show = true;
		}
	}
	this.toggleComments = function(e){
		if(e.showC == true){
			e.showC = false;
		}else{
			e.showC = true;
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
		if(response.data == "null"){
			eventCon.getEvents();
		}else{
			alert("An error occured. Please try again. If the problem persists please contact the Webmaster.")
		}
		});
	}
	
	this.toggleOptions = function(e){
		if(e.showO == true){
			e.showO = false;
		}else{
			e.showO = true;
		}
	}
	
	this.updateMode = function(e){
		if(e.showC == true){
		}else{
			e.showC = true;
		}
		if(e.showU == true){
			e.showU = false;
		}else{
			e.showU = true;
		}
	}
	
	
	
	this.getEvents();
	this.getResolvedEvents();
	
});