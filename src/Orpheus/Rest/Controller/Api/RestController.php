<?php

namespace Orpheus\Rest\Controller\Api;

use Orpheus\EntityDescriptor\User\AbstractUser;
use Orpheus\InputController\HTTPController\HTTPController;
use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\JSONHTTPResponse;
use User;

/**
 * Created by Florent HAZARD on 04/02/2018
 */
abstract class RestController extends HTTPController {
	
	const HEADER_AUTHORIZATION = 'Authorization';
	const HEADER_ALT_AUTHORIZATION = 'X-Auth';
	
	protected $user;
	
	/**
	 * @param HTTPRequest $request
	 * @return null
	 */
	public function preRun($request) {
		
		// Authenticated user
		$headers = $request->getHeaders();
		if( !empty($headers[self::HEADER_ALT_AUTHORIZATION]) || !empty($headers[self::HEADER_AUTHORIZATION]) ) {
			$authHeader = !empty($headers[self::HEADER_ALT_AUTHORIZATION]) ? $headers[self::HEADER_ALT_AUTHORIZATION] : $headers[self::HEADER_AUTHORIZATION];
			[, $token] = explodeList(' ', $authHeader, 2);
			$this->user = User::getByAccessToken($token);
			// Compatibility with all user system
			if( $this->user ) {
				$this->user->login(true);
			}
		} else {
			// Classic Web authentication
			$this->user = AbstractUser::getLoggedUser();
		}
		
		return null;
	}
	
	public function renderOutput($data) {
		return new JSONHTTPResponse($data);
	}
}
