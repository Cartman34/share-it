<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace ShareIt\Controller\User;

use ShareIt\Controller\Admin\AdminController;
use ShareIt\User;

abstract class UserController extends AdminController {
	
	protected $scope = self::SCOPE_USER;
	
	/** @var User */
	protected $user;
	
	public function preRun($request) {
		parent::preRun($request);
		
		$this->setOption('mainmenu', 'user_menu');
		$this->user = User::getLoggedUser();
	}
	
}
