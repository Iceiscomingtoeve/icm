<?php

namespace Model\Expression\Where;

use Model\Expression\SqlExpression;

/**
 * java.sql.SQLException for equal
 *
 * @package Model\Expression\Where
 */
class Equal extends SqlExpression {

	private $column;

	/**
	 * Equal constructor.
	 *
	 * @param string $column the column name
	 */
	public function __construct(string $column) {
		$this->column = $column;
	}

	/**
	 * @return string the SQL query
	 */
	public function toSql(): string {
		return $this->column . " = ?";
	}

}
