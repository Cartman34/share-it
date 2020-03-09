<?php

namespace ShareIt\Controller\Developer;

use Orpheus\Exception\UserException;
use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\HTTPResponse;

class DevComposerController extends DevController {
	
	/**
	 * @param HTTPRequest $request The input HTTP request
	 * @return HTTPResponse The output HTTP response
	 */
	public function run($request) {
		
		define('DOMAIN_COMPOSER', 'composer');
		defifn('COMPOSER_HOME', INSTANCEPATH . '.composer');
		// 		define('COMPOSER_HOME', APPLICATIONPATH.'.composer');
		
		$composerFile = APPLICATIONPATH . 'composer.json';
		
		if( !file_exists($composerFile) ) {
			throw new UserException('Unable to find composer.json file');
		}
		
		try {
			if( !is_writable($composerFile) ) {
				reportWarning('composerFileNotWritable', DOMAIN_COMPOSER);
				// 				throw new UserException('composerFileNotWritable');
			}
			if( !is_writable(COMPOSER_HOME) ) {
				reportWarning('composerHomeNotWritable', DOMAIN_COMPOSER);
			}
			
			// Always save data
			if( ($data = $request->getArrayData('composer')) && is_array($data) ) {
				$composerConfig = json_decode(file_get_contents($composerFile));
				
				foreach( $data as $property => $value ) {
					$composerConfig->$property = $value;
				}
				if( !empty($composerConfig->authors) ) {
					$composerConfig->authors = json_decode($composerConfig->authors);
				} else {
					unset($composerConfig->authors);
				}
				if( !empty($composerConfig->require) ) {
					$composerConfig->require = json_decode($composerConfig->require);
				} else {
					unset($composerConfig->require);
				}
				
				file_put_contents($composerFile, json_encode($composerConfig));
				
			}
			unset($data);
			
			if( $request->hasData('submitUpdateInstall') ) {
				
				$command = $request->hasData('update/refresh') ? 'update' : 'install';
				$devOpt = $request->hasData('update/withdev') ? '' : '--no-dev';
				// --dev is deprecated, this is default
				// 				$devOpt		= $request->hasData('update/withdev') ? '--dev' : '--no-dev';
				$optiOpt = $request->hasData('update/optimize') ? '--optimize-autoloader' : '';
				
				putenv('COMPOSER_HOME=' . COMPOSER_HOME);
				
				$cmd = 'cd "' . APPLICATIONPATH . '"; php composer.phar ' . $command . ' ' . $devOpt . ' ' . $optiOpt . ' 2>&1';
				
				ob_start();
				$return = null;
				system($cmd, $return);
				$output = ob_get_clean();
				
				reportInfo(nl2br(t('outputLog', DOMAIN_COMPOSER, $cmd, $output)));
				
				if( $return ) {
					throw new UserException('updateFailed');
				}
				reportSuccess('successUpdateInstall', DOMAIN_COMPOSER);
			}
		} catch( UserException $e ) {
			reportError($e, DOMAIN_COMPOSER);
		}
		$composerConfig = json_decode(file_get_contents($composerFile));
		
		$this->addThisToBreadcrumb();
		
		return $this->renderHTML('developer/dev_composer', [
			'composerConfig'    => $composerConfig,
			'applicationFolder' => APPLICATIONPATH,
			'composerFile'      => $composerFile,
			'composerHome'      => COMPOSER_HOME,
		]);
	}
	
}
