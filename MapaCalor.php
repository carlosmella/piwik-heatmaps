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

class MapaCalor extends \Piwik\Plugin
{

	public function getListHooksRegistered(){

                return array('AssetManager.getJavaScriptFiles' => 'getJavaScriptFiles',
			     'AssetManager.getStylesheetFiles' => 'getStylesheetFiles');
        }

        public function getJavaScriptFiles(&$files){

                $files[] = "plugins/MapaCalor/javascripts/html2canvas.js";
		$files[] = "plugins/MapaCalor/javascripts/ajaxHeatMap.js";
		$files[] = "plugins/MapaCalor/javascripts/simpleheat.js";
        }

	public function getStylesheetFiles(&$files){
		$files[] = "plugins/MapaCalor/stylesheets/estilosMC.css";	
    	}

	public function install(){
		
		try{
			$sql = "CREATE TABLE ".Common::prefixTable("mapaCalor")." (
					page varchar(255) not null primary key,
					array text not null
				) default charset=utf8";
			Db::exec($sql);
		}catch(Exception $e){
			if (!Db::get()->isErrNo($e,'1050')){
				throw $e;
			}
		}
		
		try{
			$sql = "CREATE TABLE ".Common::prefixTable("urlsMapaCalor")." (
					id int  not null auto_increment primary key,
					url varchar(255) not null
				) default charset=utf8";
			Db::exec($sql);
		}catch(Exception $e){
			if (!Db::get()->isErrNo($e,'1050')){
				throw $e;
			}
		}		
	}
	
	public function uninstall(){
		Db::dropTables(Common::prefixTable("mapaCalor"));
		Db::dropTables(Common::prefixTable("urlsMapaCalor"));		
	}
}



