<?php

namespace Orpheus\Rest\Controller\Api;

use Orpheus\EntityDescriptor\PermanentEntity;
use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\JSONHTTPResponse;
use Orpheus\InputController\OutputResponse;

/**
 * Created by Florent HAZARD on 31/01/2018
 */
class RestListController extends EntityRestController {
	
	/**
	 * Run this controller
	 *
	 * @param HTTPRequest $request
	 * @return OutputResponse|null
	 */
	public function run($request) {
		
		$output = $request->getParameter('output', 'all');
		
		$query = $this->entityService
			->getSelectQuery($request->getParameter('filter'))
			->asObjectList();
		
		$data = [];
		foreach( $query as $item ) {
			/* @var PermanentEntity $item */
			$data[$item->id()] = $this->entityService->extractPublicArray($item, $output);
		}
		
		return new JSONHTTPResponse($data);
	}
}
