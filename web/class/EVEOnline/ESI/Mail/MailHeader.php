<?php

namespace EVEOnline\ESI\Mail;
use DateTime;

/**
 * Class MailHeader
 *
 * @package EVEOnline\ESI\Mail
 */
class MailHeader {

	/**
	 * @var int
	 */
	private $mailId;

	/**
	 * @var string
	 */
	private $subject;

	/**
	 * @var \DateTime
	 */
	private $time;

	/**
	 * @var int
	 */
	private $from;

	/**
	 * @var MailRecipient[]
	 */
	private $recipients;

	/**
	 * @var bool
	 */
	private $isRead;

	/**
	 * @var int[]
	 */
	private $labels;

	/**
	 * MailHeader constructor.
	 *
	 * @param int $mailId
	 * @param string $subject
	 * @param DateTime $time
	 * @param int $from
	 * @param MailRecipient[] $recipients
	 * @param bool $isRead
	 * @param int[] $labels
	 */
	private function __construct(
		int $mailId,
		string $subject,
		DateTime $time,
		int $from,
		array $recipients,
		bool $isRead,
		array $labels
	) {
		$this->mailId = $mailId;
		$this->subject = $subject;
		$this->time = $time;
		$this->from = $from;
		$this->recipients = $recipients;
		$this->isRead = $isRead;
		$this->labels = $labels;
	}

	/**
	 * @return int
	 */
	public function getMailId(): int {
		return $this->mailId;
	}

	/**
	 * @return string
	 */
	public function getSubject(): string {
		return $this->subject;
	}

	/**
	 * @return DateTime
	 */
	public function getTime(): DateTime {
		return $this->time;
	}

	/**
	 * @return int
	 */
	public function getFrom(): int {
		return $this->from;
	}

	/**
	 * @return MailRecipient[]
	 */
	public function getRecipients(): array {
		return $this->recipients;
	}

	/**
	 * @return bool
	 */
	public function isRead(): bool {
		return $this->isRead;
	}

	/**
	 * @return int[]
	 */
	public function getLabels(): array {
		return $this->labels;
	}

	/**
	 * Creates MailHeader from the associative json array.
	 *
	 * @param array $json the json associative array
	 * @return MailHeader
	 */
	public static function create(array $json) {
		return new MailHeader(
			$json['mail_id'],
			$json['subject'],
			new DateTime($json['timestamp']),
			$json['from'],
			self::createMailRecipient($json),
			$json['is_read'],
			$json['labels']
		);
	}

	/**
	 * Creates an array of MailRecipient.
	 *
	 * @param array $json the global json
	 * @return MailRecipient[] the array of MailRecipient
	 */
	private static function createMailRecipient(array $json) {
		$recipients = array();
		// Retrieves channels if exists
		foreach ($json['recipients'] as $curRecipient) {
			$recipients[] = MailRecipient::create($curRecipient);
		}
		return $recipients;
	}

}
