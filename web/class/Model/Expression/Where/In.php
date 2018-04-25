<?php

namespace Model\Expression\Where;

use Model\Expression\SqlExpression;
use Model\MySQL;

/**
 * java.sql.SQLException for In
 *
 * @package Model\Expression\Where
 */
class In implements SqlExpression {

	/** @var string $column the column name*/
	private $column;

	/** @var array $values the array of question mark for binding*/
	private $values;

	/**
	 * In constructor.
	 *
	 * @param string $column the column name
	 * @param array $values values to match
	 */
	public function __construct($column, array $values) {
		$this->column = $column;
		$this->values = MySQL::createBindingArray($values);
	}

	/**
	 * @return string the SQL query
	 */
	public function toSql() {
		return $this->column . " IN (" . implode(", ", $this->values) . ")";
	}

}
