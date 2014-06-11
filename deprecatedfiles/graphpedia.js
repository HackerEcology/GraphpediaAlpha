var graphpedia = angular.module('graphpedia',['ngRoute']);

    graphpedia.factory('getDataDBFactory',function($http){
        var factory={};

        factory.getDataDB = function(url) {
            var xml;
            $http({
              method: 'GET',
              url: "http://lookup.dbpedia.org/api/search.asmx/KeywordSearch?QueryClass=&MaxHits=10&QueryString=Physics"
            }).success(function(data, status, headers, config) {
              // data contains the response
              // status is the HTTP status
              // headers is the header getter function
              // config is the object that was used to create the HTTP request
              xml=data;
              console.log(data);
            }).error(function(data, status, headers, config) {
                console.log("Error");
            });
            return xml;
        }
        
        return factory;
    });

    graphpedia.controller('getDataDBController',function ($scope,getDataDBFactory){
        $scope.xmlData = [];

        $scope.getDB = function (){
            console.log("getDB");
            $scope.xmlData = graphpedia.getDataDB();
        }

    });


function createJSONGraph() {
    jsonObj = [];
    for(i=0;i<3;i++) {

        item = {}
        item ["title"] = i;
        item ["email"] = i+"@gmail.com";

        jsonObj.push(item);
    };

    console.log(jsonObj);
}

function createJSONAdjacencies() {
    jsonAdj = [];

}