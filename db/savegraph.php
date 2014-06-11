<?php 

error_reporting(E_ALL);
//header("Access-Control-Allow-Headers: x-requested-with"); 

$data=json_decode(file_get_contents("php://input"));

//echo $data,"!";

include('connect.php');

$p_uid=$data->uid;
$p_graphname=$data->graphname;
$p_graph=$data->graph;
$p_notetext=$data->notetext;

$sql="INSERT INTO graph (uid,graphname,graph,notetext) VALUES ('{$p_uid}','{$p_graphname}','{$p_graph}','{$p_notetext}')";
$result=$db->query($sql);

if($result){
	header('Content-Type: text/html; charset=utf-8');
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
}
else {
	print_r("error");
} 

?>