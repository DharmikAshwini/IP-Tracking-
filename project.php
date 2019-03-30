<?php
    //$db_connection = pg_connect("host=localhost dbname= csvreader user=postgres password=admin");
    
$host = "localhost"; 
$user = "postgres"; 
$pass = "Mansipatil99"; 
$db = "csvreader"; 

$db_connection = pg_connect("host=$host dbname=$db user=$user password=$pass");
 
    $result = pg_query($db_connection, "SELECT * FROM geolite limit 5");
 echo ("connected\n\n");
 
//displaying date and time at which page has been hit by visitor

$sample= $_SERVER['REMOTE_ADDR'];
echo ("$sample");
$day=date("l");
echo("$day");
$dt= date("Y-m-d"); 
echo ("$dt");
//$time=date("h:i:s a");
//echo ("$time");
// to store in database
$query1 = "INSERT INTO ipaddress values ('$sample','$day','$dt')"; 
$rs = pg_query($query1) or die('Query failed: ' . pg_last_error());


//giving sample ip address
 $sample ="27.0.48.0";
 //Code to extrct first 3 digits of ip addr
 $nums =explode (".","$sample");
 //$nums = explode(".", "1.2.3.4") ;
 
$ipadd = $nums[0]. "." .$nums[1]. "." .$nums[2] ; 

if(!$sample)
{
	echo ("not in the range");
}
else
{
 

//put this extracted ip into the query, but datatype needs to be string
$query = "SELECT * FROM (SELECT * FROM geolite INNER JOIN geolite_city ON geolite.geoname_id = geolite_city.geoname_id)as t WHERE network like '" . $ipadd . "%' LIMIT 10";
//$query="SELECT * FROM geolite INNER JOIN geolite_city ON geolite.geoname_id = geolite_city.geoname_id LIMIT 5";
//Performing SQL query

$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// Printing results in HTML

echo "<table border=3>\n";
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo "\t<tr>\n";
	$res[] = $line;
    foreach ($line as $col_value => $row_value) {
        echo "\t\t<tr><td>$col_value</td><td>$row_value</td></tr>\n";
		
    }
    //echo "\t</tr>\n";
}
echo "</table>\n";
}
//print_r($res);

// Free resultset
pg_free_result($result);
 
// Closing connection
pg_close($db_connection);
?>
<!DOCTYPE html>
<html>
<body>

<h1> Map</h1>

<div id="googleMap" style="width:100%;height:400px;"></div>

<script>

function myMap() {
var mapProp= {

  center:new google.maps.LatLng(<?php echo $res[0]['latitude']; ?>, <?php echo $res[0]['longitude']; ?> ),
  zoom:5,
};
var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6SmC7VEZ2gAMQmQaaFiz7LtJrZBxay28&callback=myMap"></script>

</body>
</html>
