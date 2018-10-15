<?php
// mail("firdauzfanani@gmail.com","Success","Great, Localhost Mail works");
// include ('config/connect.php');
//API URL
$url = 'http://35.202.49.101:8080/api/v1/O0SR75GfRyhYvceTWeVr/telemetry';
//create a new cURL resource
$ch = curl_init($url);
$data = array();

$HOST="Localhost";
$USER="root";
$PASS="root";
$DB="dcim";
 
//Connecting to Database 
$con = mysqli_connect($HOST,$USER,$PASS,$DB) or die('Unable to Connect'); 
$fetch = mysqli_query($con,"SELECT Temperature AS temp, Humidity AS hum FROM `fac_SensorReadings` limit 1"); 

while ($row = mysqli_fetch_array($fetch)) {
    $row_array['temp'] = $row['temp'];
    $row_array['hum'] = $row['hum'];
}
array_push($data,$row_array);

$payload = json_encode($data);
//attach encoded JSON string to the POST fields
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
//return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute the POST request
$result = curl_exec($ch);
//close cURL resource
curl_close($ch);
//Output response
echo "<pre>$result</pre>";
echo $payload;
//get response
$data = json_decode(file_get_contents('php://input'), true);
//output response
echo '<pre>'.$data.'</pre>';