<?php

class ControllerCalendar extends OpenController {
	public $template = 'pages/open/calendar.twig';
        
        public $months=array(
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December"
        );

	public $day_of_weeks = array(
		"Sun",
		"Mon",
		"Tue",
		"Wed",
		"Thu",
		"Fri",
		"Sat"
	);
	
	public function execute (Session $session = null, $uri, $post, $get, $files) {
		$this->base_date = $this->current_date = date("Y-m-d"); // Default value is today
                $this->day = date('d', strtotime($this->base_date));
		$this->month = date('m', strtotime($this->base_date));
		$this->year = date('Y', strtotime($this->base_date));
		// Add/Subtract one month depending on navigation action is next/previous
		if (array_key_exists('navigation', $post)) {
		    $date_time = new DateTime();
		    $new_date = $date_time->createFromFormat('Y-m-d', $post['base_date']);
		    if ($post['navigation'] == 'next') {
			$this->base_date = date('Y-m-d', strtotime('+1 month', strtotime($new_date->format('Y-m-d'))) );
		    } else {
			$this->base_date = date('Y-m-d', strtotime('-1 month', strtotime($new_date->format('Y-m-d'))) );
		    }
		}
		$this->weeks = CalendarFactory::generateCalendar($this->base_date);
	}
}