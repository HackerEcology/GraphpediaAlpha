<!DOCTYPE html>

<?php

error_reporting(E_ALL);
require 'db/connect.php';
require 'facebook-php-sdk-master/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '',
  'secret' => '',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
      //If user is new, add profile into db
        $trimuserid=trim($user);
        $result = $db->query("SELECT * FROM user WHERE user_id='{$trimuserid}'");
        if($result){
          if($result->num_rows){
              //Do Nothing for now
          } else{
              //Escape to avoid injection
              $user_id=trim($user);
              $user_name=trim($user_profile['name']);
              $first_name=trim($user_profile['first_name']);
              $last_name=trim($user_profile['last_name']);
              $email=trim($user_profile['email']);

              if($insert = $db->query("
                  INSERT INTO user (user_name,first_name,last_name,email,user_id)
                  VALUES ('{$user_name}','{$first_name}','{$last_name}','{$email}','{$user_id}')
                ")){
                      
                } else{
                    echo "error";
                }
              
          }
      }
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = "logout.php";
} else {
  $statusUrl = $facebook->getLoginStatusUrl();
  $loginUrl = $facebook->getLoginUrl(
      array('scope' => 'email')
    );
}

?>

<html lang='en' ng-app='graphpedia' xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<title>Graphpedia</title>
		<meta name="viewpoint" content="width=device-width, initial-scale=1.0">
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="bootstrap/css/style.css" rel="stylesheet">
		<link type="text/css" href="css/ForceDirected.css" rel="stylesheet" />
		<link type="text/css" href="css/base.css" rel="stylesheet" />
		<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	</head>

	<body onload="init();" ng-controller="gpControl">

		<script src="Angularjs/angular.min.js"></script>
		<script src="Angularjs/angular-route.min.js"></script>
		<script src="graph.js"></script>
		<script src="data.js"></script>

		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<a href="#" class="navbar-brand">Graphpedia Alpha Version 0.5</a>

				<button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<div class="collapse navbar-collapse navHeaderCollapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="#claim" data-toggle="modal">Claim</a></li>
						<li>
							<?php if ($user): ?>
			                	<a href="<?php echo $logoutUrl; ?>">Logout</a>
			                	<script>uid=<?php echo $user ?></script>
			                <?php else: ?>
			                    <!--<a href="<?php echo $loginUrl; ?>">Login with Facebook</a></li>-->
			                <?php endif ?>

			                <?php if ($user): ?>
			                	<li>
			                    <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

			                <?php
			                    //echo "Hi, ",$user_profile['first_name'];
			                ?>
			                <?php else: ?>
			                    
			                <?php endif ?>
						</li>
						<li><a href="#contact" data-toggle="modal">Contact</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="jumbotron text-center">
				<h1>Graphpedia</h1>
				<form ng-submit='getDataDB(query)'>
					<input type="search" class="form-control" placeholder="e.g. Linguistics" ng-model="query">
					<a class="btn btn-large btn-primary" ng-click="getDataDB(query)">Search</a> <!--"getDataDB(query)"-->
					<a class="btn btn-large btn-warning" onclick="window.location.reload();">New Search</a>
				</form>
			</div>
		</div>

		<div class="container">
			<div class="row">
			    <div class="col-md-3">
			    	<?php if($user): ?>
						<p style="margin-bottom=10px">
							<input type="button" id="savegraph_id" value="Save this graph!" ng-click="saveGraph()" class="btn btn-xs btn-info"/>
							<a id="retrievegraph_id" href="#graphlist" class="btn btn-xs btn-info" data-toggle="modal">My Graphs</a>
			    		</p>
					<?php endif ?>
			    	<input type="button" id="viewwiki_id" value="Wikipedia" onclick="loadwiki()" class="btn btn-xs btn-info"/>
		            <input type="button" id="expandbtn_id" value="Expand" ng-click="expand()" class="btn btn-xs btn-info"/>
		            <input type="button" id="addbtn_id" value="Add Node" ng-click="add()" class="btn btn-xs btn-info"/>
		            <input type="button" id="cancel_id" value="Cancel" ng-click="cancel()" class="btn btn-xs btn-info"/>
		            <p style="margin-top:10px">
		            <span> Add Node: </span>
		            <input type="text" name="addnodetext" id="addnodetext_id" class="addnode" placeholder="New node name" ng-model="newnodename"/>
		        	</p>
					<div id="inner-details"></div>
				</div>
				<div id="graph-container" class="col-md-9">
			        <div id="infovis"></div>
			        <div id="log"></div>
			    </div>
			</div>
		</div>


		<div class="navbar navbar-invert navbar-fixed-bottom">
			<div class="container">
				<p class="navbar-text pull-left"><a href="terms.html" target="_blank">Terms of use and privacy</a></p>
				<a href="https://www.surveymonkey.com/s/CG2NXLQ" target="_blank" class="navbar-btn btn btn-danger btn pull-right">We need feedback</a>
			</div>
		</div>

		<div class="modal fade" id="contact" role="dialog" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<p>Graphpedia</p>
					</div>
					<div class="modal-body">
						<p>Contact:</p>
						<p>
							jiannan.zhang@stx.ox.ac.uk<br>
							UK: +44-07562823434<br>
							Web: <a href="http://www.jiannanweb.com" target="_blank">jiannanweb.com</a>
						</p>
					</div>
					<div class="modal-footer">
						<a class="btn btn-default" data-dismiss="modal">Close</a>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="claim" role="dialog" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<p>Graphpedia Alpha Version Claims</p>
					</div>
					<div class="modal-body">
						<p>This is an alpha version of Graphpedia</p>
						<p>
							Login: no login is allowed now, functionalities after login is being implemented.<br>
						</p>
						<p>
							Other ideas: we have ideas in mind, if you get new ideas, please email us.<br>
						</p>
						<p>
							Want to work on this? Send us an email!
						</p>
					</div>
					<div class="modal-footer">
						<a class="btn btn-default" data-dismiss="modal">Close</a>
					</div>
				</div>
			</div>
		</div>

		<div class="infowrap">
		<div id='content-control' class = 'modal fade' role='dialog' tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
				    	<div id="wikipage-details">View Wikipedia Page</div>
				    	<!--<textarea id="notebook_id" class="notebook" style="width: 100%; height: 100%; margin:0; border:1px solid #CCC;"></textarea>
						</div>-->
			        <div class="modal-body">
			    		<div id="wikipage" class="wikidiv"></div>
			    	</div>
			    	<div class="modal-footer">
						<a class="btn btn-default" data-dismiss="modal">Close</a>
					</div>
			    </div>
			</div>
		</div>
		</div>

		<div class="graphlistmodal">
		<div id='graphlist' class = 'modal fade' role='dialog' tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
				    	<h2>My Knowledge Graphs</h2>
					</div>
			        <div class="modal-body">
			        	<ul>
			    		
			    		</ul>
			    	</div>
			    	<div class="modal-footer">
						<a class="btn btn-default" data-dismiss="modal">Close</a>
					</div>
			    </div>
			</div>
		</div>
		</div>

		<script src="bootstrap/js/bootstrap.js"></script>
		<script src="Jit/jit-yc.js"></script>
<!--		<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
	    <script>
	            tinymce.init({selector:'textarea'});
	    </script>-->

	    <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-46844350-1', 'jiannanweb.com');
		  ga('send', 'pageview');
		</script>
	</body>
</html>