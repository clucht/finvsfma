<?php
$ini_array = parse_ini_file("../config.ini");
$dbhost = $ini_array['dbhost'];
$dbuser = $ini_array['dbuser'];
$dbpass = $ini_array['dbpass'];
$db     = $ini_array['db'];

$isNew = 1;
$isUpdate = 0;

$dbconnect=mysqli_connect($dbhost,$dbuser,$dbpass,$db);

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
    (`id`,`name`,`points`,`winner_id`,`time`)values
    ('$id','$game','$points','$winner','$time')
    where id = '$id'";
        mysqli_query($dbconnect,$ins_query) or die("Database connection failed: " . $dbconnect->connect_error);
    }


    $status = "New Record Inserted Successfully.
    </br></br><a href='view.php'>View Inserted Record</a>";
}

$participants_query = mysqli_query($dbconnect, "SELECT * FROM participants") or die (mysqli_error($dbconnect));

?>

<div id="box">
    <div id="form">
        <form action="games.php" method="post">
            <input type="hidden" name="new" value=" <?php echo $isNew?>" />
            <input type="hidden" name="update" value=" <?php echo $isUpdate?>" />
            <div class="input"> ID: <input type="number" name="id" /> </div>
            <div class="input"> Spiel: <input type="text" name="game" /> </div>
            <div class="input"> Punkte: <input type="number" name="points" /> </div>
            <div class="input"> Gewinner: <select name="winner">
                    <?php
                        while ($row = mysqli_fetch_array($participants_query)) {
                            echo "<option value=\"".$row['participant_id']."\">".$row['name']."</option>";
                        }

                    ?>
                </select> </div>
            <div class="input"> Zeit: <input type="datetime-local" name="time" /> </div>
            <div> <input type="submit"></div>
        </form>
    </div>

    <div id="table">
        <div class="tableRow">
            <div class="tableRowID">ID</div>
            <div class="tableRowGame">Spiel</div>
            <div class="tableRowPoints">Punkte</div>
            <div class="tableRowWinner">Gewinner</div>
            <div class="tableRowTime">Zeit</div>
        </div>
        <?php
        $games_query = mysqli_query($dbconnect, "SELECT g.id as gid, g.name as gname,g.points as gpoints, p.name as pname, g.time as gtime FROM games g JOIN participants p ON g.winner_id = p.participant_id ") or die (mysqli_error($dbconnect));


        echo "<div class=\"tableRow\">";
                while ($row = mysqli_fetch_array($games_query)) {
                    echo "<div class=\"tableRowID\">".$row['gid']."</div>";
                    echo "<div class=\"tableRowGame\">".$row['gname']."</div>";
                    echo "<div class=\"tableRowPoints\">".$row['gpoints']."</div>";
                    echo "<div class=\"tableRowWinner\">".$row['pname']."</div>";
                    echo "<div class=\"tableRowTime\">".$row['gtime']."</div>";

                }
            echo "</div>";

        ?>
    </div>
</div>




<?php
mysqli_close($dbconnect);

?>