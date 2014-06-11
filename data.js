var graphpedia = angular.module('graphpedia', []);
var uid,graphname;

graphpedia.factory('DataFactory',function($http,$filter){
	var factory={};
	
	factory.getDataDB = function(query){
		$http.get('http://lookup.dbpedia.org/api/search.asmx/KeywordSearch?QueryClass=&MaxHits=10&QueryString='+query)
			.success(function(data){
				if(data.results.length==0){
					return;
				}
				treeJSONProvider(data.results,graph);
				console.log(graph);
				console.log(fd);
				fd.loadJSON(graph);
				fd.refresh();
				return data;
			})
			.error(function(error){
				console.log(error);
				return [];
			})
	}

	factory.saveGraph = function(){
		if(!graphname){
			alert("You have an empty graph.");
		}
		$http.defaults.headers.post['Content-Type']='application/x-www-form-urlencoded; charset=UTF-8';
		var postdata={};
		postdata.uid=uid;
		postdata.graphname=graphname;
		postdata.graph=JSON.stringify(graph);
		postdata.notetext=tinyMCE.get('notebook_id').getContent();
		console.log(postdata);
		$http.post('/graphpedia/db/savegraph.php',postdata)
			.success(function(data,status,headers,config){
				console.log(data);
				console.log("success");
			})
			.error(function(error,status,headers,config){
				console.log(error);
				console.log("error");
			})
	}
	
	return factory;
});


graphpedia.controller('gpControl', function ($scope,$http,DataFactory) {

	$scope.query="";

	$scope.getDataDB = function() {
		console.log($scope.query);
		graphname=$scope.query;
		DataFactory.getDataDB($scope.query);
	};

	$scope.expand = function() {
		if(currentMotherNode){
			DataFactory.getDataDB(currentMotherNode.name);
		}
	};

	$scope.add = function() {
		addNode($scope.newnodename,currentMotherNode);
	}

	$scope.saveGraph = function() {
		DataFactory.saveGraph();
	}

	$scope.cancel = function(){
		currentMotherNode=undefined;
		var innerdetail = document.getElementById("inner-details");
        innerdetail.innerHTML="";
        $("#wikipage").empty();
	}
	//$scope.xml=DataFactory.getDataDB();

 /* $http.get('http://lookup.dbpedia.org/api/search.asmx/KeywordSearch?QueryClass=&MaxHits=10&QueryString=Beijing').success(function(data) {
    $scope.xml = data;
  });*/
});

function treeJSONProvider(json,graph){	//Graph is also a json array
	var motherNode;
	if(json.length>0){
		motherNode=json[0];
		var ajacency=ajacencyProvider(motherNode,json.slice(1));
		var nodeObj={};
		nodeObj.adjacencies=ajacency;
		nodeObj.data={
			"$color": "#AABBCC",
			"$type": "circle",
			"$dim": 5
		};
		nodeObj.id=motherNode.label;
		nodeObj.name=motherNode.label;
		graph.push(nodeObj);
		descriptionText[motherNode.label]=motherNode.description;
		for(i=1;i<json.length;i++){
			var childNodeObj={};
			childNodeObj.adjacencies=[];
			childNodeObj.data={
				"$color": "#0000ff",
				"$type": "circle",
				"$dim": 5
			};
			childNodeObj.id=json[i].label;
			childNodeObj.name=json[i].label;
			graph.push(childNodeObj);
			descriptionText[json[i].label]=json[i].description;
		}
	}
}

function addNode(newNodeName,oldNode){
	if(newNodeName && newNodeName!="" && oldNode && graph){
		var adjacency=[];
		var adjacencyNode={};
		var nodeObj={}
		adjacencyNode.nodeTo=oldNode.name;
		adjacencyNode.nodeFrom=newNodeName;
		adjacencyNode.data={"color": "#00ff00"};
		adjacency.push(adjacencyNode);
		nodeObj.adjacencies=adjacency;
		nodeObj.data={
			"$color": "#00ff00",
			"$type": "circle",
			"$dim": 8
		};
		console.log(nodeObj);
		nodeObj.id=newNodeName;
		nodeObj.name=newNodeName;
		graph.push(nodeObj);
		fd.loadJSON(graph);
		fd.refresh();
	} else if(newNodeName && newNodeName!="" && graph) {
		var nodeObj={};
		nodeObj.adjacencies=[];
		nodeObj.data={
			"$color": "#ff0000",
			"$type": "circle",
			"$dim": 8
		};
		nodeObj.id=newNodeName;
		nodeObj.name=newNodeName;
		graph.push(nodeObj);
		fd.loadJSON(graph);
		fd.refresh();
	} else {
		alert("Select node, and give a name to new node.")
	}

}

function ajacencyProvider(motherNode,childrenArray,graph){
	var adjacencies=[];
	for(i=0;i<childrenArray.length;i++){
		var ajacencyNode={};
		ajacencyNode.nodeTo=childrenArray[i].label;
		ajacencyNode.nodeFrom=motherNode.label;
		ajacencyNode.data={"color": "#557EAA"};
		adjacencies.push(ajacencyNode);
	}
	return adjacencies;
}
