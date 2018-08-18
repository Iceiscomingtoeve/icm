<?php

namespace Pages\Mail\Read\Views;

use EVEOnline\ESI\Mail\MailBody;
use EVEOnline\ESI\Mail\MailLabel;
use EVEOnline\ESI\Mail\MailList;
use Pages\Mail\CommonMailView;
use View\DefaultBreadcrumb;
use View\View;

/**
 * Class Success
 *
 * @package Pages\Mail\Read\Views
 */
class Success implements View {

	use DefaultBreadcrumb;

	/** @var MailLabel[] */
	private $mailLabels;

	/** @var MailList[] */
	private $mailsLists;

	/** @var MailBody */
	private $mailBody;

	/** @var bool */
	private $isOwnCharacter;

	/**
	 * Success constructor.
	 *
	 * @param MailLabel[] $mailLabels
	 * @param MailList[] $mailLists
	 * @param MailBody $mailBody
	 * @param bool $isOwnCharacter
	 */
	public function __construct(
		array $mailLabels,
		array $mailLists,
		MailBody $mailBody,
		bool $isOwnCharacter
	) {
		$this->mailLabels = $mailLabels;
		$this->mailsLists = $mailLists;
		$this->mailBody = $mailBody;
		$this->isOwnCharacter = $isOwnCharacter;
	}

	public function getPageTitle() {
		return "Reading Mail";
	}

	private function getIconFromLabelType(MailLabel $mailLabel) {
		if (stristr("Inbox", $mailLabel->getName())) {
			return "fa fa-inbox";
		} else if (stristr("Sent", $mailLabel->getName())) {
			return "fa fa-send";
		} else if (stripos($mailLabel->getName(), "Corp") !== false) {
			return "fa fa-star";
		} else if (stripos($mailLabel->getName(), "Alliance") !== false) {
			return "fa fa-star-o";
		}
		return "mdi mdi-folder";
	}

	public function showTemplate() {
?>
<div class="row">
	<div class="col-xl-2 col-lg-3">
		<div class="p-20">
			<a href="javascript:void(0);" class="btn btn-danger btn-rounded btn-custom btn-block waves-effect waves-light disabled">Compose</a>

			<div class="list-group mail-list m-t-20">
<?php foreach ($this->mailLabels as $mailLabel) : ?>
				<a href="/mail/show/<?= $mailLabel->getLabelId(); ?>" class="list-group-item b-0<?= in_array($mailLabel->getLabelId(), $this->mailBody->getLabels()) ? " active" : ""; ?>"">
					<i class="<?= $this->getIconFromLabelType($mailLabel); ?> m-r-5" style="color: <?= $mailLabel->getColor(); ?>"></i>
					<?= $mailLabel->getName(); ?>
	<?php if ($mailLabel->getUnreadCount() > 0) : ?>
					<b class="ml-1">(<?= $mailLabel->getUnreadCount(); ?>)</b>
	<?php endif; ?>
				</a>
<?php endforeach; ?>
			</div>

			<h4 class="font-18 m-t-40">Mailing Lists</h4>
			<div class="list-group b-0 mail-list">
<?php foreach ($this->mailsLists as $mailList) : ?>
				<a href="javascript:void(0);" class="list-group-item b-0"><?= $mailList->getName(); ?></a>
<?php endforeach; ?>
			</div>
		</div>
	</div>

	<div class="col-xl-10 col-lg-9">
		<div class="row">
			<div class="col-lg-12">
				<div class="btn-toolbar m-t-20" role="toolbar">
					<div class="btn-group">
						<button type="button" class="btn btn-primary waves-effect waves-light mail-read" title="Mark as read"><i class="mdi mdi-email-open"></i></button>
						<button type="button" class="btn btn-primary waves-effect waves-light mail-unread" title="Mark as unread"><i class="mdi mdi-email"></i></button>
						<button type="button" class="btn btn-primary waves-effect waves-light mail-delete disabled" title="Delete"><i class="fa fa-trash-o"></i></button>
					</div>
				</div>
			</div>
		</div>

		<div class="card-box p-1 m-t-20">
			<div class="panel-body p-0">
				<h2><?= $this->mailBody->getSubject(); ?></h2>
				<hr>
				<p>
					From: <?= $this->mailBody->getFromName(); ?><br>
					Sent: <?= date("Y.m.d H:i", $this->mailBody->getTime()->getTimestamp()); ?><br>
					To: <?= implode(", ", $this->mailBody->getRecipients()); ?><br>
					<br>
					<?= $this->mailBody->getBody(); ?>
				</p>
			</div>
		</div>
	</div>
</div>

<?php $commonView = new CommonMailView(); $commonView->showTemplate(); ?>
<?php if ($this->isOwnCharacter) : ?>
<script type="text/javascript">
	var isMailRead = <?= $this->mailBody->isRead() ? "true" : "false"; ?>;

	$(document).ready(function() {
		$("div.btn-group button.mail-read").click(function() {
			setMailReadStatus(<?= $this->mailBody->getMailId(); ?>, true);
		});

		$("div.btn-group button.mail-unread").click(function() {
			setMailReadStatus(<?= $this->mailBody->getMailId(); ?>, false);
		});

		// Set the mail as read after 5 seconds
		if (!isMailRead) {
			setTimeout(function() { setMailReadStatus(<?= $this->mailBody->getMailId(); ?>, true); }, 5000);
		}
	});
</script>
<?php endif; ?>
<?php
	}

}
