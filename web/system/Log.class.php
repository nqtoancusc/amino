<?php

/**
 * Logger class
 *
 *
 */



class Log {
	static private $started;
	static private $lines = array();

	static function start() {
		self::$started = microtime(true);
		self::$lines = array();
		self::Add('Log started');
	}

	static function add($string, $level = 0) {
		$line = new stdClass();
		$line->created = microtime(true);
		$line->elapsed =  $line->created - self::$started;
		$line->level = $level;
		$line->text = $string;
		self::$lines[] = $line;
	}

	static function get() {
		self::Add('Log ended');
		$string = '#'.str_repeat('=',80).'#'."\n";
		foreach (self::$lines as $line) {
			$string.= '# '.number_format($line->elapsed,5).': '.$line->text."\n";
		}
		DB::clearLog();
		return $string;
	}


}