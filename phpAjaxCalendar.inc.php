<?PHP
// 
//  Ryboe Ajax Calendar
//
//    Version: 0.03
//
//  
//  Author: Sean Sullivan
//  Website: www.ryboe.com
//  Copyright 2008 Sean Sullivan under the GNU GENERAL PUBLIC [GPL] LICENSE: http://www.gnu.org/licenses/gpl.txt
//
//  Copyright 2008 Dave Brondsema http://brondsema.net

class Ajax_Calendar {

/*
 * Define custom callback functions (optional)
 */
	# using a callback function like this is optional
	public function day_details($y,$m,$d) {
		# blank cells before day 1 or after day 30/31
		if ($day == '') {
			echo '&nbsp;'; # for IE table cells
		}
		echo "$d";
		if ($m == 1 and $d == 1) {
			echo "<br/><em>New Year's Day!</em>";
		}
	}

	/*
	 * Build links to events to fetch data from a database	 
	 * This is a simple callback function that will make the dates that have 
	 * events associated with them linkable. The link can then query the database
	 * directly to fetch a list of said events.
	 * A simple ajax example @ http://plusjade.com/calendar
	 
		params $year, $month, $day (int)	
			= numerical values for year/month/day
		params $data_data
			= The key/value pair associated with this date,
			coming from the given $date_array we sent to $calendar->getPhpAjaxCalendarCore();
	 */
	function link_to_events($year, $month, $day, $date_data)
	{
		# blank cells before day 1 or after day 30/31
		if ($day == '')
			echo '&nbsp;'; # for IE table cells
		
		# Your date_array should tell us how many events are on the given day...
		if( '0' < $date_data )
		{
			echo '<a href="/calendar/list_events/'."$year/$month/$day".'" class="day_link">'. $day .'</a>';
		}
		else
			echo '<div class="day_simple">'.$day.'</div>';
	}	
	
/*
 * Build the Calendar html output
 * @ param $month (int)
		= numerical month indicator
 * @ param $year (int)
		= numerical year indicator
 * @ param $date_array (array)
		= optional array containing key/value pairs
		for all dates in the current month such as date/"date event data".
 * @ param $day_function (string)
		= optional callback function to invoke
		must pass the name of the method you wish to call.
 */
 
	public function getPhpAjaxCalendarCore($month, $year, $date_array=NULL, $day_function=null)
	{
		// Use the PHP time() function to find out the timestamp for the current time
		$current_time = time();
		
		// Get the first day of the month
		$month_start = mktime(0,0,0,$month, 1, $year); 
		
		// Get the name of the month
		$month_name = date('F', $month_start); 
		
		// Figure out which day of the week the month starts on.
		$first_day = date('D', $month_start);
		
		// Assign an offset to decide which number of day of the week the month starts on.
		switch($first_day)
		{
		case "Sun":
			$offset = 0;
			break;
		case "Mon":
			$offset = 1;
			break;
		case "Tue":
			$offset = 2;
			break;
		case "Wed":
			$offset = 3;
			break;
		case "Thu":
			$offset = 4;
			break;
		case "Fri":
			$offset = 5;
			break;
		case "Sat":
			$offset = 6;
			break;
		} 
		
		// determine how many days were in last month.
		//    Note: The cal_days_in_month() function returns the number of days in a month for the specified year and calendar.
		//  Gregorian Calendar: http://en.wikipedia.org/wiki/Gregorian_calendar
		//  Define this using the constant: CAL_GREGORIAN
		if($month == 1)
			$num_days_last = cal_days_in_month(CAL_GREGORIAN, 12, ($year -1));
		else
			$num_days_last = cal_days_in_month(CAL_GREGORIAN, ($month - 1), $year);
		
		// determine how many days are in the this month.
		$num_days_current = cal_days_in_month(CAL_GREGORIAN, $month, $year); 
		
		// Count through the days of the current month -- building an array
		for($i = 0; $i < $num_days_current; $i++)
		{
			$num_days_array[] = $i+1;
		} 
		
		// Count through the days of last month -- building an array
		for($i = 0; $i < $num_days_last; $i++)
		{
			$num_days_last_array[] = '';
		}
		
		if($offset > 0){ 
			$offset_correction = array_slice($num_days_last_array, -$offset, $offset);
			$new_count = array_merge($offset_correction, $num_days_array);
			$offset_count = count($offset_correction);
		}
		else
		{ 
			$new_count = $num_days_array;
		}
		
		// How many days do we now have?
		$current_num = count($new_count); 
		
		// Our display is to be 35 cells so if we have less than that we need to dip into next month
		if($current_num > 35)
		{
			$num_weeks = 6;
			$outset = (42 - $current_num);
		}
		else if($current_num < 35)
		{
			$num_weeks = 5;
			$outset = (35 - $current_num);
		}
		if($current_num == 35)
		{
			$num_weeks = 5;
			$outset = 0;
		}
		
		// Outset Correction
		for($i = 1; $i <= $outset; $i++)
		{
			$new_count[] = '';
		}
		
		// Now let's "chunk" the $new_count array
		// into weeks. Each week has 7 days
		// so we will array_chunk it into 7 days.
		$weeks = array_chunk($new_count, 7);
		
		// Start the output buffer so we can output our calendar nicely
		ob_start();
		
		$prev_month = $month - 1;
		$prev_year = $year;
		if ($month == 1) {
			$prev_month = 12;
			$prev_year = $year-1;
		}
		
		$next_month = $month + 1;
		$next_year = $year;
		if ($month == 12) {
			$next_month = 1;
			$next_year = $year + 1;
		}
		
		// Build the heading portion of the calendar table
		echo <<<EOS
		<table id="calendar">
		<tr>
			<td><a href="?month=$prev_month&year=$prev_year" class="monthnav">&laquo; Prev</a></td>
			<td colspan="5" class="month">$month_name $year</td>
			<td><a href="?month=$next_month&year=$next_year" class="monthnav">Next &raquo;</a></td>
		</tr>
		<tr class="daynames"> 
			<td>S</td><td>M</td><td>T</td><td>W</td><td>T</td><td>F</td><td>S</td>
		</tr>
EOS;
		
		foreach($weeks AS $week){
			echo '<tr class="week">'; 
			foreach($week as $day)
			{
				if($day == date('d', $current_time) && $month == date('m', $current_time) && $year == date('Y', $current_time))
					echo '<td class="today">';
				else
					echo '<td class="days">';
				if (null == $day_function OR empty($date_array[$day])) {
					echo $day;
				} else {
					/* Calls user function from this class,
					 * Sends appropriate date data from date_array
					 */
					call_user_func(array($this, $day_function), $year, $month, $day, $date_array[$day]);
				}
				echo '</td>';
			}
			echo '</tr>';
		}
		
		echo '</table>';
		
		return ob_get_clean();
	}
	
}
?>