<?php

use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\HTTPRoute;
use Orpheus\Rendering\HTMLRendering;
use Orpheus\Rendering\Menu\MenuItem;
use ShareIt\Controller\Admin\AdminController;
use ShareIt\User;

/**
 * @var HTMLRendering $rendering
 * @var AdminController $Controller
 * @var HTTPRequest $Request
 * @var HTTPRoute $Route
 *
 * @var string $menu
 * @var MenuItem[] $items
 */

$invertedStyle = $Controller->getOption('invertedStyle', 1);
$user = User::getLoggedUser();

?>
<div id="layoutSidenav_nav">
	<nav class="sb-sidenav accordion <?php echo $invertedStyle ? 'sb-sidenav-dark' : 'sb-sidenav-light'; ?>" id="sidenavAccordion">
		<div class="sb-sidenav-menu">
			
			<div class="nav menu <?php echo $menu; ?>">
				<div class="sb-sidenav-menu-heading"><?php echo t($menu); ?></div>
				<?php
				foreach( $items as $item ) {
					?>
					<a class="nav-link menu-item<?php echo (isset($item->route) ? ' ' . $item->route : '') . (!empty($item->current) ? ' active' : ''); ?>" href="<?php echo $item->link; ?>">
						<?php echo $item->label; ?>
					</a>
					<?php
				}
				?>
			</div>
		
		</div>
	</nav>
</div>
