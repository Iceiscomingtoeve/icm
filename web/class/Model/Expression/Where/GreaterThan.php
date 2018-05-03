<?php

namespace Model\Expression\Where;

use Model\Expression\SqlExpression;

/**
 * java.sql.SQLException for GreaterThan
 *
 * @package Model\Expression\Where
 */
class GreaterThan implements SqlExpression {

	/** @var string $column */
	private $column;
	/** @var bool $orEquals */
	private $orEquals;

	/**
	 * GreaterThan constructor.
	 *
	 * @param string $column the column name
	 * @param bool $orEquals greater or equals ? false by default
	 */
	public function __construct(string $column, bool $orEquals = false) {
		$this->column = $column;
		$this->orEquals = $orEquals;
	}

	/**
	 * @return string the SQL query
	 */
	public function toSql() {
		return $this->column . " " . ($this->orEquals ? ">=" : ">") . " ?";
	}

}
