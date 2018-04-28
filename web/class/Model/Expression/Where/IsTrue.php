<?php

namespace Model\Expression\Where;

use Model\Expression\SqlExpression;

/**
 * java.sql.SQLException for IsTrue
 *
 * @package Model\Expression\Where
 */
class IsTrue implements SqlExpression {

	/**
	 * @return string the SQL query
	 */
	public function toSql() {
		return "1 = 1";
	}

}
