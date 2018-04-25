<?php

namespace Model\Expression\Where;

use Model\Expression\SqlExpression;

/**
 * java.sql.SQLException for equal
 *
 * @package Model\Expression\Where
 */
class Equal implements SqlExpression {

	private $column;

	/**
	 * Equal constructor.
	 *
	 * @param string $column the column name
	 */
	public function __construct($column) {
		$this->column = $column;
	}

	/**
	 * @return string the SQL query
	 */
	public function toSql() {
		return $this->column . " = ?";
	}

}
