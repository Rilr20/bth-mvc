<?php

declare(strict_types=1);

namespace Rilr\Dice;

/**
 * Class Dice.
 */
class Dice
{
    public int $sides;
    private int $latestThrow = 0;
    public function __construct($sides)
    {
        $this->sides = $sides;
    }
    public function throw(): int
    {
        $this->latestThrow = rand(1, $this->sides);
        return $this->latestThrow;
    }
    public function getLastRoll(): int
    {
        return $this->latestThrow;
    }
}
