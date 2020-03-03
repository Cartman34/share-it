<?php
/**
 * @author Florent HAZARD <f.hazard@sowapps.com>
 */

namespace ShareIt\Controller\User;

use Orpheus\Exception\UserException;
use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\HTTPResponse;
use ShareIt\File\File;

class UserFileListController extends UserController {
	
	/**
	 * @param HTTPRequest $request The input HTTP request
	 * @return HTTPResponse The output HTTP response
	 */
	public function run($request) {
		
		$this->addThisToBreadcrumb();
		
		try {
			if( $request->hasDataKey('submitDelete', $fileId) ) {
				$file = File::load($fileId, false);
				if( $file->usage !== FILE_USAGE_USER_REPOSITORY ) {
					File::throwException('invalidUsage');
				}
				if( $file->parent_id !== $this->user->id() ) {
					File::throwException('invalidUser');
				}
				$file->remove();
				reportSuccess(File::text('successDelete', $file));
			}
		} catch( UserException $e ) {
			reportError($e);
		}
		
		$query = File::get()
			->where('usage', FILE_USAGE_USER_REPOSITORY)
			->where('parent_id', $this->user)
			->orderby('position DESC, id DESC');
		
		return $this->renderHTML('app/user_file_list', [
			'allowCreate' => true,
			'allowUpdate' => true,
			'allowDelete' => true,
			'query'       => $query,
		]);
	}
	
}
