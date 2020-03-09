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
		<h1>Welcome to Share It !</h1>
		<p>
			Share It is an Open Source solution to share private files,<br>
			Drag it to your file space and you immediately got a link to share it !<br>
		</p>
		<p>
			<a class="btn btn-primary btn-lg" href="https://github.com/Sowapps/share-it" role="button" target="_blank">
				Share It GitHub <i class="fab fa-github fa-sm"></i>
			</a>
		</p>
	</div>
</div>
<div class="container">
	<div class="row">
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
			<h2>Orpheus Framework</h2>
			<p>
				Share It is developed using the Orpheus PHP Framework.
				This one is another sowapps's Open Source Solution we bring to the community to help them to develop quickly brand new web applications.
			</p>
			<p>
				<a class="btn btn-secondary" href="http://orpheus-framework.com/" role="button" target="_blank">
					Orpheus Website <i class="fas fa-angle-double-right fa-sm"></i>
				</a>
			</p>
		</div>
		<div class="col-md-4">
			<h2>Sowapps</h2>
			<p>
				Sowapps is the IT company that initiated Share It and Orpheus projects.
				Sowapps intents to help all web developers bring online proper web application quickly.
				Florent HAZARD is the founder of Sowapps Company.
			</p>
			<p>
				<a class="btn btn-secondary" href="http://sowapps.com/" role="button" target="_blank">
					Sowapps Website <i class="fas fa-angle-double-right fa-sm"></i>
				</a>
			</p>
		</div>
	</div>
	
	<hr>
</div>
