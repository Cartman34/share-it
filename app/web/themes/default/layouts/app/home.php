<?php

use Orpheus\InputController\HTTPController\HTTPController;
use Orpheus\InputController\HTTPController\HTTPRequest;
use Orpheus\InputController\HTTPController\HTTPRoute;
use Orpheus\Rendering\HTMLRendering;

/**
 * @var HTMLRendering $rendering
 * @var HTTPController $Controller
 * @var HTTPRequest $Request
 * @var HTTPRoute $Route
 */

$rendering->useLayout('page_skeleton');
?>
<div class="jumbotron">
	<div class="container">
		<h1>Hello PHP Developer !</h1>
		<p>
			Welcome to your own Orpheus application,<br>
			You may want to edit this Controller & View, please see the controller <u>HomeController</u> &amp; the template <u>app/home.php</u>.<br>
			If you want more information, please visit our website.
		</p>
		<p>
			<a class="btn btn-primary btn-lg" href="http://orpheus-framework.com/" role="button" target="_blank">
				Get documented <i class="fas fa-angle-double-right fa-sm"></i>
			</a>
		</p>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<h2>The ElePHPant</h2>
			<p>
				Orpheus is developed using PHP and your app is designed using a PHP-only MVC model. But enjoy our twig library if you want so.
				With all our love of code, we bring lot of tools to help you to develop a great application.
				But the greatest help is from the manual, so abuse about it.
			</p>
			<p>
				<a class="btn btn-secondary" href="https://www.php.net/" role="button" target="_blank">
					PHP Manual <i class="fas fa-angle-double-right fa-sm"></i>
				</a>
			</p>
		</div>
		<div class="col-md-4">
			<h2>Bootstrap your app</h2>
			<p>
				You just started you app and it's already beautiful &amp; responsive.
				Bootstrap is an awesome CSS framework that help you to organize your UI and make it totally responsive.
				Bootstrap is our favorite choice but we advise to use your preferred library ! If you want so, get more details with the documentation.
			</p>
			<p>
				<a class="btn btn-secondary" href="https://getbootstrap.com/" role="button" target="_blank">
					Bootstrap Doc <i class="fas fa-angle-double-right fa-sm"></i>
				</a>
			</p>
		</div>
		<div class="col-md-4">
			<h2>The Awesome Font</h2>
			<p>
				So now, what about icons ? You need it in all apps, even more if it fits your needs, so we added it to you, if you want so.
				We love Font Awesome and we ensure that is making your app more iconic !
				Feel free to read the documentation and browse the gallery.
			</p>
			<p>
				<a class="btn btn-secondary" href="https://fontawesome.com/icons?d=gallery&m=free" role="button" target="_blank">
					Font Awesome Gallery <i class="fas fa-angle-double-right fa-sm"></i>
				</a>
			</p>
		</div>
	</div>
	
	<hr>
</div>
