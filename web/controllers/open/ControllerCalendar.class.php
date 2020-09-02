<?php

class ControllerCalendar extends OpenController {
	public $template = 'pages/open/calendar.twig';

	public $day_of_weeks = array(
		"Sun",
		"Mon",
		"Tue",
		"Wed",
		"Thu",
		"Fri",
		"Sat"
	);
	
	public function Execute() {
		$this->base_date = date("Y-m-d"); // Default value is today
		
		// Add/Subtract one month depending on navigation action is next/previous
		if (array_key_exists('navigation', $this->post)) {
		    $date_time = new DateTime();
		    $new_date = $date_time->createFromFormat('Y-m-d', $this->post['base_date']);
		    if ($this->post['navigation'] == 'next') {
			$this->base_date = date('Y-m-d', strtotime('+1 month', strtotime($new_date->format('Y-m-d'))) );
		    } else {
			$this->base_date = date('Y-m-d', strtotime('-1 month', strtotime($new_date->format('Y-m-d'))) );
		    }
		}
		
		$this->weeks = CalendarFactory::generateCalendar($this->base_date);
	}
}