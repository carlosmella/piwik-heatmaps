<?php header('Access-Control-Allow-Origin: http://efismart.local ');

$array_config = parse_ini_file("../../config/config.ini.php");

$db = mysqli_connect($array_config['host'],$array_config['username'],$array_config['password'],$array_config['dbname']);

$tables_prefix = $array_config['tables_prefix'];

if(isset($_POST['getUrls'])){
	$sql = "select url from ".$tables_prefix."urlsMapaCalor";
	$result = mysqli_query($db,$sql);
	
	$aux = array();
	while($row = mysqli_fetch_assoc($result)){
		$aux[]  = urlencode($row['url']);
	}	
	echo json_encode($aux);	
}
if (isset($_POST['insertClick'])){
	$page = htmlspecialchars($_POST['page']);
	$coordX = htmlspecialchars($_POST['coordX']);
	$coordY = htmlspecialchars($_POST['coordY']);
	
	if($coordX != NULL and $coordY!= NULL){
		$file = fopen("/var/tmp/piwik_mapaCalor_datos","a+");
		fwrite($file,"".$page.",".$coordX.",".$coordY.",click".PHP_EOL);
		fclose($file);
	}
}

if (isset($_POST['image'])){
	$image =htmlspecialchars($_POST['image']);
	$page = htmlspecialchars($_POST['page']);
	if($page == "/"){
		$page = "home";
	}
	$decoded = base64_decode(str_replace('data:image/png;base64,','',$image));
	file_put_contents("./img/".$page.".png",$decoded);
	print_r($page);
}

if (isset($_POST['insertMousemove'])){

	$arrayX = explode(',',htmlspecialchars($_POST['arrayX']));
	$arrayY = explode(',',htmlspecialchars($_POST['arrayY']));
	$arrayPage = explode(',',htmlspecialchars($_POST['arrayPage']));
	$length = count($arrayX);	
	
	$file = fopen("/var/tmp/piwik_mapaCalor_datos","a+");
	for($i=0; $i<$length;$i++){
		if($arrayX[$i] != NULL and $arrayY[$i]!= NULL){
			fwrite($file,"".$arrayPage[$i].",".$arrayX[$i].",".$arrayY[$i].",mouseMove".PHP_EOL);
		}
	}
	fclose($file);
}

