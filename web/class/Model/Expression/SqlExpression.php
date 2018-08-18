<?php

namespace Model\Expression;

/**
 * Defines that the object is a SQL Expression.
 *
 * @package Model\Expression
 */
abstract class SqlExpression {

	/**
	 * @return string the SQL query
	 */
	public abstract function toSql(): string;

	public function __toString(): string {
		return $this->toSql();
	}

}
