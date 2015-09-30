<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\MapaCalor;

use Piwik\Db;
use Piwik\Common;

class Tasks extends \Piwik\Plugin\Tasks
{
    public function schedule()
    {
        $this->hourly('actualizarCoordenadas');  // method will be executed once every hour
       /* $this->daily('myTask');   // method will be executed once every day
        $this->weekly('myTask');  // method will be executed once every week
        $this->monthly('myTask'); // method will be executed once every month

        // pass a parameter to the task
        $this->weekly('myTaskWithParam', 'anystring');

        // specify a different priority
        $this->monthly('myTask', null, self::LOWEST_PRIORITY);
        $this->monthly('myTaskWithParam', 'anystring', self::HIGH_PRIORITY);
	*/
    }

    public function actualizarCoordenadas()
    {
	$clicks =  array();
	$mouseMove = array();	
	$Array = array();
	$pages = array();
	$rutaArchivo = "/var/tmp/piwik_mapaCalor_datos";
	$val = -1;

	try{
		$sql = "select page,array from ".Common::prefixTable("mapaCalor")."";
		$result = Db::fetchAll($sql);
	}catch(Exception $e){
		throw $e;
	}
	for($i=0;$i<count($result);$i++){
		$Array[$i][0] = $result[$i]['page']; 
		$Array[$i][1] = json_decode($result[$i]['array']);
		$pages[] = $result[$i]['page'];
	}
	
	//echo getcwd();
	
	$file = fopen($rutaArchivo,"r");
	if($file == false) die("Error!");
	else{
	$i=0;
	while(!feof($file)){
		$linea = explode(',',fgets($file));
		$flag = false;
		$page  = $linea[0];
		$x = round($linea[1] - $linea[1]%10,0,PHP_ROUND_HALF_DOWN);
		$y = round($linea[2] - $linea[2]%10,0,PHP_ROUND_HALF_DOWN);
		$tipo = $linea[3];
		if(strcmp(trim($tipo),"click") == 0){
			$val = 10;
		}
		else if(strcmp(trim($tipo),"mouseMove") == 0){
			$val = 0.5;
		}

		for($a=0;$a<count($Array);$a++){
			if($Array[$a][0] == $page){
				for($j=0;$j<count($Array[$a][1]);$j++){
					if( $Array[$a][1][$j][0] == $x && $Array[$a][1][$j][1] == $y){
						$Array[$a][1][$j][2]  += $val;
						$flag = true;
					}
				}
				if($flag == false){
					$length = count($Array[$a][1]);
					$Array[$a][1][$length][0] = $x;
					$Array[$a][1][$length][1] = $y;
					$Array[$a][1][$length][2] = $val;
					$flag = true;
				}
			}
		}
		if($flag == false){
			$length = count($Array);
			$length2 = count($Array[$length][1]);
			$Array[$length][0] = $page;
			$Array[$length][1][$length2][0] = $x;
			$Array[$length][1][$length2][1] = $y;
			$Array[$length][1][$length2][2] = $val;
		}
		$i++;
	}
	fclose($file);
	}
	for($a=0;$a<count($Array);$a++){
		if(in_array($Array[$a][0],$pages)){
			$sql = "update ".Common::prefixTable("mapaCalor")." set array='".json_encode($Array[$a][1])."' where page='".$Array[$a][0]."' ";
			Db::exec($sql);
		}else{
			$sql = "insert into ".Common::prefixTable("mapaCalor")." (page,array) values ('".$Array[$a][0]."','".json_encode($Array[$a][1])."')";	
			Db::exec($sql);

		}
	}
	$file = fopen($rutaArchivo,"w");
	fclose($file);
    }

    
}
