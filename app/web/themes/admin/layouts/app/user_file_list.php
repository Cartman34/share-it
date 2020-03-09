<?php

use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\HTTPRoute;
use Orpheus\Rendering\HTMLRendering;
use Orpheus\SQLRequest\SQLSelectRequest;
use ShareIt\Controller\User\UserController;
use ShareIt\File\File;

/**
 * @var HTMLRendering $rendering
 * @var UserController $Controller
 * @var HTTPRequest $Request
 * @var HTTPRoute $Route
 *
 * @var boolean $allowCreate
 * @var boolean $allowUpdate
 * @var boolean $allowDelete
 * @var SQLSelectRequest $query
 * // * @var string[] $pendingFiles
 */

$rendering->addThemeCSSFile('user_file_list.css');
$rendering->addThemeJSFile('user_file/user_file_list.js');
if( $allowCreate ) {
	$rendering->addThemeJSFile('user_file/user_file_upload.js');
}
$rendering->useLayout('page_skeleton');
?>
<div class="uploader">
	<div class="row">
		<div class="col-lg-8">
			<form method="POST">
				<?php $rendering->useLayout('panel-default'); ?>
				
				<table class="table table-bordered table-hover">
					<thead>
					<tr>
						<th scope="col"><?php _t('idColumn'); ?></th>
						<th scope="col"><?php File::_text('name'); ?></th>
						<th scope="col"><?php File::_text('download'); ?></th>
						<th scope="col"><?php _t('actionsColumn'); ?></th>
					</tr>
					</thead>
					<tbody id="ListFile">
					<tr class="loading">
						<td colspan="99" class="text-center py-3">
							<div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
								<span class="sr-only">Loading...</span>
							</div>
						</td>
					</tr>
					<template id="ModelFileListItem">
						<tr class="file_list_item">
							<th scope="row" class="file_id" title="{{ create_date }}"></th>
							<td class="file_label"></td>
							<td>
								<a data-if="is_image" class="picture-preview" href="{{ download_link }}">
									<img src="{{ link }}" alt="{{ label }}">
								</a>
								<a data-if="not is_image" class="btn btn-info btn-sm" href="{{ download_link }}">
									<i class="fa fa-download"></i>
								</a>
							</td>
							<td>
								<?php
								if( $allowUpdate || $allowDelete ) {
									?>
									<div class="btn-group btn-group-sm" role="group" aria-label="<?php echo t('actionsColumn'); ?>">
										<?php
										if( $allowUpdate ) {
											?>
											<button class="btn btn-outline-secondary action-update" type="button">
												<i class="fa fa-edit"></i>
											</button>
											<?php
										}
										if( $allowDelete ) {
											?>
											<button class="btn btn-outline-secondary action-delete" type="button">
												<i class="fa fa-times"></i>
											</button>
											<?php
										}
										?>
									</div>
									<?php
								}
								?>
							</td>
						</tr>
					</template>
					</tbody>
				</table>
				
				<?php $rendering->endCurrentLayout(['title' => 'My Files']); ?>
			
			</form>
		</div>
		
		<?php /*
		<div class="col-lg-4">
			<form method="POST">
				<?php $rendering->useLayout('panel-default'); ?>
				
				<div class="form-group">
					<label>My Repository Path</label>
					<input type="text" readonly class="form-control-plaintext" value="<?php echo $user->getRepositoryPath(); ?>">
				</div>
				
				<?php $rendering->endCurrentLayout(['title' => 'FTP Access']); ?>
			
			</form>
		</div>
 */ ?>
	</div>
	
	<?php
	if( $allowCreate ) {
		?>
		<div class="row mb-3">
			<div class="col-lg-8">
				
				<div class="row mb-3">
					
					<div class="col-12 col-lg-8 upload-file-list-wrapper">
						<ul class="list-group upload-file-list">
							<li class="list-group-item group-title">
								<div class="d-flex w-100 justify-content-between">
									<h5 class="mb-0">Uploading files</h5>
									<small><span class="counter">3</span> uploads</small>
								</div>
							</li>
						</ul>
					</div>
					
					<div class="col">
						<div class="drop-zone">
							
							<div class="drop-zone-message d-flex flex-column py-5">
								<i class="fas fa-cloud-upload-alt fa-2x"></i>
								Drag &amp; Drop your files here !
							</div>
						
						</div>
					</div>
				</div>
			
			</div>
		</div>
		<?php
	}
	?>
</div>

<div id="DialogFileEdit" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST">
				<div class="modal-header">
					<h4 class="modal-title">Edit file</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="<?php _t('close'); ?>"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="system-state">
					</div>
					<div class="form-group">
						<label for="InputFileName">File Name</label>
						<input name="name" type="text" class="form-control file_name" id="InputFileName" required="required">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><?php _t('cancel'); ?></button>
					<button type="button" class="btn btn-primary action-edit-submit"><?php _t('edit'); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="DialogFileDelete" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST">
				<div class="modal-header">
					<h4 class="modal-title">Suppress file ?</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="<?php _t('close'); ?>"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="system-state">
					</div>
					<p>
						Are you sure you want to delete the file <span class="file_label text-break"></span> from your repository ?
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><?php _t('cancel'); ?></button>
					<button type="button" class="btn btn-primary action-delete-submit"><?php _t('_yes'); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

