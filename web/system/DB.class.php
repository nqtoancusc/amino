<?php


/**
 *
 * Database abstraction object
 *
 * Singleton
 *
 *
 */

class DB {
	static private $_pdo;
	static private $_statements = array();

	static private $_last_key = null;
	static private $_in_transaction = false;
	static private $_lock = false;

	static private $_host;
	static private $_scheme;
	static private $_user;
	static private $_password;

	static private $_connected_time;


	static $log = array();

	static function init($host,$scheme,$user,$password) {
		self::$_host = $host;
		self::$_scheme = $scheme;
		self::$_user = $user;
		self::$_password = $password;
	}

	static function _connect() {
		if (!self::$_host) {
			throw new Exception('DB connection parameters missing');
		}
		if (self::$_scheme) {
			$string = 'mysql:dbname='.self::$_scheme.';host='.self::$_host.';charset=UTF8';
		} else {
			$string = 'mysql:host='.self::$_host.';charset=UTF8';
		}
		self::$_pdo = new PDO($string, self::$_user, self::$_password);
		self::$_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		self::$_connected_time = microtime(true);
	}

	static function beginTransaction($withLock = true) {
		if (self::$_in_transaction) {
			// already in progress
			return;
		}
		if (!self::$_pdo) {
			self::_connect();
		}

		if ($withLock) {
			self::setLog( 'get_lock' );
			self::sql("select get_lock('ec3', 2)");
			self::$_lock = true;
		}

		self::setLog( 'beginTransaction' );
		self::$_pdo->beginTransaction();
		self::$_in_transaction = true;
	}

	static function rollBack() {
		if (!self::$_in_transaction) {
			// no open transaction
			return;
		}
		self::setLog( 'rollBack' );
		self::$_pdo->rollBack();
		self::$_in_transaction = false;

		if (self::$_lock) {
			self::setLog( 'release_lock' );
			self::sql("select release_lock('ec3')");
			self::$_lock = false;
		}

	}

	static function commit() {
		if (!self::$_in_transaction) {
			// no open transaction
			return;
		}
		self::setLog( 'commit' );
		self::$_pdo->commit();
		self::$_in_transaction = false;

		if (self::$_lock) {
			self::setLog( 'release_lock' );
			self::sql("select release_lock('ec3')");
			self::$_lock = false;
		}

	}

	static function getExecTimes() {
		$lines = array();
		foreach (self::$_statements as $key=>$obj) {
			$lines[] = $key.' '.str_pad($obj->calls,5,' ', STR_PAD_LEFT).' calls '.str_pad(number_format($obj->execution_time,5),8,' ', STR_PAD_LEFT);
		}
		return implode("\n", $lines);
	}

	static function bindColumn($key, $a, $b, $c) {
		$statement = &self::_getStatement( $key );
		$statement->prepared->bindColumn ( $a, $b, $c );
	}

	static function bindParam($key, $a, $b, $c) {
		$statement = &self::_getStatement( $key );
		$statement->prepared->bindParam ( $a, $b, $c );
	}

	static function prepare($sql) {
		if (!self::$_pdo) {
			self::_connect();
		}

		$key = md5($sql);
		self::setLog( 'prepare: '.$key.' '.$sql );

		if (array_key_exists($key, self::$_statements)) {
			// this statement already exisits, so no need to prepare new one
			self::$_last_key = $key;
			return $key;
		}

		$stobj = new stdClass();
		$stobj->prepared = self::$_pdo->prepare($sql);
		$stobj->SQL = $sql;
		$stobj->key = $key;
		$stobj->calls = 0;
		$stobj->execution_time = 0;
		$stobj->results = array();

		self::$_statements[$key] = $stobj;
		self::$_last_key = $key;
		return $key;
	}

	static function &_getStatement( $key = null ) {
		// Resolve key and get the statement
		if (!$key) {
			$key = self::$_last_key;
		}
		if (array_key_exists($key, self::$_statements)) {
			return self::$_statements[$key];
		}
	}

	static function affectedRows($key=null) {
		$statement = &self::_getStatement( $key );
		return $statement->prepared->rowCount();
	}

	static function exec($values = array(), $key = null) {
		$statement = &self::_getStatement( $key );

		self::setLog( 'execute: '.$statement->key.( is_array($values) ? ' ['.implode('][',$values).']' : null ));

		$statement->calls++;
		// clock it
		$time = microtime(true);

		if ( $statement->prepared->execute($values) ) {
			// store rows
			if ($statement->prepared->columnCount()) {
				if ($statement->results = $statement->prepared->fetchAll(PDO::FETCH_OBJ)) {
					array_reverse($statement->results);
				}
			}
			$statement->prepared->closeCursor();

			// success
			$elapsed = microtime(true)-$time;
			$statement->execution_time += $elapsed;
		} else {
			throw new Exception('SQL error');
		}
	}

	static function fetch( $key = null ) {
		$statement = &self::_getStatement( $key );
		if ($object = array_pop($statement->results)) {
			return $object;
		}
	}

	static function foundRows() {
		return self::$_pdo->query('SELECT FOUND_ROWS() as found')->fetchColumn();
	}

	static function lastInsertId() {
		return self::$_pdo->lastInsertId();
	}

	static function sql($sql) {
		if (!self::$_pdo) {
			self::_connect();
		}
		self::setLog('execute SQL: '.$sql);
		self::$_pdo->query($sql)->closeCursor();
	}

	static function setLog($line) {
		$delta = microtime(true) - self::$_connected_time;
		self::$log[] = number_format($delta,6).': '. substr($line,0,80);
	}

	static function clearLog() {
		self::$log = array();
		self::$_statements = array();
		self::$_connected_time = microtime(true);
	}

	static function getLog() {
		return self::$log;
	}

}