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
 */

$rendering->addThemeCSSFile('user_file_list.css');
$rendering->addThemeJSFile('user_file_list.js');
$rendering->useLayout('page_skeleton');
?>
	<form method="POST" class="uploader">
		
		<div class="row">
			<div class="col-lg-12">
				<?php $rendering->useLayout('panel-default'); ?>
				
				<?php
				if( $allowCreate ) {
					?>
					<div class="btn-group mb10" role="group" aria-label="<?php _t('actionsColumn'); ?>">
						<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#AddUserDialog">
							<i class="fa fa-plus"></i> <?php _t('new'); ?>
						</button>
					</div>
					<?php
				}
				?>
				<table class="table table-bordered table-hover">
					<thead>
					<tr>
						<th scope="col"><?php _t('idColumn'); ?></th>
						<th scope="col" title="<?php File::_text('nameHelp'); ?>"><?php File::_text('name'); ?></th>
						<th scope="col"><?php File::_text('preview'); ?></th>
						<th scope="col"><?php _t('actionsColumn'); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach( $query as $file ) {
						?>
						<tr>
							<th scope="row" class="file-id" title="<?php echo d($file->create_date); ?>"><?php echo $file->id(); ?></th>
							<td class="file-name"><?php echo $file->name; ?></td>
							<td><?php
								if( $file->isImage() ) {
									?>
									<a class="picture-preview" href="<?php echo $file->getLink(true); ?>">
										<img src="<?php echo $file->getLink(); ?>" alt="<?php echo $file->name; ?>">
									</a>
									<?php
								} else {
									?>
									<a class="btn btn-info btn-sm" href="<?php echo $file->getLink(true); ?>">
										<i class="fa fa-download"></i>
									</a>
									<?php
								}
								?>
							</td>
							<td>
								<?php
								if( $allowUpdate || $allowDelete ) {
									?>
									<div class="btn-group btn-group-sm" role="group" aria-label="<?php echo t('actionsColumn'); ?>">
										<?php
										if( $allowUpdate ) {
											?>
											<button class="btn btn-outline-secondary action-file-update" type="button">
												<i class="fa fa-edit"></i>
											</button>
											<?php
										}
										if( $allowDelete ) {
											?>
											<button class="btn btn-outline-secondary" type="submit" name="submitDelete[<?php echo $file->id(); ?>]">
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
						<?php
					}
					?>
					</tbody>
				</table>
				
				<?php $rendering->endCurrentLayout(); ?>
			</div>
		</div>
		
		<div class="row mb-3">
			
			<div class="col-12 col-lg-6 upload-file-list-wrapper">
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
						Drag & Drop your files here !
					</div>
				
				</div>
			</div>
		</div>
	
	</form>

<?php
/*
if( $allowCreate ) {
	?>
	<div id="AddUserDialog" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="POST">
					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?php User::_text('addUser'); ?></h4>
					</div>
					<div class="modal-body">
						<p class="help-block"><?php User::_text('addUser_lead'); ?></p>
						<div class="form-group">
							<label><?php User::_text('name'); ?></label>
							<input class="form-control" type="text" name="user[fullname]" <?php echo htmlValue('fullname'); ?>/>
						</div>
						<div class="form-group">
							<label><?php User::_text('email'); ?></label>
							<input class="form-control" type="text" name="user[email]" <?php echo htmlValue('email'); ?> autocomplete="off">
						</div>
						<div class="form-group">
							<label><?php User::_text('password'); ?></label>
							<input class="form-control" type="password" name="user[password]" autocomplete="off">
						</div>
						<div class="form-group">
							<label><?php User::_text('confirmPassword'); ?></label>
							<input class="form-control" type="password" name="user[password_conf]">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php _t('cancel'); ?></button>
						<button name="submitCreate" type="submit" class="btn btn-primary" data-submittext="<?php _t('saving'); ?>"><?php _t('add'); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php
}
*/

