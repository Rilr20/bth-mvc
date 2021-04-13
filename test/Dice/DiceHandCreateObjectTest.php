<?php

namespace Rilr\Dice;

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * test cases for class Dice
 */
class DiceHandCraeteObjectTest extends TestCase
{
    public function testCreateDiceHand()
    {
        $exp = 5;
        $sides = 6;
        $dicehand = new DiceHand($exp, $sides);
        $res = $dicehand->getDiceHandLenght();
        $this->assertEquals($exp, $res);
    }

    public function testThrowDice()
    {
        $sides = 6;
        $exp = 2;
        $dicehand = new DiceHand($exp, $sides);
        $dicehand->throw();
        $res = $dicehand->getLastRoll();
        $this->assertEquals($exp, count($res));
    }
}
