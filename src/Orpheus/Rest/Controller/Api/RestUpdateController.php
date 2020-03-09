<?php

namespace Orpheus\Rest\Controller\Api;

use Orpheus\InputController\HTTPController\JSONHTTPResponse;
use Orpheus\InputController\InputRequest;
use Orpheus\InputController\OutputResponse;

/**
 * Created by Florent HAZARD on 11/02/2018
 */
class RestUpdateController extends EntityRestController {
	
	/**
	 * Run this controller
	 *
	 * @param InputRequest $request
	 * @return OutputResponse|null
	 */
	public function run($request) {
		
		$input = $request->getInput();
		
		$this->entityService->updateItem($this->item, $input);
		
		$data = $this->entityService->extractPublicArray($this->item);
		
		return new JSONHTTPResponse($data);
	}
}
