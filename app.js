var SERVICE_URL = "classes/main.php?"
var app = angular.module('app', []); 
var dumbEvents = [{"id":"3","title":"Unexpected power outage","message":"We experienced an unexpected power outage in our Glasgow data centre.","date":"2016-12-09 14:50:20","status":"1","resolved":"0", "comments":[{"id":"3","eventID":"3","message":"Test coment","date":"2016-12-12 10:10:00"}]},{"id":"3","title":"Unexpected power outage","message":"We experienced an unexpected power outage in our Glasgow data centre.","date":"2016-12-09 14:50:20","status":"3","resolved":"0", "comments":[]}]

app.controller('EventController', function($http, $httpParamSerializerJQLike){
	eventCon = this;
	this.events;
	
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
	this.getComments = function(id, i){
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
			eventCon.events[i].comments = response.data;
		});
		
	}
	this.toggle = function(e, i){// e for event, i for index
		console.log(e);
		if(e.show == true){
			e.show = false;
			console.log(e.show);
		}else{
			eventCon.getComments(e.id, i);
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
	this.getEvents();
	
});