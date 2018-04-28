<?php

namespace Model\Expression;

/**
 * Defines that the object is a SQL Expression.
 *
 * @package Model\Expression
 */
interface SqlExpression {

	/**
	 * @return string the SQL query
	 */
	public function toSql();

}
