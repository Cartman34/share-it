<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace ShareIt\Controller;

use LoginAttempt;
use Orpheus\InputController\HTTPController\HTTPController;
use User;

abstract class AbstractHttpController extends HTTPController {
	
	const SCOPE_PUBLIC = 'public';
	const SCOPE_USER = 'user';
	const SCOPE_ADMIN = 'admin';
	
	/** @var string */
	protected $scope;
	
	/**
	 * @return string
	 */
	public function getScope() {
		return $this->scope;
	}
	
}
