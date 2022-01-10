<?php
$ini_array = parse_ini_file("config.ini");
$dbhost = $ini_array['dbhost'];
$dbuser = $ini_array['dbuser'];
$dbpass = $ini_array['dbpass'];
$db     = $ini_array['db'];

$dbconnect=mysqli_connect($dbhost,$dbuser,$dbpass,$db);

if ($dbconnect->connect_error) {
    die("Database connection failed: " . $dbconnect->connect_error);
}

$participants_query = mysqli_query($dbconnect, "SELECT * FROM participants") or die (mysqli_error($dbconnect));

$all = array();

while ($row = mysqli_fetch_array($participants_query)) {

    $all[$row['name']] = $row['color'];

}

echo json_encode($all);


mysqli_close($dbconnect);
?>