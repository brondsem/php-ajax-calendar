<?php
require_once dirname(__FILE__) . '/phpAjaxCalendar.inc.php';

if(@$_SERVER ['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_POST['month']))
{
    $month = $_POST['month'];
    $year = isset($_POST['year']) ? $_POST['year'] : date('Y');
    die(getPhpAjaxCalendarCore($month,$year));
}
?>
<html>
<head>
<link href="./calendar.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
$month = date('m');
if (isset($_GET['month'])) {
    $month = $_GET['month'];
}
$year = date('Y');
if (isset($_GET['year'])) {
    $year = $_GET['year'];
}
echo getPhpAjaxCalendar($month,$year);
?>
</body>
</html>
