<?php

namespace Pages\Mail\Show\Views;

use EVEOnline\ESI\Mail\MailLabel;
use EVEOnline\ESI\Mail\MailList;
use Pages\Mail\CommonMailView;
use View\DefaultBreadcrumb;
use View\View;

/**
 * Class Success
 *
 * @package Pages\Mail\Show\Views
 */
class Success implements View {

	use DefaultBreadcrumb;

	/** @var MailLabel[] */
	private $mailLabels;

	/** @var MailList[] */
	private $mailsLists;

	/** @var int */
	private $activeLabelId;

	/**
	 * Success constructor.
	 *
	 * @param MailLabel[] $mailLabels
	 * @param MailList[] $mailLists
	 * @param int $activeLabelId label ID which show be active
	 */
	public function __construct(
			array $mailLabels,
			array $mailLists,
			int $activeLabelId
	) {
		$this->mailLabels = $mailLabels;
		$this->mailsLists = $mailLists;
		$this->activeLabelId = $activeLabelId;
	}

	public function getPageTitle() {
		return "Mails";
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
				<a href="javascript:void(0);" class="list-group-item b-0" label-id="<?= $mailLabel->getLabelId(); ?>">
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
				<div class="table-responsive">
					<table id="datatable-mails" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
						<tr>
							<th></th>
							<th>Sender</th>
							<th>Subject</th>
							<th>Time</th>
						</tr>
						</thead>

						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $commonView = new CommonMailView(); $commonView->showTemplate(); ?>
<link href="/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
<link href="/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css">
<!-- DataTable core include -->
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Plugin for DataTables -->
<script src="/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="/plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="/plugins/datatables/jszip.min.js"></script>
<script src="/plugins/datatables/pdfmake.min.js"></script>
<script src="/plugins/datatables/buttons.html5.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var dataTable = $("table#datatable-mails").DataTable({
			// Allows to change the number of entry per page
			lengthChange: true,
			// Add button for export purpose
			buttons: ['copy', 'excel', 'pdf'],
			// Pre-sort on the timestamp
			"order": [[ 3, "desc" ]]
		});
		dataTable.buttons().container().appendTo('#datatable-mails_wrapper .col-md-6:eq(0)');

		// Selecting a label
		$("div.list-group a").click(function() {
			// Remove previous active label
			$("div.list-group").children("a").each(function() {
				$(this).removeClass("active");
			});
			// Declare the clicked one as active
			$(this).addClass("active");

			$(this).append('<i class="wait fa fa-spinner fa-spin"></i>');

			// Call ESI to retrieve mail on the selected label
			$.ajax({
				url: "/ajax/browse-label/",
				type: "POST",
				data: "params=" + $(this).attr("label-id"),
				dataType: "json"
			})
				.done(function(json, textStatus, jqXHR) {
					if (json.state !== "ok") {
						$.Notification.autoHideNotify(
							"error",
							"top right",
							"An error occurred",
							"An unexpected error occurred: " + json.error + "."
						);
						return;
					}

					// Removes previous mails
					dataTable.clear().draw();

					$.each(json.value, function() {
						dataTable.row.add(
							[
								'<div class="checkbox checkbox-primary">\
									<input id="checkbox-' + this.mail_id + '" type="checkbox" mail-id="' + this.mail_id + '">\
									<label for="checkbox-' + this.mail_id + '">' + (this.is_read ? "" : "<span class=\"badge badge-primary pull-right\">NEW</span>") + '</label>\
								</div>',
								'<a href="/mail/read/' + this.mail_id + '">\
									<img src="<?= IMAGE_SERVER_URL; ?>/Character/' + this.from + '_32.jpg" alt="Character" class="rounded-circle">\
									' + this.from_name + '\
								</a>',
								'<a href="/mail/read/' + this.mail_id + '">\
									' + this.subject + '\
								</a>',
								$.format.date(this.timestamp, "yyyy/MM/dd")
							]
						);
					});

					dataTable.columns.adjust().draw();
					$("div.list-group a").find("i.wait").remove();
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					$.Notification.autoHideNotify(
						"error",
						"top right",
						"Could not retrieve mail...",
						"An unexpected error occurred: " + errorThrown + "."
					);

					$("div.list-group a").find("i.wait").remove();
				});
		});

		// Setting mail as read
		$("div.btn-group button.mail-read").click(function() {
			var mailIds = $("div.checkbox input:checked")
				.map(function() { return $(this).attr("mail-id"); })
				.get()
				.join();
			if (mailIds.length > 0) {
				setMailReadStatus(mailIds, true);
			}
		});

		// Setting mail as unread
		$("div.btn-group button.mail-unread").click(function() {
			var mailIds = $("div.checkbox input:checked")
				.map(function() { return $(this).attr("mail-id"); })
				.get()
				.join();
			if (mailIds.length > 0) {
				setMailReadStatus(mailIds, false);
			}
		});

		// Try to find the active label ID or select the first one if any
		var found = false;
		$("div.list-group a").each(function() {
			if (parseInt($(this).attr("label-id"), 10) === <?= $this->activeLabelId; ?>) {
				$(this).trigger("click");
				found = true;
			}
		});

		if (!found) {
			$("div.list-group a").first().trigger("click");
		}
	});
</script>
<?php
	}

}
