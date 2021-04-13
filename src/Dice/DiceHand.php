<?php

declare(strict_types=1);

namespace Rilr\Dice;

use Rilr\Dice\Dice;

/**
 * Class DiceHand.
 */
class DiceHand
{
    private $dices = [];
    public function __construct($numOfDice, $sides)
    {
        for ($i = 0; $i < $numOfDice; $i++) {
            $this->dices[$i] = new Dice($sides);
        }
    }

    public function throw(): void
    {
        $len = sizeOf($this->dices);
        for ($i = 0; $i < $len; $i++) {
            $this->dices[$i]->throw();
        }
    }

    public function getLastRoll(): array
    {
        // $returnString = "";
        $len = sizeOf($this->dices);
        $returnArray = [];
        for ($i = 0; $i < $len; $i++) {
            $dieRoll = $this->dices[$i]->getLastRoll();
            // $returnString = (string)$returnString . " " . (string)$dieRoll;
            $returnArray[$i] = $dieRoll;
        }
        // $returnString = $returnArray;
        return $returnArray;
    }
    public function getDiceHandLenght(): int
    {
        return count($this->dices);
    }
}
