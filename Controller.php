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
class Controller extends \Piwik\Plugin\Controller
{
    public function index()
    {
        // Render the Twig template templates/index.twig and assign the view variable answerToLife to the view.
        return $this->renderTemplate('index');
    }

    public function listaUrls(){
	$daoUrls = new DAOUrls();
	$result = $daoUrls->listUrls();
	$view = new View('@MapaCalor/listaUrls');
	$this->setBasicVariablesView($view);
	$view->urls = $result;
	return $view->render();
    }
	
    public function anhadirUrl(){
	
	$url = urldecode(Common::getRequestVar('url'));
	$daoUrls = new DAOUrls();
	$daoUrls->insertUrl($url);

	return $this->listaUrls();
    }

    public function eliminarUrl(){
		
	$id = Common::getRequestVar('id');
	
	$daoUrls = new DAOUrls();
	$daoUrls->deleteUrl($id);
	
	return $this->listaUrls();
    }

    public function recuperarPuntos(){

	$url = Common::getRequestVar('url');
	
	$daoMP = new DAOMapaCalor();
	$result  = $daoMP->getPoints($url);

	$view = new View('@MapaCalor/heatMap');
	$this->setBasicVariablesView($view);
	$view->datos = $result;
	if(urlDecode($url) == "/"){
		$url = "home";
	}
	$view->page = urlDecode($url);
	return $view->render();	
    }
}
