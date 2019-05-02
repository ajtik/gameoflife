<?php declare(strict_types=1);

use GameOfLife\GameOfLife;

//error_reporting(0);

require "vendor/autoload.php";

try {
    $gameOfLife = new GameOfLife(__DIR__ . "/../temp/input.xml");
    $gameOfLife->start();
} catch(Exception $e) {
    echo $e->getMessage();
}