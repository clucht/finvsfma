<?php
$ini_array = parse_ini_file("config.ini");

$eventname = $ini_array['eventname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="style.css" rel="stylesheet" type="text/css">
    <meta charset="UTF-8">
    <title><?php echo $eventname?></title>
</head>
<body>

<header></header>

<div class="logo"><img class="logo_img" src="./img/logo.png"></div>

<div class="bar_wrapper">
    <div class="bar_top"></div>
    <div class="bar" id="bar">

        <script src="bar.js"></script>

    </div>

</div>




<footer></footer>

</body>
</html>