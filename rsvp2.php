<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
$name = $_GET['a'];
$attending = $_GET['b'];

$userIP = $_SERVER['REMOTE_ADDR'];
$userDomain = gethostbyaddr($_SERVER['REMOTE_ADDR']);

$mymail = "RSVP\n\n";
$mymail .= "Name: $name\n\n";
$mymail .= "Attending: $attending\n\n";

$headers = "MIME-Version: 1.0\n";
$headers .= "Content-type: text/plain; charset=Iso 8859-1\n";
$headers .= "X-Priority: 1\n";
$headers .= "X-MSMAIL-Priority: Normal\n";
$headers .= "X-Mailer: php\n";
$headers .= "From: info@myagentnow.ca \r\n\r\n";

$to = "info@myagentnow.ca";
$subject = "My Agent Now RSVP";

mail($to, $subject, $mymail, $headers);

header("Location:rsvp3.php");
?>