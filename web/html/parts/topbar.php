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
			<a href="/" class="logo"><i class="mdi mdi-radar"></i> <span>EVEMyAdmin</span></a>
		</div>
	</div>

	<nav class="navbar-custom">
		<ul class="list-inline float-right mb-0">
			<li class="list-inline-item dropdown notification-list">
				<a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
<?php if ($isLogged && !is_null($mainCharacter)) : ?>
					<img src="<?= IMAGE_SERVER_URL . "/Character/" . $mainCharacter->getCharacterId() . "_32.jpg"; ?>" alt="user" class="rounded-circle">
<?php else : ?>
					<img src="<?= IMAGE_SERVER_URL . "/Type/22208_32.png"; ?>" alt="user" class="rounded-circle">
<?php endif; ?>
				</a>
				<div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
<?php if ($isLogged) : ?>
					<div class="dropdown-item noti-title">
						<h5 class="text-overflow">
							<small>Welcome ! <?= $phpbb->getUser()->data['username']; ?></small>
						</h5>
					</div>

	<?php foreach ($characters as $character) : ?>
		<?php if ($character->getCharacterId() != $mainCharacter->getCharacterId()) : ?>
					<a href="/callback/change-character/<?= $character->getCharacterId(); ?>" class="dropdown-item notify-item">
						<img src="<?= IMAGE_SERVER_URL . "/Character/" . $character->getCharacterId() . "_32.jpg"; ?>" alt="user" class="rounded-circle"> <span><?= $character->getName(); ?></span>
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
			<li class="hide-phone app-search">
				<form role="search" class="">
					<input type="text" placeholder="Search..." class="form-control">
					<a href=""><i class="fa fa-search"></i></a>
				</form>
			</li>
		</ul>
	</nav>
</div>
