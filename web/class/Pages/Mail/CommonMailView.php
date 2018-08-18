<?php

namespace Pages\Mail;

use View\EmptyBreadcrumb;
use View\EmptyPageTitle;
use View\View;

/**
 * Class CommonMailView
 *
 * @package Pages\Mail
 */
class CommonMailView implements View {

	use EmptyPageTitle;
	use EmptyBreadcrumb;

	public function showTemplate() {
?>
<!-- Notifications plugin -->
<script src="/plugins/notifyjs/dist/notify.min.js"></script>
<script src="/plugins/notifications/notify-metro.js"></script>
<script src="/js/mailManagement.js"></script>
<?php
	}

}
