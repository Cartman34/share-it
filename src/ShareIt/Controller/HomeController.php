<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace ShareIt\Controller;

use Orpheus\InputController\HTTPController\HTMLHTTPResponse;
use Orpheus\InputController\HTTPController\HTTPController;
use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\HTTPResponse;
use Orpheus\InputController\HTTPController\RedirectHTTPResponse;
use ShareIt\User;

class HomeController extends HTTPController {
	
	/**
	 * Controller declaration
	 *
	 * @param HTTPRequest $request The input HTTP request
	 * @return HTTPResponse The output HTTP response
	 */
	public function run($request) {
		
		if( User::isLogged() ) {
			return new RedirectHTTPResponse(u(getHomeRoute()));
		}
		
		return HTMLHTTPResponse::render('app/home');
	}
	
}
