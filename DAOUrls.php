<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\MapaCalor;

use Piwik\View;
use Piwik\Db;
use Piwik\Common;

/**
 * A controller let's you for example create a page that can be added to a menu. For more information read our guide
 * http://developer.piwik.org/guides/mvc-in-piwik or have a look at the our API references for controller and view:
 * http://developer.piwik.org/api-reference/Piwik/Plugin/Controller and
 * http://developer.piwik.org/api-reference/Piwik/View
 */
class DAOUrls 
{

    public function listUrls(){
	try{
		$sql = "select * from ".Common::prefixTable("urlsMapaCalor")." ";
		$result = Db::fetchAll($sql);
	}catch(Exception $e){
		print_r($e);
	}
	return $result;
    }
	
    public function insertUrl($url){

	try{
		$sql = "insert into ".Common::prefixTable("urlsMapaCalor")." values (null,'".$url."') ";
		Db::exec($sql);
	}catch(Exception $e){
		print_r($e);
	}
    }

    public function deleteUrl($id){
	
	try{
		$sql = "select url from ".Common::prefixTable("urlsMapaCalor")." where id=".$id." ";
		$url = Db::fetchOne($sql);
	
		$sql = "delete from  ".Common::prefixTable("urlsMapaCalor")." where id=".$id." ";
		Db::exec($sql);
		$patron="/^.*\/\/[^\/]+/";
		$page = preg_replace($patron,"",$url);
		$sql = "delete from  ".Common::prefixTable("mapaCalor")." where page='".$page."' ";
		Db::exec($sql);
	}catch(Exception $e){
		print_r($e);
	}
    }

}
