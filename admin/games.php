<script>
    function updateGame(id,game,points,winner,time){
        time = time.replace(/\s/g, 'T');
        time = time.substring(0, time.length-3);
        document.getElementById('inputForm').elements["id"].value = id;
        document.getElementById('inputForm').elements["game"].value = game;
        document.getElementById('inputForm').elements["points"].value = points;
        document.getElementById('inputForm').elements["winner"].value = winner;
        document.getElementById('inputForm').elements["time"].value = time;
        document.getElementById('inputForm').elements["new"].value = 0;
        document.getElementById('inputForm').elements["update"].value = 1;
    }
</script>



<?php
$ini_array = parse_ini_file("../config.ini");
$dbhost = $ini_array['dbhost'];
$dbuser = $ini_array['dbuser'];
$dbpass = $ini_array['dbpass'];
$db     = $ini_array['db'];

$isNew = 1;
$isUpdate = 0;

$dbconnect=mysqli_connect($dbhost,$dbuser,$dbpass,$db);
$eventname = $ini_array['eventname'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="adminstyle.css" rel="stylesheet" type="text/css">
    <meta charset="UTF-8">
    <title><?php echo $eventname?> Admin</title>
</head>
<body>

<?php

if ($dbconnect->connect_error) {
    die("Database connection failed: " . $dbconnect->connect_error);
}

if((isset($_POST['new']) && $_POST['new']==1) || (isset($_POST['update']) && $_POST['update']==1)){
    $id = $_REQUEST['id'];
    $game =$_REQUEST['game'];
    $points = $_REQUEST['points'];
    $winner = $_REQUEST['winner'];
    $time = $_REQUEST['time'];

    if ($_POST['new']==1){
        $ins_query="insert into games
    (`id`,`name`,`points`,`winner_id`,`time`)values
    ('$id','$game','$points','$winner','$time')";
        mysqli_query($dbconnect,$ins_query) or die("Database connection failed: " . $dbconnect->connect_error);
    }
    elseif ($_POST['update']==1){
        $ins_query=" update games
        /* SET id = $id, name = $game, points = $points, winner_id = $winner, time = $time */
        SET id = $id, name = '$game', points = $points, winner_id = $winner
        where id = $id";
        mysqli_query($dbconnect,$ins_query) or die("Database connection failed: " . $dbconnect->connect_error);
    }
    //TODO add message for new or update
    //TODO catch existing ID
}

$participants_query = mysqli_query($dbconnect, "SELECT * FROM participants") or die (mysqli_error($dbconnect));

?>

<div id="box">
    <div id="form">
        <form action="games.php" method="post" id="inputForm">
            <input type="hidden" name="new" value=" <?php echo $isNew?>" />
            <input type="hidden" name="update" value=" <?php echo $isUpdate?>" />
            <div class="input"><label for="id">ID:</label> <input type="number" name="id" id="id" /> </div>
            <div class="input"> <label for="game">Spiel:</label> <input type="text" name="game" id="game" /> </div>
            <div class="input"> <label for="points">Punkte:</label> <input type="number" name="points" id="points" /> </div>
            <div class="input"> <label for="winner">Gewinner:</label> <select name="winner" id="winner">
                    <?php
                        while ($row = mysqli_fetch_array($participants_query)) {
                            echo "<option value=\"".$row['participant_id']."\">".$row['name']."</option>";
                        }

                    ?>
                </select> </div>
            <div class="input"> <label for="time">Zeit:</label> <input type="datetime-local" name="time" id="time" /> </div>
            <div> <input type="submit"></div>
        </form>
    </div>

    <div id="table" class="table">
        <div class="tableRow">
            <div class="tableRowEmpty">&nbsp;</div>
            <div class="tableRowID">ID</div>
            <div class="tableRowGame">Spiel</div>
            <div class="tableRowPoints">Punkte</div>
            <div class="tableRowWinner">Gewinner</div>
            <div class="tableRowTime">Zeit</div>
        </div>
        <?php
        $games_query = mysqli_query($dbconnect, "SELECT g.id as gid, g.name as gname,g.points as gpoints, p.name as pname, g.winner_id as gwinner, g.time as gtime FROM games g JOIN participants p ON g.winner_id = p.participant_id ") or die (mysqli_error($dbconnect));


        while ($row = mysqli_fetch_array($games_query)) {
            echo "<div class=\"tableRow\">";
                echo "<div class=\"tableRowEmpty\">&nbsp;</div>";
                echo "<div class=\"tableRowID\">".$row['gid']."</div>";
                echo "<div class=\"tableRowGame\">".$row['gname']."</div>";
                echo "<div class=\"tableRowPoints\">".$row['gpoints']."</div>";
                echo "<div class=\"tableRowWinner\">".$row['pname']."</div>";
                echo "<div class=\"tableRowTime\">".$row['gtime']."</div>";
                echo "<button class=\"tableRowButton\" onclick=\"updateGame(".$row['gid'].",'".$row['gname']."',".$row['gpoints'].",'".$row['gwinner']."','".$row['gtime']."')\">Ändern</button>";
            echo "</div>";
        }

        ?>
    </div>
</div>




<?php
mysqli_close($dbconnect);

?>

</body>
</html>
