<?php declare(strict_types=1);


namespace GameOfLife;

use Tracy\Debugger;

/**
 * Class Organism
 *
 * @package GameOfLife
 * @license GNU General Public License v3.0
 * @author  Adam Čuba <cuba.adam@seznam.cz>
 * @link    https://github.com/cuba-adam/gameoflife
 */
class Cell
{
    /**
     * @var int
     */
    private $_xPos;

    /**
     * @var int
     */
    private $_yPos;

    /**
     * Species type
     * 0 = dead
     *
     * @var int
     */
    private $_speciesType;

    public const DEAD = 0;

    /**
     * Organism constructor.
     *
     * @param int $x
     * @param int $y
     * @param int $speciesType
     */
    public function __construct(int $x, int $y, int $speciesType = 0)
    {
        $this->_xPos = $x;
        $this->_yPos = $y;
        $this->_speciesType = $speciesType;
    }

    /**
     * @param Cell[][] $matrix
     */
    public function liveDieOrRevive(array $matrix): void
    {
        $neighbors = $this->getNeighborCountsWithType($matrix);
        $species = $this->getSpeciesType();

        if ($species === self::DEAD) {
            $speciesThatCouldBeBorn = [];

            for ($i = 1; $i < count($neighbors) + 1; $i++) {
                // if there are 3 neighbors with same type, they may give birth to this cell
                if ($neighbors[$i] === 3) {
                    $speciesThatCouldBeBorn[] = $i;
                }
            }

            if (!empty($speciesThatCouldBeBorn)) {
                // choose randomly if there are more neighbors with same type
                $this->setSpeciesType($speciesThatCouldBeBorn[array_rand($speciesThatCouldBeBorn)]);
            }

        } elseif ($neighbors[$species] < 2 || $neighbors[$species] > 3) {
            $this->setSpeciesType(self::DEAD);
        }
    }

    /**
     * @param Cell[][] $matrix
     * @return array|int[]
     */
    public function getNeighborCountsWithType(array $matrix): array
    {
        $neighborCountsWithType = [];
        $neighborCells = $this->getNeighborsCells($matrix);

        foreach($neighborCells as $neighborCell) {
            if ($neighborCell->getSpeciesType() > self::DEAD) {
                $neighborCountsWithType[$neighborCell->getSpeciesType()]
                    = $neighborCountsWithType[$neighborCell->getSpeciesType()] ?? 0 ;
                $neighborCountsWithType[$neighborCell->getSpeciesType()]++;
            }
        }

        return $neighborCountsWithType;
    }

    /**
     * @param Cell[][] $matrix
     * @return Cell[]
     */
    public function getNeighborsCells(array $matrix): array
    {
        $cells = [];
        $x = $this->getXPos();
        $y = $this->getYPos();
        $matrixSize = count($matrix);

        if ($x > 1 && $y > 1) $cells[] = $matrix[$x - 1][$y - 1]; // top left
        if ($x > 1) $cells[] = $matrix[$x - 1][$y]; // top
        if ($x > 1 && $y < $matrixSize) $cells[] = $matrix[$x - 1][$y + 1]; // top right
        if ($y > 1) $cells[] = $matrix[$x][$y - 1]; // left
        if ($y < $matrixSize) $cells[] = $matrix[$x][$y + 1]; // right
        if ($x < $matrixSize && $y > 1 ) $cells[] = $matrix[$x + 1][$y - 1]; // bottom left
        if ($x < $matrixSize) $cells[] = $matrix[$x + 1][$y]; // bottom
        if ($x < $matrixSize && $y < $matrixSize) $cells[] = $matrix[$x + 1][$y + 1]; // bottom right

        return $cells;
    }

    /**
     * @return int
     */
    public function getSpeciesType(): int
    {
        return $this->_speciesType;
    }

    /**
     * @return int
     */
    public function getYPos(): int
    {
        return $this->_yPos;
    }

    /**
     * @return int
     */
    public function getXPos(): int
    {
        return $this->_xPos;
    }

    /**
     * @param int $xPos
     */
    public function setXPos(int $xPos): void
    {
        $this->_xPos = $xPos;
    }

    /**
     * @param int $yPos
     */
    public function setYPos(int $yPos): void
    {
        $this->_yPos = $yPos;
    }

    /**
     * @param int $speciesType
     */
    public function setSpeciesType(int $speciesType): void
    {
        $this->_speciesType = $speciesType;
    }
}