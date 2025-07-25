<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('xdebug.var_display_max_depth', 10); // увеличивает максимум до 10 уровней


require_once __DIR__ . '/src/Main.php';



$main = new Main();
$main->moveLeadFromBidToWaitingIfItsSumMore5000();









