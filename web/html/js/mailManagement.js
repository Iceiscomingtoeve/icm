
function setMailReadStatus(mailIds, readStatus) {
	$.ajax({
		url: "/ajax/update-mail/",
		type: "POST",
		data: "mails=" + mailIds + "&read=" + readStatus,
		dataType: "json"
	})
		.done(function (json, textStatus, jqXHR) {
			if (json.state !== "ok") {
				$.Notification.autoHideNotify(
					"error",
					"top right",
					"An error occurred",
					"An unexpected error occurred: " + json.error + "."
				);
			} else {
				$.Notification.autoHideNotify(
					"success",
					"top right",
					"Mail " + (readStatus ? "read" : "unread"),
					""
				);
			}
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			$.Notification.autoHideNotify(
				"error",
				"top right",
				"Could not retrieve mail...",
				"An unexpected error occurred: " + errorThrown + "."
			);
		});
}
