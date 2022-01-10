<?php
$ini_array = parse_ini_file("../config.ini");
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

    ${'team'.$row['participant_id'].'_query'} = mysqli_query($dbconnect, "SELECT SUM(points) AS sum FROM games WHERE winner_id LIKE ".$row['participant_id']) or die (mysqli_error($dbconnect));
    ${'team'.$row['participant_id'].'_points'} = mysqli_fetch_array(${'team'.$row['participant_id'].'_query'});

    if (${'team'.$row['participant_id'].'_points'}['sum'] == null){ //replace null with 0
        ${'team'.$row['participant_id'].'_points'}['sum']= 0;
    }
    ${$row['name']} = ${'team'.$row['participant_id'].'_points'}['sum'];
    $all[$row['name']] = ${'team'.$row['participant_id'].'_points'}['sum'];

}

echo json_encode($all);


mysqli_close($dbconnect);
?>