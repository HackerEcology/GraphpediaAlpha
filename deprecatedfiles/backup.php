
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Graphpedia Prototype 0.5</title>

<!-- CSS Files -->
<link type="text/css" href="css/base.css" rel="stylesheet" />
<link type="text/css" href="css/ForceDirected.css" rel="stylesheet" />

<!--[if IE]><script language="javascript" type="text/javascript" src="../../Extras/excanvas.js"></script><![endif]-->

<!-- JIT Library File -->
<script language="javascript" type="text/javascript" src="Jit/jit-yc.js"></script>

<!-- Example File -->
<script language="javascript" type="text/javascript" src="example1.js"></script>
</head>

<body onload="init();">
<div id="container">

<div id="left-container">



        <div class="text">
        <h4>
Force Directed Static Graph    
        </h4> 

            A static JSON Graph structure is used as input for this visualization.<br /><br />
            You can <b>zoom</b> and <b>pan</b> the visualization by <b>scrolling</b> and <b>dragging</b>.<br /><br />
            You can <b>change node positions</b> by <b>dragging the nodes around</b>.<br /><br />
            The clicked node's connections are displayed in a relations list in the right column.<br /><br />
            The JSON static data is customized to provide different node types, colors and widths.
            
        </div>

        <div id="id-list"></div>


</div>

<div id="center-container">
    <div id="infovis"></div>    
</div>

<div id="right-container">

<div id="inner-details"></div>

</div>

<div id="log"></div>
</div>
</body>
</html>


#left-container {
    height:200px;
    position:absolute;
    top:200;
}

#left-container {
    left:600px;
    background-position:center right;
    border-left:1px solid #ddd;
    
}

style='display: none; position: relative; left: 0px; top: 0px; border: solid black 1px; padding: 10px; backgr

//init modal first
// $('#content-control').modal({show:false});

#right-container {
    width:200px;
    color:#686c70;
    text-align: left;
    overflow: auto;
    background-repeat:no-repeat;
    border-bottom:1px solid #ddd;
}



#right-container {
    right:0;
    background-position:center left;
    border-right:1px solid #ddd;
}


#right-container {
    height:400px;
    position:absolute;
} 

#right-container h4{
    text-indent:8px;
}

<?php 
$result = $db->query("SELECT * FROM graph WHERE user_id='{$user}'");
while($row=$result->fetch_assoc()){
    ?><li>
        <a><?php echo $row['graphname'] ?></a>
    </li><?php
}
?>