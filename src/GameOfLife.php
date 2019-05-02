<?php declare(strict_types=1);

namespace GameOfLife;

use GameOfLife\Helpers\Matrix;
use Tracy\Debugger;

/**
 * Class GameOfLife
 *
 * @package GameOfLife
 * @license GNU General Public License v3.0
 * @author  Adam Čuba <cuba.adam@seznam.cz>
 * @link    https://github.com/cuba-adam/gameoflife
 */
class GameOfLife
{
    /**
     * @var Life
     */
    private $_life;

    private const OUTPUT_FILENAME = __DIR__ . "/../temp/out.xml";

    /**
     * GameOfLife constructor.
     *
     * @param string $inputFilename
     */
    public function __construct(string $inputFilename)
    {
        $this->_life = $this->_initializeFromXML($inputFilename);
    }

    public function start(): void
    {
        $newGenerationMatrix = $this->_life->startEvolution();
        $this->_generateOutputXml($newGenerationMatrix);
    }

    /**
     * Generates output xml file from last generation.
     *
     * @param Cell[][] $matrix
     */
    private function _generateOutputXml(array $matrix): void
    {
        $world = $this->_life->getWorld();

        $xml = new \SimpleXmlElement('<?xml version="1.0" encoding="UTF-8"?>'
                                    . '<life></life>');
        $worldTag = $xml->addChild("world");
        $worldTag->addChild("cells", (string)$world->getSize());
        $worldTag->addChild("iterations", (string)$world->getIterations());
        $worldTag->addChild("species", (string)$world->getSpecies());

        $organismsTag = $xml->addChild("organisms");

        $size = $world->getSize();

        for($x = 1; $x <= $size; $x++) {
            for($y = 1; $y <= $size; $y++) {
                $organismTag = $organismsTag->addChild("organism");
                $organismTag->addChild("x_pos", (string)$matrix[$x][$y]->getXPos());
                $organismTag->addChild("y_pos", (string)$matrix[$x][$y]->getYPos());
                $organismTag->addChild("species", (string)$matrix[$x][$y]->getSpeciesType());
            }
        }

        $xml->saveXML(self::OUTPUT_FILENAME);
    }

    /**
     * Loads configuration from XML file.
     *
     * @param  string $filename
     * @return Life
     */
    private function _initializeFromXML(string $filename): Life
    {
        $xml = simplexml_load_file($filename);

        if (!$xml instanceof \SimpleXMLElement) {
            throw new \RuntimeException("Cannot open input file with world configuration.");
        }

        $worldSize = (int)$xml->world->cells;
        $iterations = (int)$xml->world->iterations;
        $species = (int)$xml->world->species;

        if ($worldSize < 1
            || $species < 1
            || $iterations < 1
        ) {
            throw new \InvalidArgumentException("None of the configuration properties can be < 1.");
        }

        $world = new World($worldSize, $iterations, $species);

        // Generate empty matrix for start
        $matrix = Matrix::createSquareMatrixFilledWithCells($world->getSize());

        // Put values from our xml to matrix
        foreach($xml->organisms->organism as $organism) {
            $x = (int)$organism->x_pos;
            $y = (int)$organism->y_pos;
            $species = (int)$organism->species;

            if ($x < 0 || $y < 0 || $species < 0 || $species > $world->getSpecies()) {
                throw new \InvalidArgumentException("Wrong value/s for organism.");
            }

            $cell = $matrix[$x][$y];
            $cell->setXPos($x);
            $cell->setYPos($y);
            $cell->setSpeciesType($species);
        }

        return new Life($world, $matrix);
    }
}
