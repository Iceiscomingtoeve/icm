<?php
/** @var \Utils\Handler\PhpBB $phpbb */
global $phpbb;

$session = \Model\Bean\UserSession::getSession();
$isLogged = !$phpbb->isAnonymous();
$characters = $session->getEVECharacters();
$mainCharacter = $session->getActiveCharacter();
?>

<div class="topbar">
	<div class="topbar-left">
		<div class="text-center">
			<a href="/" class="logo">
				<img src="<?= \Utils\Builder\EVEImage::getCorporationImage(CORPORATION_ID, 64); ?>" alt="logo"><span>EVEMyAdmin</span>
			</a>
		</div>
	</div>

	<nav class="navbar-custom">
		<ul class="list-inline float-right mb-0">
			<li class="list-inline-item notification-list">
				<a href="javascript:void(0);" class="nav-link waves-effect waves-light esi-status">
					<i class="fa fa-refresh"></i> ESI status
				</a>
			</li>

			<li class="list-inline-item dropdown notification-list">
				<a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
<?php if ($isLogged && !is_null($mainCharacter)) : ?>
					<img src="<?= \Utils\Builder\EVEImage::getCharacterImage($mainCharacter->getCharacterId(), 32); ?>" alt="user" class="rounded-circle">
<?php else : ?>
					<img src="<?= \Utils\Builder\EVEImage::getItemImage(22208, 32); ?>" alt="user" class="rounded-circle">
<?php endif; ?>
				</a>
				<div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
<?php if ($isLogged) : ?>
					<div class="dropdown-item noti-title">
						<h5 class="text-overflow">
							<small>Welcome <?= $phpbb->getUser()->data['username']; ?> !</small>
						</h5>
					</div>

					<a href="<?= OAUTH_LOGIN_URL ?>" class="dropdown-item notify-item">
						<img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-small.png" alt="EVE SSO login">
					</a>

					<!-- separator -->
					<hr>

	<?php foreach ($characters as $character) : ?>
		<?php if ($character->getCharacterId() != $mainCharacter->getCharacterId()) : ?>
					<a href="/callback/change-character/<?= $character->getCharacterId(); ?>" class="dropdown-item notify-item">
						<img src="<?= \Utils\Builder\EVEImage::getCharacterImage($character->getCharacterId(), 32); ?>" alt="user" class="rounded-circle"> <span><?= $character->getName(); ?></span>
					</a>
		<?php endif; ?>
	<?php endforeach; ?>

					<!-- separator -->
					<hr>

					<a href="<?= PHPBB_URL; ?>" class="dropdown-item notify-item">
						<i class="mdi mdi-comment-multiple-outline"></i> <span>Forum</span>
					</a>

					<a href="javascript:void(0);" class="dropdown-item notify-item">
						<i class="mdi mdi-settings"></i> <span>Settings</span>
					</a>

					<a href="/logout" class="dropdown-item notify-item">
						<i class="mdi mdi-logout"></i> <span>Logout</span>
					</a>
<?php else : ?>
					<div class="dropdown-item noti-title">
						<h5 class="text-overflow">
							<small>Please connect to the forum</small>
						</h5>
					</div>

					<a href="<?= PHPBB_URL . "/ucp.php?mode=login"; ?>" class="dropdown-item notify-item">
						<i class="mdi mdi-login"></i> <span>Login</span>
					</a>
<?php endif; ?>
				</div>
			</li>
		</ul>

		<ul class="list-inline menu-left mb-0">
			<li class="float-left">
				<button class="button-menu-mobile open-left waves-light waves-effect">
					<i class="mdi mdi-menu"></i>
				</button>
			</li>
		</ul>
	</nav>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		function setIconAccordingToStatus(ratioGreen) {
			var status;
			if (ratioGreen <= 0) {
				status = "-outline";
			} else if (ratioGreen <= 20) {
				status = "-10";
			} else if (ratioGreen <= 30) {
				status = "-20";
			} else if (ratioGreen <= 40) {
				status = "-30";
			} else if (ratioGreen <= 50) {
				status = "-40";
			} else if (ratioGreen <= 60) {
				status = "-50";
			} else if (ratioGreen <= 70) {
				status = "-60";
			} else if (ratioGreen <= 80) {
				status = "-70";
			} else if (ratioGreen <= 90) {
				status = "-80";
			} else if (ratioGreen < 100) {
				status = "-90";
			} else {
				status = "";
			}
			setEsiStatus("mdi mdi-battery" + status);
		}

		function setEsiStatus(clazz) {
			var iconEsiStatus = $("a.esi-status i");
			iconEsiStatus.removeClass();
			iconEsiStatus.addClass(clazz);
		}

		function getEsiStatus() {
			// If it's already refreshing the status
			if ($("a.esi-status i").hasClass("fa-spin")) {
				return;
			}
			setEsiStatus("fa fa-refresh fa-spin");

			$.ajax({
				// JSON status of the ESI
				url: "<?= ESI_BASE_URL; ?>/status.json?version=latest",
				type: "GET",
				dataType: "json",
				crossDomain: true
			})
				.done(function(json, textStatus, jqXHR) {
					var nbEndPoint = 0;
					var nbStatusGreen = 0;
					$.each(json, function() {
						nbEndPoint++;
						if (this.status === "green") {
							nbStatusGreen++;
						}
					});
					setIconAccordingToStatus(nbStatusGreen / nbEndPoint * 100);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					setEsiStatus("mdi mdi-battery-unknown");
				});
		}

		// Call the ESI status every 5 seconds
		setInterval(getEsiStatus, 5000);

		$("a.esi-status").click(getEsiStatus);
	});
</script>
