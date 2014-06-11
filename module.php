
<!DOCTYPE html>
<html data-ng-app="graphpedia">
<head>

<title>Graphpedia Prototype 0.5</title>

<!-- CSS Files -->
<link type="text/css" href="css/base.css" rel="stylesheet" />
<link type="text/css" href="css/ForceDirected.css" rel="stylesheet" />
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

<!--[if IE]><script language="javascript" type="text/javascript" src="../../Extras/excanvas.js"></script><![endif]-->

<!-- JIT Library File -->
<script language="javascript" type="text/javascript" src="Jit/jit-yc.js"></script>
<script src="Angularjs/angular.min.js"></script>
<script src="Angularjs/angular-route.min.js"></script>

<!-- Example File -->
<script language="javascript" type="text/javascript" src="example1.js"></script>

</head>

<body onload="init();">

<h1>Graphpedia Alpha 0.5</h1>
      <div id = "search_id" class = "search">
              <button data-ng-click="getDB()">Test</button>
              <input type="text" name="searchstring" id="ksearchstring" data-ng-click="getDB()" class="searchbox"/>
              <input type="button" id="ksearch" value="Search" class="btn btn-large btn-primary"/>
              <input type="button" id="reload" onclick="window.location.reload();" value="New Search" class="btn btn-large btn-warning"/>
      </div>

<div id="container">

    <div id="left-container">
            <div class="text">
                A static JSON Graph structure is used as input for this visualization.<br />           
            </div>
            <div id="id-list"></div>
    </div>

    <div id="graph-container">
        <div id="infovis"></div>  
        <div id="log"></div>  
    </div>

    <div id="right-container">
        <div id="inner-details"></div>
    </div>
</div>

</body>
</html>
