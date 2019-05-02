<?php declare(strict_types=1);

namespace GameOfLife;


use GameOfLife\Helpers\Matrix;

/**
 * Class Life
 *
 * @package GameOfLife
 * @author  Adam Čuba <cuba.adam@seznam.cz>
 * @link    https://github.com/cuba-adam/gameoflife
 * @license GNU General Public License v3.0
 */
class Life
{
    /**
     * @var Cell[][]
     */
    private $_cells;

    /**
     * @var World
     */
    private $_world;

    /**
     * Life constructor.
     * @param World $world
     * @param Cell[][] $cells
     */
    public function __construct(World $world, array $cells)
    {
        $this->_world = $world;
        $this->_cells = $cells;
    }

    /**
     * @return World
     */
    public function getWorld(): World
    {
        return $this->_world;
    }

    /**
     * @return Cell[][]
     */
    public function getCells(): array
    {
        return $this->_cells;
    }

    /**
     * Makes new generation of organisms made of cells.
     *
     * @return Cell[][]
     */
    public function startEvolution(): array
    {
        $i = 0;
        $x = 1;
        $y = 1;
        $size = $this->_world->getSize() + 1;
        $nextGeneration = Matrix::cloneMatrixOfCells($this->_cells); // need to clone, because we have objects in array

        while ($i < $this->_world->getIterations()) {
            while ($x < $size) {
                while ($y < $size) {
                    /** @var Cell */
                    $nextGeneration[$x][$y]->liveDieOrRevive($this->_cells);
                    $y++;
                }
                $y = 1;
                $x++;
            }
            $x = 1;
            $i++;
            $this->_cells = Matrix::cloneMatrixOfCells($nextGeneration);
        }

        return $this->_cells;
    }
}