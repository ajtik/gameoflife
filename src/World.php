<?php declare(strict_types=1);


namespace GameOfLife;


/**
 * Class World
 *
 * @package GameOfLife
 * @license GNU General Public License v3.0
 * @author  Adam Čuba <cuba.adam@seznam.cz>
 * @link    https://github.com/cuba-adam/gameoflife
 */
class World
{
    /**
     * @var int
     */
    private $_size;

    /**
     * @var int
     */
    private $_iterations;

    /**
     * @var int
     */
    private $_species;

    /**
     * World constructor.
     * @param int $size
     * @param int $iterations
     * @param int $species
     */
    public function __construct(int $size, int $iterations, int $species)
    {
        $this->_size = $size;
        $this->_iterations = $iterations;
        $this->_species = $species;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->_size;
    }

    /**
     * @return int
     */
    public function getIterations(): int
    {
        return $this->_iterations;
    }

    /**
     * @return int
     */
    public function getSpecies(): int
    {
        return $this->_species;
    }
}