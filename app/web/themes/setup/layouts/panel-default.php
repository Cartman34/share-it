<?php

use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\HTTPRoute;
use Orpheus\Rendering\HTMLRendering;
use Sowapps\Controller\Admin\AdminController;

/**
 * @var string $CONTROLLER_OUTPUT
 * @var HTMLRendering $rendering
 * @var AdminController $Controller
 * @var HTTPRequest $Request
 * @var HTTPRoute $Route
 *
 * @var string $Content
 */

if( !isset($title) ) {
	$title = '';
}

if( !isset($menu) ) {
	$menu = [];
}

if( !isset($titleClass) ) {
	$titleClass = '';
}

if( !isset($footer) ) {
	$footer = null;
}

if( !isset($panelClass) ) {
	$panelClass = '';
}

if( !isset($bodyClass) ) {
	$bodyClass = '';
}

if( !isset($body) ) {
	$body = $Content;
}

?>
<div class="card mb-4 <?php echo $panelClass; ?>">
	<?php
	if( $title || $menu ) {
		?>
		<div class="card-header <?php echo $titleClass; ?>">
			<?php
			if( $menu ) {
				?>
				<ul class="nav nav-tabs card-header-tabs">
					<?php
					if( $title ) {
						?>
						<li class="nav-item">
							<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">
								<?php echo $title; ?>
							</a>
						</li>
						<?php
					}
					if( !isset($menuActiveItem) ) {
						$menuActiveItem = null;
					}
					foreach( $menu as $itemKey => $item ) {
						$item = (object) $item;
						/**
						 * Item contains:
						 * - link
						 * - label
						 */
						$isActive = $itemKey === $menuActiveItem;
						?>
						<li class="nav-item <?php echo $itemKey; ?>">
							<a class="nav-link<?php echo $isActive ? ' active' : ''; ?>" href="<?php echo $item->link; ?>"><?php echo $item->label; ?></a>
						</li>
						<?php
					}
					?>
				</ul>
				<?php
			} else {
				echo $title;
			}
			?>
		</div>
		<?php
	}
	?>
	<div class="card-body <?php echo $bodyClass; ?>">
		<?php echo $body; ?>
	</div>
	<?php
	if( $footer ) {
		?>
		<div class="card-footer text-right">
			<?php echo $footer; ?>
		</div>
		<?php
	}
	?>
</div>
