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

$games_query = mysqli_query($dbconnect, "SELECT g.id, g.name as gname,g.points, p.name as pname, g.time FROM games g JOIN participants p ON g.winner_id = p.participant_id ") or die (mysqli_error($dbconnect));

$all = array();

while ($row = mysqli_fetch_array($games_query)) {
    ${"game".$row['id']} = array();
    ${"game".$row['id']}['gname'] = $row['gname'];
    ${"game".$row['id']}['points'] = $row['points'];
    ${"game".$row['id']}['pname'] = $row['pname'];
    ${"game".$row['id']}['time'] = $row['time'];

    $all[$row['id']] = ${"game".$row['id']};
}
echo json_encode($all);


mysqli_close($dbconnect);

?>