<?php

use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\HTTPRoute;
use Orpheus\Rendering\HTMLRendering;
use ShareIt\Controller\Admin\AdminController;
use ShareIt\User;

/**
 * @var string $CONTROLLER_OUTPUT
 * @var HTMLRendering $rendering
 * @var AdminController $Controller
 * @var HTTPRequest $Request
 * @var HTTPRoute $Route
 * @var User $user
 * @var string $Content
 */

/* @var array $Breadcrumb */
/* @var string $PageTitle */
/* @var boolean $noContentTitle */
/* @var string $contentTitle */
/* @var string $contentLegend */
/* @var string $titleRoute */

global $APP_LANG;

$routeName = $Controller->getRouteName();
$user = User::getLoggedUser();

$contentTitle = $Controller->getOption(AdminController::OPTION_CONTENT_TITLE, isset($contentTitle) ? $contentTitle : null);
$contentLegend = $Controller->getOption(AdminController::OPTION_CONTENT_LEGEND);

$invertedStyle = $Controller->getOption('invertedStyle', 1);

$libExtension = DEV_VERSION ? '' : '.min';
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="<?php echo $APP_LANG; ?>" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?php echo $APP_LANG; ?>">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo !empty($PageTitle) ? $PageTitle : t('app_name'); ?></title>
	<meta name="Description" content=""/>
	<meta name="Author" content="<?php echo AUTHORNAME; ?>"/>
	<meta name="application-name" content="<?php _t('app_name'); ?>"/>
	<meta name="msapplication-starturl" content="<?php echo DEFAULTLINK; ?>"/>
	<meta name="Keywords" content="projet"/>
	<meta name="Robots" content="Index, Follow"/>
	<meta name="revisit-after" content="16 days"/>
	<link rel="icon" type="image/png" href="<?php echo STATIC_ASSETS_URL . '/images/icon.png'; ?>"/>
	<?php
	foreach( $rendering->listMetaProperties() as $property => $content ) {
		echo '
	<meta property="' . $property . '" content="' . $content . '"/>';
	}
	?>
	
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap<?php echo $libExtension; ?>.css" media="screen"/>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all<?php echo $libExtension; ?>.css" media="screen"/>
	
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2<?php echo $libExtension; ?>.css" media="screen"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $rendering->getCSSURL(); ?>select2-bootstrap.css" media="screen"/>
	
	<?php
	foreach( $rendering->listCSSURLs(HTMLRendering::LINK_TYPE_PLUGIN) as $url ) {
		echo '
	<link rel="stylesheet" href="' . $url . '" type="text/css" media="screen" />';
	}
	?>
	
	<link rel="stylesheet" href="<?php echo $rendering->getThemeURL(); ?>libs/sb-admin/css/styles.css" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo STATIC_ASSETS_URL . '/style/base.css'; ?>" type="text/css" media="screen"/>
	<link rel="stylesheet" href="<?php echo $rendering->getCSSURL(); ?>style.css" type="text/css" media="screen"/>
	<?php
	foreach( $rendering->listCSSURLs() as $url ) {
		echo '
	<link rel="stylesheet" href="' . $url . '" type="text/css" media="screen" />';
	}
	?>
	
	<!-- External JS libraries -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery<?php echo $libExtension; ?>.js"></script>
</head>
<body class="sb-nav-fixed">

<!-- Sidebar -->
<nav class="sb-topnav navbar navbar-expand <?php echo $invertedStyle ? 'navbar-dark bg-dark' : 'navbar-light bg-light'; ?>" role="navigation">
	<a class="navbar-brand" href="<?php _u(DEFAULT_ROUTE); ?>"><?php _t($Controller->getOption('main_title', 'adminpanel_title')); ?></a>
	<button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
	
	<?php
	if( $user ) {
		?>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
					<a class="dropdown-item" href="<?php _u(ROUTE_ADM_MYSETTINGS); ?>"><?php _t(ROUTE_ADM_MYSETTINGS); ?></a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?php _u(ROUTE_LOGOUT); ?>"><?php _t(ROUTE_LOGOUT); ?></a>
				</div>
			</li>
		</ul>
		<?php
	}
	?>
</nav>

<div id="layoutSidenav">
	<?php
	$this->showMenu($Controller->getOption('mainmenu', 'adminmenu'), 'menu-sidebar');
	?>
	
	<div id="layoutSidenav_content">
		<main>
			<div class="container-fluid">
				
				<div class="row">
					<div class="col-lg-12">
						<?php
						if( $contentTitle !== false ) {
							if( $contentTitle === null ) {
								$contentTitle = t(isset($titleRoute) ? $titleRoute : $routeName);
							}
							if( $contentLegend === null ) {
								$contentLegend = t((isset($titleRoute) ? $titleRoute : $routeName) . '_legend');
							}
							?>
							<h1 class="page-header mt-4">
								<?php echo $contentTitle; ?>
								<small><?php echo $contentLegend; ?></small>
							</h1>
							<?php
						}
						if( !empty($Breadcrumb) ) {
							?>
							<ol class="breadcrumb mb-4">
								<?php
								$bcLast = count($Breadcrumb) - 1;
								foreach( $Breadcrumb as $index => $page ) {
									if( $index >= $bcLast || empty($page->link) ) {
										echo '
						<li class="breadcrumb-item active">' . $page->label . '</li>';
									} else {
										?>
										<li class="breadcrumb-item">
											<a href="<?php echo $page->link; ?>"><?php echo $page->label; ?></a>
										</li>
										<?php
									}
								}
								?>
							</ol>
							<?php
						}
						$rendering->display('reports-bootstrap3');
						?>
					</div>
				</div>
				
				<?php
				echo $CONTROLLER_OUTPUT;
				echo $Content;
				?>
			
			</div>
		</main>
	</div>

</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui<?php echo $libExtension; ?>.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper<?php echo $libExtension; ?>.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap<?php echo $libExtension; ?>.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.full<?php echo $libExtension; ?>.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/i18n/fr.js"></script>

<?php
foreach( $this->listJSURLs(HTMLRendering::LINK_TYPE_PLUGIN) as $url ) {
	echo '
	<script type="text/javascript" src="' . $url . '"></script>';
}
?>

<script src="<?php echo $rendering->getThemeURL(); ?>libs/sb-admin/js/scripts.js"></script>
<script src="<?php echo JSURL; ?>orpheus.js"></script>
<script src="<?php echo JSURL; ?>orpheus-confirmdialog.js"></script>
<script src="<?php echo JSURL; ?>script.js"></script>

<script src="<?php echo $rendering->getJSURL(); ?>orpheus.js"></script>
<script src="<?php echo $rendering->getJSURL(); ?>script.js"></script>
<?php
foreach( $rendering->listJSURLs() as $url ) {
	echo '
	<script type="text/javascript" src="' . $url . '"></script>';
}
?>
</body>
</html>
