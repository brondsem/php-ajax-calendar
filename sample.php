<?php
require_once dirname(__FILE__) . '/phpAjaxCalendar.inc.php';

$calendar	= new Ajax_Calendar;

# Initialize the date_array container for given month
$date_array	= array();
for($x=0; $x<=31; ++$x)
	$date_array[$x] = 0;

/* Example data fetched from a database call.
 * Each nested array is a table row.
 * Array key is the day of the event The value is sample event data
 * Why??
	--------------
  Attaching a callback to each day is very useful, but you don't want to
  make a database call for EVERY DAY of each month, when you can simply
  grab all days from one given month at once, and pass the data via array.
  
 * IMPORTANT!!!
	-------------
 * This is an example only. The $date_array is being sent to
 * every calendar/month call. You'll need to build this array based on the 
 * actual month being requested.
 *
 */
$dates = array(
	array( 1 => 'some event data'),
	array( 1 => 'some event data'),
	array( 5 => 'some event data'),
	array( 7 => 'some event data'),
	array( 8 => 'some event data'),
	array( 8 => 'some event data'),
	array( 8 => 'some event data'),
	array( 8 => 'some event data'),
	array( 15 => 'some event data'),
	array( 20 => 'some event data'),
	array( 25 => 'some event data'),
	array( 25 => 'some event data'),
	array( 31 => 'some event data'),
	array( 31 => 'some event data'),
);

/* 
 * Increases the event count for the day, depending on how many events
 * are on said day. Now our callback functions knows which dates have events on them.
 */
foreach($dates as $date) {
	foreach($date as $day => $data) {
		(int) ++$date_array[$day];
	}
}

/*
 * IF the prev/next month links are being clicked and loaded via ajax...
 */ 
if(@$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_GET['month']))
{
    $month = $_GET['month'];
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
    die($calendar->getPhpAjaxCalendarCore($month, $year, $date_array, 'link_to_events'));
}

$month = date('m');
if (isset($_GET['month'])) {
    $month = $_GET['month'];
}
$year = date('Y');
if (isset($_GET['year'])) {
    $year = $_GET['year'];
}
?>
<html>
<head>
	<link href="./calendar.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.js"></script>
</head>
<body>

	<div class="phpajaxcalendar_wrapper">
		<?php echo $calendar->getPhpAjaxCalendarCore($month, $year, $date_array, 'link_to_events')?>
	</div>
	
	<script type="text/javascript" language="javascript">
		function phpAjaxCalendar_clickMonth() {
			// use ajax to repopulate, using the parameters from the link itself
			$(this).parents(".phpajaxcalendar_wrapper").load(this.href, {}, function() {
				// and reset the click handling for the new HTML
				$(".phpajaxcalendar_wrapper a.monthnav").click(phpAjaxCalendar_clickMonth);
			});
			return false;
		}
		$(".phpajaxcalendar_wrapper a.monthnav").click(phpAjaxCalendar_clickMonth);
	</script>
	
</body>
</html>
