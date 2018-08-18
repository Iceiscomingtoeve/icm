<?php

namespace EVEOnline\ESI\Mail;

use EVEOnline\ESI\Mail\Enums\RecipientType;

/**
 * Class MailRecipient
 *
 * @package EVEOnline\ESI\Mail
 */
class MailRecipient {

	/** @var int */
	private $recipientId;

	/** @var string */
	private $recipientName;

	/** @var RecipientType */
	private $recipientType;

	/**
	 * MailRecipient constructor.
	 *
	 * @param int $recipientId
	 * @param RecipientType $recipientType
	 */
	public function __construct(
		int $recipientId,
		RecipientType $recipientType
	) {
		$this->recipientId = $recipientId;
		$this->recipientName = "";
		$this->recipientType = $recipientType;
	}

	/**
	 * @return int
	 */
	public function getRecipientId(): int {
		return $this->recipientId;
	}

	/**
	 * @return RecipientType
	 */
	public function getRecipientType(): RecipientType {
		return $this->recipientType;
	}

	/**
	 * @return string
	 */
	public function getRecipientName(): string {
		return $this->recipientName;
	}

	/**
	 * @param string $recipientName
	 */
	public function setRecipientName(string $recipientName): void {
		$this->recipientName = $recipientName;
	}

	public function __toString() {
		return $this->getRecipientName();
	}

	/**
	 * Creates a MailRecipient from the associative json array.
	 *
	 * @param array $json the json associative array
	 * @return MailRecipient
	 */
	public static function create(array $json) {
		return new MailRecipient(
			$json['recipient_id'],
			new RecipientType($json['recipient_type'])
		);
	}

}
