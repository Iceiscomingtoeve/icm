<?php

// Includes the main function script and set environment
require_once "../inc/bootstrap.inc.php";

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

$view = ADispatcher::getInstance()->dispatch();
?>
<!DOCTYPE html>
<html lang="fr" class="wide wow-animation">
	<head>
		<!-- Les meta -->
		<meta charset="UTF-8">
		<meta name="author" content="Freyers in EVE Online">
		<meta name="description" content="EVE My Admin">
		<meta name="keywords" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="shortcut icon" type="ico" href="/favicon.ico">

		<title>EVEMyAdmin &bull; Pingouin DTG</title>

		<link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="/css/icons.css" rel="stylesheet" type="text/css">
		<link href="/css/style.css" rel="stylesheet" type="text/css">
		<link href="/plugins/switchery/switchery.min.css" rel="stylesheet">

		<script src="/js/modernizr.min.js"></script>
		<script src="/js/jquery.min.js"></script>
		<script src="/plugins/dateformat/dateFormat.min.js"></script>
	</head>

	<body class="fixed-left">
		<div id="wrapper">
<?php
// Main topbar
require_once "parts/topbar.php";

// Left menu
require_once "parts/left-menu.php";
?>
			<div class="content-page">
				<div class="content">
					<div class="container-fluid">
						<div class="row">
							<div class="col-sm-12">
								<div class="page-title-box">
									<h4 class="page-title"><?= $view->getPageTitle(); ?></h4>
									<?= $view->getBreadcrumb(); ?>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-12">
<?= $view->showTemplate(); ?>
							</div>
						</div>

					</div>
				</div>
				<footer class="footer">
					2018 © EVEMyAdmin <span class="hide-phone">- <?= DOMAIN; ?></span>
				</footer>
			</div>
<?php
// Right menu
require_once "parts/right-menu.php";
?>
		</div>

		<script>
			var resizefunc = [];
		</script>
		<script src="/js/popper.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/detect.js"></script>
		<script src="/js/fastclick.js"></script>
		<script src="/js/jquery.slimscroll.js"></script>
		<script src="/js/jquery.blockUI.js"></script>
		<script src="/js/waves.js"></script>
		<script src="/js/wow.min.js"></script>
		<script src="/js/jquery.nicescroll.js"></script>
		<script src="/js/jquery.scrollTo.min.js"></script>
		<script src="/plugins/switchery/switchery.min.js"></script>
		<script src="/js/jquery.core.js"></script>
		<script src="/js/jquery.app.js"></script>
	</body>
</html>
