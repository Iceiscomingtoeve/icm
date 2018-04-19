<?php

namespace View\Index\Channels;

use EVEOnline\ESI\Character\Channel;
use View\View;

/**
 * Class Success for the show in Index controller
 *
 * @package View\Index\Show
 */
class Success implements View {

	/** @var Channel[] $characters  */
	private $channels;

	/**
	 * Success constructor.
	 *
	 * @param Channel[] $channels
	 */
	public function __construct(
		$channels
	) {
		$this->channels = $channels;
	}

	public function showTemplate() {
?>
Vous avez synchornisé <?= \Utils\Utils::plural(count($this->channels), "channel"); ?>:<br>
<?php foreach ($this->channels as $channel) : ?>
	<?= debug($channel); ?>
<?php endforeach; ?>
<?php
	}
}