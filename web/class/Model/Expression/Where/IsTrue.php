<?php

namespace Model\Expression\Where;

use Model\Expression\SqlExpression;

/**
 * java.sql.SQLException for IsTrue
 *
 * @package Model\Expression\Where
 */
class IsTrue extends SqlExpression {

	/**
	 * @return string the SQL query
	 */
	public function toSql(): string {
		return "1 = 1";
	}

}
