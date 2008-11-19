<?php
require_once dirname(__FILE__) . '/../below_root_inc/phpAjaxCalendar.inc.php';

if(@$_SERVER ['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_POST['month']))
{
    $month = $_POST['month'];
    $year = isset($_POST['year']) ? $_POST['year'] : date('Y');
    die(getPhpAjaxCalendarCore($month,$year));
}
?>
<html>
<head>
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
