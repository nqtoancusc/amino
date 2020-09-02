<?php

/**
 * CalendarFactory is a factory class
 * with a single static method createCalendar()
 */
class CalendarFactory {

   /**
    * Method createCalendar($base_date = null) is a static method.
    * Input: $base_date is a specific date in format Y-m-d.
    * Return: an array represents 6 weeks.
    */
    public static function generateCalendar($base_date = null) {
	$weeks = array(
	    array(),
	    array(),
	    array(),
	    array(),
	    array(),
	    array()
	); 
	
	$current_month = date("m");
	$current_year = date("Y");
	
	$w = 0; // Week number starting from 0

	// Include days of last month
	$current_day_of_month = date('N', mktime(12, 0, 0, $current_month, 1, $current_year));
	$same_day_last_month = $current_day_of_month - 1;
	$last_day_last_month = date('d', strtotime("last day of last month", strtotime($base_date)));
	$last_month = date("m", strtotime("-1 month",strtotime($base_date)));
	for($j = $same_day_last_month; $j > 0; $j--) {
		$day = date("Y-m-d", mktime(0, 0, 0, $last_month, $last_day_last_month - ($j - 1)));
		$weeks[$w][$day] = date("d", mktime(0, 0, 0, $last_month, $last_day_last_month - ($j - 1)));
	}

	// Include days of current month
	for($d=1; $d<=31; $d++) {
	    $time=mktime(12, 0, 0, $current_month, $d, $current_year);
	    if (date('m', $time) == $current_month) {
		$day = date('Y-m-d', $current_month);
		$weeks[$w][$day] = date('d', $time);
		// Week number increases having 7 days 
		if ((($d + $same_day_last_month) % 7) == 0) {
		    $w++;
		}
	    }
	}

	// Include days of next month
	$next_month = date("m", strtotime("1 month",strtotime($base_date)));
	$next_day = 1;

	// If current month is December, next month belongs to next year
	if ($current_month == 12) {
	    $next_year = date('Y', strtotime("+1 year",strtotime($base_date)));
	}

	// Include more days if Week number is less than 7
	if(7 > count($weeks[$w])) {
	    for($j = count($weeks[$w]); $j < 7; $j++) {
		$time=mktime(12, 0, 0, $next_month, $next_day++, $next_year);
		if (date('m', $time)==$next_month) {
		    $day = date('Y-m-d', $time);
		    $weeks[$w][$day] = date('d', $time);
		}
	    }
	}

	// If week number is 4, add the fifth week
	if ($w == 4) {
	    $w++;
	    for($j = 1; $j <= 7; $j++) {
		$time=mktime(12, 0, 0, $next_month, $next_day++, $next_year);
		if (date('m', $time)==$next_month) {
		    $day = date('Y-m-d', $time);
		    $weeks[$w][$day] = date('d', $time);
		}
	    }
	}
	
	return $weeks;
    }
}