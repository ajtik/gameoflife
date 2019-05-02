<?php declare(strict_types=1);

namespace GameOfLife\Helpers;

use GameOfLife\Cell;
use Tracy\Debugger;

/**
 * Class MatrixHelper
 *
 * @package GameOfLife
 * @author  Adam Čuba <cuba.adam@seznam.cz>
 * @link    https://github.com/cuba-adam/gameoflife
 * @license GNU General Public License v3.0
 */
class Matrix
{
    /**
     * @param int $size
     * @return Cell[][]
     */
    public static function createSquareMatrixFilledWithCells(int $size)
    {
        $matrix = [];
        $size += 1;

        for($i = 1; $i < $size; $i++) {
            for($j = 1; $j < $size; $j++) {
                $matrix[$i][$j] = new Cell($i, $j, 0);
            }
        }

        return $matrix;
    }

    public static function cloneMatrixOfCells(array $arrayToClone)
    {
        return array_map(function ($cellArray) {
            return array_map(function($cell) {
                return clone $cell;
            }, $cellArray);
        }, $arrayToClone);
    }
}