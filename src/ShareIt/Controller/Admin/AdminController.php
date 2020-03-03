<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace ShareIt\Controller\Admin;

use Exception;
use Orpheus\Rendering\HTMLRendering;
use ShareIt\Controller\AbstractHttpController;

abstract class AdminController extends AbstractHttpController {
	
	const OPTION_CONTENT_TITLE = 'contentTitle';
	const OPTION_CONTENT_LEGEND = 'contentLegend';
	
	protected $scope = self::SCOPE_ADMIN;
	
	/** @var array */
	protected $breadcrumb = [];
	
	public function addThisToBreadcrumb($label = null, $link = false) {
		$this->addRouteToBreadcrumb($this->getRouteName(), $label, $link);
	}
	
	/**
	 * Add given route to breadcrumb
	 * Label is optional, else we translate the route name
	 * Link could be
	 *  - disabled using false
	 *  - auto-generated using true or an array of value (passed as values)
	 *  - Specified using string
	 *
	 * @param $route
	 * @param string|null $label
	 * @param string|bool|array $link
	 * @throws Exception
	 */
	public function addRouteToBreadcrumb($route, $label = null, $link = true) {
		if( !$link ) {
			$link = null;
			
		} elseif( typeOf($link) !== 'string' ) {
			// Could be true => generate with no args
			// Could be an array => generate using args
			$params = $this->getValues();
			if( is_array($link) ) {
				$params += $link;
			}
			$link = u($route, $params);
		}
		$this->addBreadcrumb($label ? $label : t($route), $link);
	}
	
	public function getValues() {
		return [];
	}
	
	public function addBreadcrumb($label, $link = null) {
		$this->breadcrumb[] = (object) ['label' => $label, 'link' => $link];
	}
	
	public function preRun($request) {
		parent::preRun($request);
		HTMLRendering::setDefaultTheme('admin');
		
		$this->addRouteToBreadcrumb(getHomeRoute());
	}
	
	public function render($response, $layout, $values = []) {
		if( isset($GLOBALS['USER']) ) {
			$values['USER'] = $GLOBALS['USER'];
		}
		$values['Breadcrumb'] = $this->breadcrumb;
		return parent::render($response, $layout, $values);
	}
	
}
