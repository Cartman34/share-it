<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace ShareIt\Controller\User;

use Orpheus\Exception\UserException;
use Orpheus\File\UploadedFile;
use Orpheus\InputController\HTTPController\HTTPController;
use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\HTTPResponse;
use Orpheus\InputController\HTTPController\JSONHTTPResponse;
use ShareIt\File\File;
use ShareIt\User;

class UserFileUploadController extends HTTPController {
	
	/**
	 * @param HTTPRequest $request The input HTTP request
	 * @return HTTPResponse The output HTTP response
	 */
	public function run($request) {
		$user = User::getLoggedUser();
		/** @var UploadedFile[] $files */
		$files = UploadedFile::load('file');
		if( !$files && $request->isPostSiteOverLimit() ) {
			error_clear_last();
			throw new UserException('Upload over limit');
		}
		$results = [];
		foreach( $files as $uploadedFile ) {
			$result = (object) ['name' => $uploadedFile->getFileName(), 'status' => null];
			try {
				$uploadedFile->validate();
				$file = File::uploadOne($uploadedFile, FILE_USAGE_USER_REPOSITORY, null, $user);
				$result->file = (object) ['id' => $file->id(), 'label' => $file->getLabel(), 'link' => $file->getLink(true)];
				$result->status = 'ok';
			} catch( UserException $e ) {
				$result->status = 'error';
				$result->message = $e->getMessage();
			}
			$results[] = $result;
		}
		return new JSONHTTPResponse($results);
	}
	
}
