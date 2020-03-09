<?php

namespace Orpheus\Rest\Controller;

use Orpheus\Exception\ForbiddenException;
use Orpheus\InputController\HTTPController\JSONHTTPResponse;
use Orpheus\InputController\InputRequest;
use Orpheus\InputController\OutputResponse;
use Orpheus\Rest\Controller\Api\RestController;
use User;

/**
 * Created by Florent HAZARD on 04/02/2018
 */
class UserLoginRestController extends RestController {
	
	/**
	 * @param InputRequest $request
	 * @return JSONHTTPResponse|OutputResponse|null
	 * @throws ForbiddenException
	 */
	public function run($request) {
		$userEmail = $request->getInputValue('email');
		$user = User::getByEmail($userEmail);
		if( !$user ) {
			throw new ForbiddenException(User::text('Invalid authentication'));
		}
		if( $user->password !== hashString($request->getInputValue('password')) ) {
			throw new ForbiddenException(User::text('Invalid authentication'));
		}
		if( !$user->published ) {
			throw new ForbiddenException(User::text('User disabled'));
		}
		
		return $this->renderOutput(['accesstoken' => $user->getAccesstoken()]);
	}
}
