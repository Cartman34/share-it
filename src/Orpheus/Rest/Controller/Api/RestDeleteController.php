<?php

namespace Orpheus\Rest\Controller\Api;

use Orpheus\InputController\HTTPController\HTTPResponse;
use Orpheus\InputController\InputRequest;

/**
 * Created by Florent HAZARD on 11/02/2018
 */
class RestDeleteController extends EntityRestController {
	
	/**
	 * Run this controller
	 *
	 * @param InputRequest $request
	 * @return HTTPResponse
	 */
	public function run($request) {
		
		$this->item->remove();
		
		return new HTTPResponse();
	}
}
