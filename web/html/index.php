<?php

// Includes the main function script and set environment
require_once "../inc/bootstrap.inc.php";

// Init the $_SESSION
session_start();

// Include some class
use Dispatcher\ADispatcher;
use Utils\Handler\PhpBB;
use Utils\Utils;

// Links with the phpbb forum
$phpbb = PhpBB::getInstance();

$request = $phpbb->getRequest();
if (strcmp($request->variable("page", "index"), "logout") == 0) {
	$phpbb->logout();
	// Return to the home page
	Utils::redirect("/");
}

$template = ADispatcher::getInstance()->dispatch();
?>
<!DOCTYPE html>
<html lang="fr" class="wide wow-animation">
	<head>
		<title>EVEMyAdmin &bull; Pingouin DTG</title>
		<!-- Les CSS -->
		<link rel="stylesheet" type="text/css" href="/css/id.css">
		<link rel="stylesheet" type="text/css" href="/css/type.css">
		<link rel="stylesheet" type="text/css" href="/css/class.css">

		<link rel="stylesheet" type="text/css" href="/css/jquery/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="/css/jquery/jquery-ui.theme.css">
		<link rel="stylesheet" type="text/css" href="/css/jquery/jquery-ui.structure.css">

		<link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap-theme.css">
		<!-- Les meta -->
		<meta charset="UTF-8">
		<meta name="author" content="Freyers in EVE Online">
		<meta name="description" content="EVE My Admin">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-height, initial-scale=1">
		<!-- jQuery & jQuery UI -->
		<script src="/js/jquery/jquery.js" charset="UTF-8"></script>
		<script src="/js/jquery/jquery-ui.js" charset="UTF-8"></script>
		<!-- BootStrap -->
		<script src="/js/bootstrap/bootstrap.js" charset="UTF-8"></script>
		<!-- L'icone -->
		<link rel="icon" type="ico" href="/favicon.ico">
	</head>

	<body>
		<div id="menu_sup" role="navigation" class="navbar navbar-inverse navbar-static-top">
			<div class="container-fluid">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu_collapse" aria-expanded="false">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<a class="navbar-brand navbar-left" href="/" title="EVEMyAdmin">
					<img src="/img/smiles/blink.gif">
				</a>
				<p class="navbar-text navbar-left">
					<i>ICE is Coming to EVE</i>
				</p>

				<div class="collapse navbar-collapse" id="menu_collapse">
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
							   aria-haspopup="true"
							   aria-expanded="false">
								<span class="glyphicon glyphicon-user"></span> Login <span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="#">Action</a></li>
								<li><a href="#">Another action</a></li>
								<li><a href="#">Something else here</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="#">Separated link</a></li>
							</ul>
						</li>

						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
							   aria-haspopup="true"
							   aria-expanded="false">
								<span class="glyphicon glyphicon-flag"></span> Langue <span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="#"><img class="drapeau" src="/img/design/drapeaux/fr.png"> Français</a></li>
								<li><a href="#"><img class="drapeau" src="/img/design/drapeaux/en.png"> English</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row" style="margin-bottom: 15px;">
				<div class="col-xs-4 col-md-offset-1 col-md-2" style="text-align: center;">
					<div class="metier">
						<img class="hidden-xs img_metier" src="/img/design/metiers_corp/navy.png">
						<span class="titre_metier">Navy</span>
						<span class="directeur_metier">Rommhh</span>
					</div>
				</div>
				<div class="col-xs-4 col-md-offset-2 col-md-2" style="text-align: center;">
					<div class="metier">
						<img class="hidden-xs img_metier" src="/img/design/metiers_corp/logi.png">
						<span class="titre_metier">Logistique</span>
						<span class="directeur_metier">Zephina</span>
					</div>
				</div>
				<div class="col-xs-4 col-md-offset-2 col-md-2" style="text-align: center;">
					<div class="metier">
						<img class="hidden-xs img_metier" src="/img/design/metiers_corp/rh.png">
						<span class="titre_metier">RH</span>
						<span class="directeur_metier">Malory</span>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-2" style="text-align: center;">
					<div class="metier">
						<img class="hidden-xs img_metier" src="/img/design/metiers_corp/indus.png">
						<span class="titre_metier">Indus</span>
						<span class="directeur_metier">Xydon</span>
					</div>
				</div>
				<div class="col-xs-4 col-md-offset-2 col-md-2" style="text-align: center;">
					<div class="metier">
						<img class="hidden-xs img_metier" src="/img/design/metiers_corp/market.png">
						<span class="titre_metier">Market</span>
						<span class="directeur_metier">Popol</span>
					</div>
				</div>
				<div class="col-xs-4 col-md-offset-2 col-md-2" style="text-align: center;">
					<div class="metier">
						<img class="hidden-xs img_metier" src="/img/design/metiers_corp/pos.png">
						<span class="titre_metier">POS</span>
						<span class="directeur_metier">Le meuble</span>
					</div>
				</div>
			</div>

			<hr style="border-color: #000;">

			<div class="row">
				<div class="col-xs-offset-3 col-xs-6" style="text-align: center;">
					<a href="/">Index</a><br>
<?= $template; ?>
				</div>
			</div>

			<hr style="border-color: #000;">

			<div class="row" style="background-color: #CDCDCD;">
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
				<div class="col-xs-1" style="border: 1px solid black; text-align: center;">1</div>
			</div>
			<div class="row" style="background-color: #CDCDCD;">
				<div class="col-xs-2" style="border: 1px solid black; text-align: center;">2</div>
				<div class="col-xs-2" style="border: 1px solid black; text-align: center;">2</div>
				<div class="col-xs-2" style="border: 1px solid black; text-align: center;">2</div>
				<div class="col-xs-2" style="border: 1px solid black; text-align: center;">2</div>
				<div class="col-xs-2" style="border: 1px solid black; text-align: center;">2</div>
				<div class="col-xs-2" style="border: 1px solid black; text-align: center;">2</div>
			</div>
			<div class="row" style="background-color: #CDCDCD;">
				<div class="col-xs-3" style="border: 1px solid black; text-align: center;">3</div>
				<div class="col-xs-3" style="border: 1px solid black; text-align: center;">3</div>
				<div class="col-xs-3" style="border: 1px solid black; text-align: center;">3</div>
				<div class="col-xs-3" style="border: 1px solid black; text-align: center;">3</div>
			</div>
			<div class="row" style="background-color: #CDCDCD;">
				<div class="col-xs-4" style="border: 1px solid black; text-align: center;">4</div>
				<div class="col-xs-4" style="border: 1px solid black; text-align: center;">4</div>
				<div class="col-xs-4" style="border: 1px solid black; text-align: center;">4</div>
			</div>
			<div class="row" style="background-color: #CDCDCD;">
				<div class="col-xs-5" style="border: 1px solid black; text-align: center;">5</div>
				<div class="col-xs-2" style="border: 1px solid black; text-align: center;">2</div>
				<div class="col-xs-5" style="border: 1px solid black; text-align: center;">5</div>
			</div>
			<div class="row" style="background-color: #CDCDCD;">
				<div class="col-xs-6" style="border: 1px solid black; text-align: center;">6</div>
				<div class="col-xs-6" style="border: 1px solid black; text-align: center;">6</div>
			</div>

			<div id="pied_de_page" class="row">
				<div class="col-xs-12">
					Copyright <abbr title="EMA">EVEMyAdmin</abbr> <b>0.1b</b> © 2015<br>
					<a href="http://www.eveonline.com" target="_blank">EVE Online</a>, EVE and all associated logos and
					designs are the intellectual property of <a href="http://www.ccpgames.com" target="_blank">CCP hf</a>.
				</div>
			</div>
		</div>
	</body>
</html>
