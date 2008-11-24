<?php
require_once dirname(__FILE__) . '/phpAjaxCalendar.inc.php';

# using a callback function like this is optional
function day_details($y,$m,$d) {
    echo "$d";
    if ($m == 1 and $d == 1) {
        echo "<br/><em>New Year's Day!</em>";
    }
}

if(@$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_GET['month']))
{
    $month = $_GET['month'];
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
    die(getPhpAjaxCalendarCore($month,$year,'day_details'));
}
?>
<html>
<head>
<link href="./calendar.css" rel="stylesheet" type="text/css">
</head>
<body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.js"></script>
<?php
$month = date('m');
if (isset($_GET['month'])) {
    $month = $_GET['month'];
}
$year = date('Y');
if (isset($_GET['year'])) {
    $year = $_GET['year'];
}
echo getPhpAjaxCalendar($month,$year,'day_details');
?>
</body>
</html>
