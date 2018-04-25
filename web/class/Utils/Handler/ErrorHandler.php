<?php

namespace Utils\Handler;

use phpbb\request\request_interface;
use Utils\Utils;

/**
 * Error Handler.
 *
 * @see set_error_handler()
 */
final class ErrorHandler implements Handler {

	/**
	 * Register the custom error handler
	 */
	public static function register() {
		set_error_handler(__CLASS__ . "::log");
		set_exception_handler(__CLASS__ . "::logException");
	}

	/**
	 * Logs the message/error and send a debug mail to MAIL_DEVELOPER if not a E_NOTICE.
	 *
	 * @param int $number error number
	 * @param string $message error message
	 * @param string $file file path
	 * @param int $line line in the file
	 * @throws \ErrorException
	 */
	public static function log(
			int $number,
			string $message,
			string $file,
			int $line
	) {
		// Silent operator (@) activated ?
		if (error_reporting() > 0) {
			throw new \ErrorException($message, $number, $number, $file, $line);
		}
	}

	/**
	 * Logs the Exception and send a debug mail to MAIL_DEVELOPER if not an INFO.
	 *
	 * @param \Throwable $throwable the exception thrown
	 */
	public static function logException(\Throwable $throwable) {
		ob_start(
			!in_array("ob_gzhandler", ob_list_handlers()) ?
				"ob_gzhandler" : NULL
		);
		self::dumpException($throwable);
		$dump = ob_get_clean();
		ob_end_flush();

		$request = PhpBB::getInstance()->getRequest();
		// Retrieves the requested URL from $_SERVER through \phpbb\request\request
		if (strcmp(self::getServerVariable($request, "HTTP_REFERER", "%%WRONG_URL%%"), "%%WRONG_URL%%") == 0) {
			$url = self::getServerVariable($request, "REQUEST_SCHEME", "http") . "://" .
				self::getServerVariable($request, "HTTP_HOST", DOMAIN) .
				self::getServerVariable(
					$request,
					"REQUEST_URI",
					self::getServerVariable(
						$request,
						"REDIRECT_URL",
						self::getServerVariable(
							$request,
							"REDIRECT_QUERY_STRING"
						)
					)
				);
		} else {
			$url = self::getServerVariable($request, "HTTP_REFERER", "Unknown URL");
		}

		$number = $throwable->getCode();
		$prefix = "[" . Utils::dateJJ_MM_AAAA(true, time()) . "] ";
		$message = $prefix . "URL: " . $url . "\n";
		$message .= $prefix . "Erreur: " . self::getPhpErrorFromNumber($number) . " (" . $number . ")" . "\n";
		$message .= $prefix . "Message: " . $throwable->getMessage() . "\n";
		$message .= $prefix . "Stack trace:" . "\n";
		$message .= Utils::callStack(false);
		// Save the error in the file
		//TODO: Rotate to have 1 file per error to have more visibility
		Utils::log($message . str_repeat("=", 60));

		// If it's in debug purpose, print the error directly
		if (ini_get('display_errors')) {
			echo $dump;
		}
		Utils::sendMail(
			"EMA - Erreur le " . Utils::dateJJ_MM_AAAA(true, time()),
			$dump,
			MAIL_DEVELOPER
		);
	}

	/**
	 * Dumps the Exception into HTML.
	 *
	 * @param \Throwable $throwable the exception to dump
	 */
	private static function dumpException(\Throwable $throwable) {
		$file = $throwable->getFile();
		$line = $throwable->getLine();

		if (file_exists($file)) {
			$lines = file($file);
		}

?>
		<html>
		<head>
			<title><?= $throwable->getMessage(); ?></title>
			<style type="text/css">
				body {
					width: 800px;
					margin: auto;
				}

				ul.code {
					border: inset 1px;
				}
				ul.code li {
					white-space: pre;
					list-style-type: none;
					font-family: monospace;
				}
				ul.code li.line {
					color: red;
				}

				table.trace {
					width: 100%;
					border-collapse: collapse;
					border: solid 1px black;
				}
				table.thead tr {
					background: rgb(240, 240, 240);
				}
				table.trace tr.odd {
					background: white;
				}
				table.trace tr.even {
					background: rgb(250, 250, 250);
				}
				table.trace td {
					padding: 2px 4px 2px 4px;
				}
			</style>
		</head>
		<body>
			<h1>Uncaught <?= get_class($throwable); ?></h1>
			<h2><?= $throwable->getMessage(); ?></h2>
			<p>
				An uncaught <b><?= get_class($throwable); ?></b> was thrown on line
				<b><?= $line; ?></b> of file <b><?= basename($file); ?></b> that
				prevented further execution of this request.
			</p>
			<h2>Where it happened:</h2>
			<?php if (isset($lines)) : ?>
				<code><?= $file . "@" . $line; ?></code>
				<ul class="code">
					<?php for ($i = $line - 10; $i < $line + 5; $i++) : ?>
						<?php if ($i > 0 && $i < count($lines)) : ?>
							<?php if ($i == $line - 1) : ?>
								<li class="line"><?= htmlentities(str_replace("\n", "", $lines[$i])); ?></li>
							<?php else : ?>
								<li><?= htmlentities(str_replace("\n", "", $lines[$i])); ?></li>
							<?php endif; ?>
						<?php endif; ?>
					<?php endfor; ?>
				</ul>
			<?php endif; ?>

			<?php if (is_array($throwable->getTrace())) : ?>
				<h2>Stack trace:</h2>
				<table class="trace">
					<thead>
					<tr>
						<td>File</td>
						<td>Line</td>
						<td>Class</td>
						<td>Function</td>
						<td>Arguments</td>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($throwable->getTrace() as $i => $trace) : ?>
						<tr class="<?= $i % 2 == 0 ? 'even' : 'odd'; ?>">
							<td><?= isset($trace['file']) ? basename($trace['file']) : ''; ?></td>
							<td><?= isset($trace['line']) ? $trace['line'] : ''; ?></td>
							<td><?= isset($trace['class']) ? $trace['class'] : ''; ?></td>
							<td><?= isset($trace['function']) ? $trace['function'] : ''; ?></td>
							<td>
								<?php if (isset($trace['args'])) : ?>
									<?php foreach ($trace['args'] as $j => $arg) : ?>
										<span title="<?= var_export($arg, true); ?>"><?= gettype($arg); ?></span>
										<?= $j < count($trace['args']) - 1 ? ',' : ''; ?>
									<?php endforeach; ?>
								<?php else : ?>
									NULL
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php else : ?>
				<pre><?= $throwable->getTraceAsString(); ?></pre>
			<?php endif; ?>
		</body>
		</html>
<?php
	}

	/**
	 * Retrieves the type of error according to its level.
	 *
	 * @param int $errorNumber error value
	 * @return string error type
	 */
	private static function getPhpErrorFromNumber(int $errorNumber) {
		switch ($errorNumber) {
			case E_ERROR:
				return "E_ERROR";
			case E_WARNING:
				return "E_WARNING";
			case E_PARSE:
				return "E_PARSE";
			case E_NOTICE:
				return "E_NOTICE";
			case E_CORE_ERROR:
				return "E_CORE_ERROR";
			case E_CORE_WARNING:
				return "E_CORE_WARNING";
			case E_COMPILE_ERROR:
				return "E_COMPILE_ERROR";
			case E_COMPILE_WARNING:
				return "E_COMPILE_WARNING";
			case E_USER_ERROR:
				return "E_USER_ERROR";
			case E_USER_WARNING:
				return "E_USER_WARNING";
			case E_USER_NOTICE:
				return "E_USER_NOTICE";
			case E_STRICT:
				return "E_STRICT";
			case E_RECOVERABLE_ERROR:
				return "E_RECOVERABLE_ERROR";
			case E_DEPRECATED:
				return "E_DEPRECATED";
			case E_USER_DEPRECATED:
				return "E_USER_DEPRECATED";
			case E_ALL:
				return "E_ALL";
		}
		return "E_UNKNOWN";
	}

	/**
	 * Retrieves the variable value from the request.
	 *
	 * @param \phpbb\request\request $request the request to retrieve data
	 * @param string $varName the variable names
	 * @param string $defValue the default value
	 * @return string the value of the default value
	 */
	private static function getServerVariable(
			\phpbb\request\request $request,
			string $varName,
			string $defValue = "UNKNOWN"
	) {
		return $request->variable($varName, $defValue, true, request_interface::SERVER);
	}

}
