<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
#Database Connection
class database extends SQLite3
{
	function __construct()
	{
		$this->open('minidebconf.db');
	}
}
$db = new database();
if (!$db)
{
	echo $db->lastErrorMsg();
}
else
{
	$sql =<<<EOF
    CREATE TABLE IF NOT EXISTS registration_speaker
    (NAME          TEXT    NOT NULL,
    EMAIL         TEXT    NOT NULL,
    ORG           TEXT,
    CITY          TEXT,
    LAP           INT,
    ACCOM         INT,
    TSHIRT        TEXT,
    ARRIVAL       TEXT,
    DEPARTURE     TEXT,
    TITLE         TEXT,
    DESC          TEXT,
    REGTIME       TEXT,
    PROFILE	  TEXT);
EOF;
	$ret = $db->exec($sql);

}
$name=$email=$org=$city=$prearrival=$predeparture=$profile=$pretitle="";
$lap=$accom=0;
$nameerror = $emailerror = $arrivalerror = $departureerror = $orgerror = $cityerror = $titleerror = $profilerror ="";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$myDateTime = new DateTime(Date(''), new DateTimeZone('GMT'));
	$myDateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
	$date=$myDateTime->format('Y-m-d H:i:s');
	$name = $_POST['sp-name'];
	if (empty($_POST['sp-email']))
	{
		$emailerror = "Required Field";
	}
	else
	{
		$email = $_POST['sp-email'];
		if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
		{
			$emailerror = "Invalid Format";
		}
	}
	$org = $_POST['sp-org'];
	$city = $_POST['sp-city'];
	if(!preg_match('/$^|^[a-zA-Z]+[0-9]*[\. ,]*[a-zA-Z0-9]*$/',$city))
	{
		$cityerror = "City name must start with a letter and can contain only alphanumerics, spaces, periods and commas";
	}
	if( empty( $_POST['sp-profile'] ) ) {
		$profilerror = "No profile";
	} else {
		$profile = $_POST['sp-profile'];
	}

	$tshirt = 1; //Needs to be fixed

	if( empty( $_POST['sp-arrival'] ) ) {
		$arrivalerror= "No arriving date given";
	} else {
		$arrival = $_POST['sp-arrival'];
	}
	if( empty( $_POST['sp-depart'] ) ) {
		$departureerror= "No departure date given";
	} else {
		$departure = $_POST['sp-depart'];
	}
	$lap = $accom = 1; //Needs to be fixed
	$pretitle=$_POST['sp-title'];
	if (empty($pretitle))
	{
		$titleerror = "Required Field";
	}
	else
	{
		$title=$_POST['sp-title'];
		$desc = $_POST['sp-desc'];

	}
	if ($nameerror=="" && $emailerror=="" && $arrivalerror=="" && $departureerror=="" && $orgerror =="" && $cityerror=="" && $titleerror == "" && $profilerror == "" )
	{
		$sql="INSERT INTO `registration_speaker` VALUES ('$name','$email','$org','$city','$lap','$accom','$tshirt','$arrival','$departure','$title','$desc','$date', '$profile')";

		$ret = $db->exec($sql);
		if($ret)
		{
			echo "good";
			$db->close();
			header('location:return.html');
		} else {
			echo "writing failed";
		}
	} else {
		echo "wrong data entered";
	}
}
?>